<?php

namespace App\Console\Commands;

use App\Models\AS400\AS400BrandInvoice;
use App\Models\AS400\AS400BrandOpenAP;
use App\Models\AS400\AS400BrandPOReceived;
use App\Models\AS400\AS400Consignment;
use App\Models\AS400\AS400Customer;
use App\Models\AS400\AS400CustomerGroup;
use App\Models\AS400\AS400Freight;
use App\Models\AS400\AS400Margin;
use App\Models\AS400\AS400Pricing;
use App\Models\AS400\AS400SpecialPricing;
use App\Models\AS400\AS400StockData;
use App\Models\AS400\AS400Supersedes;
use App\Models\AS400\AS400UpcomingPriceChange;
use App\Models\AS400\AS400WarehouseStock;
use App\Models\AS400\AS400ZeusRetailer;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AS400MigrateCommand extends Command
{
    protected $signature = 'as400:migrate
                            {table?* : List of tables to migrate}';

    protected $description = 'Migrate AS400 tables into the PLIC database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tableMapping = [
            'brand_invoice' => [AS400BrandInvoice::class, ['brands']],
            'brand_open_ap' => [AS400BrandOpenAP::class, ['brands']],
            'brand_po_received' => [AS400BrandPOReceived::class, ['brands']],
            'customers' => [AS400Customer::class, []],
            'customer_groups' => [AS400CustomerGroup::class, []],
            'consignment' => [AS400Consignment::class, ['brands']],
            'freight' => [AS400Freight::class, ['brands']],
            'margin' => [AS400Margin::class, ['brands']],
            'pricing' => [AS400Pricing::class, ['products']],
            'special_pricing' => [AS400SpecialPricing::class, ['brands', 'products']],
            'stock_data' => [AS400StockData::class, ['products']],
            'supercededs' => [AS400Supersedes::class, ['products']],
            'upcoming_price_changes' => [AS400UpcomingPriceChange::class, ['products']],
            'warehouse_stock' => [AS400WarehouseStock::class, ['products']],
            'zeus_retailers' => [AS400ZeusRetailer::class, []],
        ];

        Log::info('AS400MigrateCommand started...');
        $products = Product::select(['id', 'stock_id'])->get();
        $brands = Brand::select(['id', 'brand_number', 'finance_brand_number', 'vendor_id', 'category_code'])->get();

        $tables = collect($tableMapping)->when($this->hasArgument('table'), function ($collection) {
            if (is_array($this->argument('table')) && count($this->argument('table')) === 0) {
                return $collection;
            }

            return $collection->filter(
                fn ($value, $table) => in_array($table, $this->argument('table'))
            );
        })->each(function ($value, $key) use ($brands, $products) {
            $start = microtime(true);

            $this->info('Importing ' . $key . ' [ ' . $value[0] . ' ]...');
            Log::info('Importing ' . $key . ' [ ' . $value[0] . ' ]...');

            [$class, $dependencies] = $value;

            if (in_array('brands', $dependencies) && in_array('products', $dependencies)) {
                $class::migrate($products, $brands);
                Log::debug('Running ' . $class . '::migrate($products, $brands)');
            } elseif (in_array('brands', $dependencies)) {
                $class::migrate($brands);
                Log::debug('Running ' . $class . '::migrate($brands)');
            } elseif (in_array('products', $dependencies)) {
                $class::migrate($products);
                Log::debug('Running ' . $class . '::migrate($products)');
            } else {
                $class::migrate();
                Log::debug('Running ' . $class . '::migrate()');
            }

            $this->comment('Completed import of ' . $key . ' in ' . strval(round(microtime(true) - $start, 1)) . ' seconds.');
            Log::info('Completed import of ' . $key . ' in ' . strval(round(microtime(true) - $start, 1)) . ' seconds.');
        });

        $overallStart = microtime(true);

        $this->info('Migrations completed in ' . strval(round(microtime(true) - $overallStart, 1)) . ' seconds.');
        Log::info('AS400MigrateCommand completed in ' . strval(round(microtime(true) - $overallStart, 1)) . ' seconds.');
    }
}
