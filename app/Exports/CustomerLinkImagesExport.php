<?php

namespace App\Exports;

use App\Contracts\Exports\Exportable;
use App\Contracts\Exports\HasCriteria;
use App\Helpers\ImageHelper;
use App\Helpers\StatusHelper;
use App\Helpers\ZipHelper;
use App\Media;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use YlsIdeas\FeatureFlags\Facades\Features;

class CustomerLinkImagesExport implements Exportable, HasCriteria, ShouldQueue
{
    private ?Carbon $sinceDate = null;
    private ?array $stockIds = null;
    private bool $onlyActive;
    private bool $includeLargeImage;
    private bool $includeOriginalImage;
    private bool $includeSmallImage;

    const SMALL_WIDTH = 75;

    const SMALL_HEIGHT = 75;

    const LARGE_WIDTH = 288;

    const LARGE_HEIGHT = 288;

    public function __construct(public ?string $filename = null)
    {
    }

    public function getFilename(): string
    {
        return $this->filename ?? 'customer-link-images.zip';
    }

    public function setFilename(string $filename): void
    {
    }

    public function setCriteria(...$criteria): self
    {
        if (Arr::get($criteria, 'sinceDate') != null) {
            $this->sinceDate = Carbon::parse(Arr::get($criteria, 'sinceDate'));
        }

        if (Arr::has($criteria, 'stockIds') && Arr::get($criteria, 'stockIds')) {
            $this->stockIds = explode(' ', preg_replace('/\ +/', ' ', preg_replace('/[^A-Za-z0-9\ ]/', ' ', Arr::get($criteria, 'stockIds'))));
        }

        if (Features::accessible('customer-link-images-export-tweaks')) {
            $this->onlyActive = Arr::get($criteria, 'onlyActive', false);
            $this->includeLargeImage = Arr::get($criteria, 'includeLargeImage', false);
            $this->includeOriginalImage = Arr::get($criteria, 'includeOriginalImage', false);
            $this->includeSmallImage = Arr::get($criteria, 'includeSmallImage', false);
        }

        return $this;
    }

    public function export()
    {
        if (Features::accessible('customer-link-images-export-tweaks')) {
            Log::notice('Started running export: '. static::class, [
                'userId' => auth()->id(),
                'stockIds' => $this->stockIds,
                'sinceDate' => $this->sinceDate,
                'onlyActive' => $this->onlyActive,
                'includeLargeImage' => $this->includeLargeImage,
                'includeSmallImage' => $this->includeSmallImage,
                'includeOriginalImage' => $this->includeOriginalImage,
            ]);
        } else {
            Log::notice('Started running export: '. static::class, [
                'userId' => auth()->id(),
                'stockIds' => $this->stockIds,
                'sinceDate' => $this->sinceDate,
            ]);
        }

        if (Features::accessible('customer-link-images-export-tweaks')) {
            if ($this->stockIds) {
                $media = Media::with(['model' => function ($query) {
                    $query->select('id', 'stock_id');
                }])
                    ->whereHasMorph('model', Product::class, function ($query) {
                        $query->whereIn('stock_id', $this->stockIds)
                            ->when($this->onlyActive, function ($query) {
                                $query->where('status', StatusHelper::ACTIVE);
                            });
                    })
                    ->where('collection_name', 'product')
                    ->get();
            } else {
                $media = Media::with(['model' => function ($query) {
                    $query->approved()->select('id', 'stock_id');
                }])
                    ->whereHasMorph('model', Product::class, function ($query) {
                        $query
                            ->approved()
                            ->when($this->sinceDate != null, fn ($query) => $query->whereDate('updated_at', '>=', $this->sinceDate))
                            ->where(function ($query) {
                                $query
                                    ->whereHas('media', function ($query) {
                                        $query->where('collection_name', 'product')->whereNull('cloned_from_id');
                                    })
                                    ->orWhere(function ($query) {
                                        $query->whereHas('media', function ($query) {
                                            $query->where('collection_name', 'product');
                                        })->whereHas('signoff', function ($query) {
                                            $query->where('new_submission', true);
                                        });
                                    });
                            })->when($this->onlyActive, function ($query) {
                                $query->where('status', StatusHelper::ACTIVE);
                            });
                    })
                    ->where('collection_name', 'product')
                    ->get();
            }
        } else {
            if ($this->stockIds) {
                $media = Media::with(['model' => function ($query) {
                    $query->select('id', 'stock_id');
                }])
                    ->whereHasMorph('model', Product::class, function ($query) {
                        $query->whereIn('stock_id', $this->stockIds);
                    })
                    ->where('collection_name', 'product')
                    ->get();
            } else {
                $media = Media::with(['model' => function ($query) {
                    $query->approved()->select('id', 'stock_id');
                }])
                    ->whereHasMorph('model', Product::class, function ($query) {
                        $query
                            ->approved()
                            ->whereDate('updated_at', '>=', $this->sinceDate)
                            ->where(function ($query) {
                                $query
                                    ->whereHas('media', function ($query) {
                                        $query->where('collection_name', 'product')->whereNull('cloned_from_id');
                                    })
                                    ->orWhere(function ($query) {
                                        $query->whereHas('media', function ($query) {
                                            $query->where('collection_name', 'product');
                                        })->whereHas('signoff', function ($query) {
                                            $query->where('new_submission', true);
                                        });
                                    });
                            });
                    })
                    ->where('collection_name', 'product')
                    ->get();
            }
        }

        if (! $media->count()) {
            flash('There were no images found for that search criteria.', 'danger');

            return redirect()->back();
        }

        $files = [];
        $filesNotFound = [];
        $filesFound = 0;
        $filesCount = 0;
        $filesNotFoundCount = 0;

        foreach ($media as $file) {
            $filesCount++;

            if (! realpath($file->getPath())) {
                $filesNotFound["{$file->model->stock_id}}"] = $file->getPath();

                $filesNotFoundCount++;

                continue;
            }

            if (Features::accessible('customer-link-images-export-tweaks')) {
                if ($this->includeOriginalImage) {
                    $files["original/{$file->model->stock_id}.png"] = ImageHelper::optimizePng($file->getPath());
                }

                if ($this->includeLargeImage) {
                    $largePath = ImageHelper::resizeImage($file->getPath(), static::LARGE_WIDTH, static::LARGE_HEIGHT, true);

                    if ($largePath) {
                        $files["{$file->model->stock_id}.png"] = $largePath;
                    }
                }

                if ($this->includeSmallImage) {
                    $smallPath = ImageHelper::resizeImage($file->getPath(), static::LARGE_WIDTH, static::SMALL_HEIGHT, true);

                    if ($smallPath) {
                        $files["small/{$file->model->stock_id}.png"] = $smallPath;
                    }
                }
            } else {
                $largePath = ImageHelper::resizeImage($file->getPath(), static::LARGE_WIDTH, static::LARGE_HEIGHT, true);
                $smallPath = ImageHelper::resizeImage($file->getPath(), static::LARGE_WIDTH, static::SMALL_HEIGHT, true);

                if ($largePath) {
                    $files["{$file->model->stock_id}.png"] = $largePath;
                }

                if ($smallPath) {
                    $files["small/{$file->model->stock_id}.png"] = $smallPath;
                }
            }

            $filesFound++;
        }

        if ($filesFound == 0) {
            Log::warning("There were {$filesCount} images found in the database but not on the filesystem.", [
                json_encode($filesNotFound),
            ]);

            flash("There were {$filesCount} images found in the database but none found on the filesystem.", 'danger');

            return redirect()->back();
        }

        if ($filesNotFoundCount > 0) {
            Log::warning("There were {$filesNotFoundCount} images found in the database but not on the filesystem.", [
                json_encode($filesNotFound),
            ]);

            flash("There were {$filesNotFoundCount} images found in the database but not on the filesystem. See the error log in the zip archive for details.", 'danger');

            $errorLog = tempnam(sys_get_temp_dir(), 'plic_');
            file_put_contents($errorLog, json_encode($filesNotFound));
            $files['errors.txt'] = $errorLog;
        }

        if (Features::accessible('customer-link-images-export-tweaks')) {
            Log::notice('Finished running export: '. static::class, [
                'userId' => auth()->id(),
                'stockIds' => $this->stockIds,
                'sinceDate' => $this->sinceDate,
                'onlyActive' => $this->onlyActive,
                'includeLargeImage' => $this->includeLargeImage,
                'includeSmallImage' => $this->includeSmallImage,
                'includeOriginalImage' => $this->includeOriginalImage,
            ]);
        } else {
            Log::notice('Finished running export: '. static::class, [
                'userId' => auth()->id(),
                'stockIds' => $this->stockIds,
                'sinceDate' => $this->sinceDate,
            ]);
        }

        return ZipHelper::zipFiles($files, true);
    }
}
