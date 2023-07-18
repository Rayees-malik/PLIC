<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\CatalogueCategory;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFrenchTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:french-translations {translationFile} {--cutoff= : The cutoff date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the French translations from the provided translation template';

    private array $brandErrors = [];

    private array $catalogueCategoryErrors = [];

    private array $productErrors = [];

    private ?Carbon $cutoffDate = null;

    private ?Spreadsheet $spreadsheet = null;

    private $progressBar;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! app()->runningInConsole()) {
            $this->error('This command must be run from the console.');

            return 1;
        }

        if (! $this->option('cutoff')) {
            $this->error('You must provide a cutoff date');

            return 1;
        }

        $this->comment('Updating French translations...');

        try {
            $this->cutoffDate = Carbon::createFromFormat('Y-m-d', $this->option('cutoff'))->startOfDay();
        } catch (InvalidFormatException $e) {
            $this->error('Invalid cutoff date format. Please use YYYY-MM-DD');

            return 1;
        }

        $this->info("Skipping fields updated after {$this->cutoffDate->format('Y-m-d')}");

        $this->loadFile();

        if (! $this->spreadsheet) {
            $this->error('The file could not be loaded.');

            return 1;
        }

        if ($this->getTotalRows() == 0) {
            $this->comment('No rows found in the file.');

            return 0;
        }

        $this->info('There are ' . $this->getTotalRows() . ' records in the translation file.');

        if ($this->confirm('Are you sure you want to update the translations?', false)) {
            $proceed = $this->confirm('Are you REALLY sure you want to update the translations?  THIS CAN NOT BE UNDONE!', false);

            if ($proceed) {
                $this->updateTranslations();

                return 0;
            }
        }

        $this->info('No records updated!');

        return 0;
    }

    private function updateTranslations()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        DB::beginTransaction();

        try {
            $products = collect($sheet->rangeToArray("B3:D{$highestRow}", null, false, false, false))
                ->zip($sheet->rangeToArray("I3:J{$highestRow}", null, false, false, false))
                ->map(function ($item, $key) {
                    return [
                        'stock_id' => $item[0][0],
                        'name' => $item[0][1],
                        'name_fr' => $item[0][2],
                        'description' => $item[1][0],
                        'description_fr' => $item[1][1],
                    ];
                })
                ->sortBy('stock_id')
                ->reject(fn ($item) => $item['stock_id'] == null);

            $brands = collect($sheet->rangeToArray("E3:H{$highestRow}", null, false, false, false))
                ->map(function ($item, $key) {
                    return [
                        'name' => $item[0],
                        'name_fr' => $item[1],
                        'description' => $item[2],
                        'description_fr' => $item[3],
                    ];
                })->unique('name')
                ->sortBy('name')
                ->reject(fn ($item) => $item['name'] == null);

            $catalogueCategories = collect($sheet->rangeToArray("K3:L{$highestRow}", null, false, false, false))
                ->map(function ($item, $key) {
                    return [
                        'name' => $item[0],
                        'name_fr' => $item[1],
                    ];
                })->unique('name')
                ->sortBy('name')
                ->reject(fn ($item) => $item['name'] == null);

            $progressBar = $this->output->createProgressBar($products->count() + $brands->count() + $catalogueCategories->count());
            $progressBar->start();

            $products
                ->each(function ($item, $row) use ($progressBar) {
                    try {
                        $product = Product::with('ledgers')->where('stock_id', $item['stock_id'])->firstOrFail();

                        if ($product->ledgers()->count() == 0) {
                            if ($item['name_fr']) {
                                $attributes['name_fr'] = $item['name_fr'];
                            }

                            if ($item['description_fr']) {
                                $attributes['description_fr'] = $item['description_fr'];
                            }
                        } else {
                            $attributes = $product->ledgers()->with(['user', 'recordable'])->get()
                                ->reduce(function ($carry, $ledger) use ($item) {
                                    $data = $ledger->getData();

                                    if ($ledger->event === 'created') {
                                        if ($item['name_fr']) {
                                            $carry['name_fr'] = $item['name_fr'];
                                        }

                                        if ($item['description_fr']) {
                                            $carry['description_fr'] = $item['description_fr'];
                                        }

                                        return $carry;
                                    }

                                    if (array_key_exists('name_fr', $data) && $data['updated_at'] >= $this->cutoffDate) {
                                        unset($carry['name_fr']);
                                    } else {
                                        if ($item['name_fr']) {
                                            $carry['name_fr'] = $item['name_fr'];
                                        }
                                    }

                                    if (array_key_exists('description_fr', $data) && $data['updated_at'] >= $this->cutoffDate) {
                                        unset($carry['description_fr']);
                                    } else {
                                        if ($item['description_fr']) {
                                            $carry['description_fr'] = $item['description_fr'];
                                        }
                                    }

                                    return $carry;
                                });
                        }

                        if ($attributes) {
                            $product->fill($attributes)->save();

                            $product->refresh();

                            if ($this->verbosity == OutputInterface::VERBOSITY_VERBOSE) {
                                Log::info("Updated product with {$product->stock_id} [{$product->id}]");
                            }
                        }
                    } catch (ModelNotFoundException $e) {
                        $this->productErrors[] = [
                            'source_row' => $row + 3,
                            'stock_id' => $item['stock_id'],
                            'name' => $item['name'],
                            'name_fr' => $item['name_fr'],
                            'description' => $item['description'],
                            'description_fr' => $item['description_fr'],
                            'reason' => 'Cannot find product with stock ID ' . $item['stock_id'] . ' in database.',
                        ];
                        Log::error("Cannot find product with stock ID '{$item['stock_id']}' in database.");
                    } catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    $progressBar->advance();
                });

            $brands->each(function ($item, $row) use ($progressBar) {
                try {
                    if (! $item['name']) {
                        return;
                    }

                    $brand = Brand::with('ledgers')->where('name', $item['name'])->firstOrFail();

                    if ($brand->ledgers()->count() == 0) {
                        if ($item['description_fr']) {
                            $attributes['description_fr'] = $item['description_fr'];
                        }
                    } else {
                        $attributes = $brand
                            ->ledgers()
                            ->get()
                            ->reduce(function ($carry, $ledger) use ($item) {
                                $data = $ledger->getData();

                                if ($ledger->event === 'created') {
                                    if ($item['description_fr']) {
                                        $carry['description_fr'] = $item['description_fr'];
                                    }

                                    return $carry;
                                }

                                if (array_key_exists('description_fr', $data) && $data['updated_at'] >= $this->cutoffDate) {
                                    unset($carry['description_fr']);
                                } else {
                                    if ($item['description_fr']) {
                                        $carry['description_fr'] = $item['description_fr'];
                                    }
                                }

                                return $carry;
                            });
                    }

                    if ($attributes) {
                        $brand->fill($attributes)->save();

                        $brand->refresh();

                        if ($this->verbosity >= OutputInterface::VERBOSITY_VERBOSE) {
                            Log::info("Updated brand {$brand->name} [{$brand->id}]");
                        }
                    }
                } catch (ModelNotFoundException $e) {
                    $this->brandErrors[] = [
                        'source_row' => $row + 3,
                        'name' => $item['name'],
                        'name_fr' => $item['name_fr'],
                        'description' => $item['description'],
                        'description_fr' => $item['description_fr'],
                        'reason' => 'Cannot find brand with name ' . $item['name'] . ' in database.',
                    ];
                    Log::error("Cannot find brand with name '{$item['name']}' in database.");
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                $progressBar->advance();
            });

            $catalogueCategories->each(function ($item, $row) use ($progressBar) {
                try {
                    if (! $item['name']) {
                        return;
                    }

                    $catalogueCategories = CatalogueCategory::where('name', $item['name']);

                    if ($catalogueCategories->count() > 0) {
                        $catalogueCategories->update([
                            'name_fr' => $item['name_fr'],
                        ]);

                        if ($this->verbosity >= OutputInterface::VERBOSITY_VERBOSE) {
                            Log::info("Updated all catalogue categories with name {$item['name']} to {$item['name_fr']}");
                        }
                    } else {
                        $this->catalogueCategoryErrors[] = [
                            'source_row' => $row + 3,
                            'name' => $item['name'],
                            'name_fr' => $item['name_fr'],
                            'reason' => 'Cannot find any catalogue categories with name ' . $item['name'] . ' in database.',
                        ];

                        Log::error("Cannot find any catalogue categories with name '{$item['name']}' in database.");
                    }

                    $progressBar->advance($catalogueCategories->count());
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            });

            $progressBar->finish();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function loadFile()
    {
        $filePath = $this->argument('translationFile');

        if (File::exists($filePath)) {
            $reader = new Xlsx;
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);

            $this->spreadsheet = $reader->load($filePath);
        } else {
            $this->error("File \"{$filePath}\" does not exist");
        }
    }

    private function getTotalRows()
    {
        return $this->spreadsheet->getActiveSheet()->getHighestRow() - 2;
    }
}
