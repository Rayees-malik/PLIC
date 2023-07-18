<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class DevelopmentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Config::get('app')['useDevelopmentSeeder']) {
            $this->call([\Database\Seeders\Development\TestUserSeeder::class]);

            // Only run iseed seeders locally, development has migration file
            // $this->call([
            //     \Database\Seeders\Development\iseed\BrokersTableSeeder::class,
            //     \Database\Seeders\Development\iseed\VendorsTableSeeder::class,
            //     \Database\Seeders\Development\iseed\AddressesTableSeeder::class,
            //     \Database\Seeders\Development\iseed\BrandsTableSeeder::class,
            //     \Database\Seeders\Development\iseed\BrandBrokerTableSeeder::class,
            //     \Database\Seeders\Development\iseed\CatalogueCategoriesTableSeeder::class,
            //     \Database\Seeders\Development\iseed\ContactsTableSeeder::class,
            //     \Database\Seeders\Development\iseed\ProductsTableSeeder::class,
            //     \Database\Seeders\Development\iseed\DimensionsTableSeeder::class,
            //     \Database\Seeders\Development\iseed\RegulatoryInfoTableSeeder::class,
            //     \Database\Seeders\Development\iseed\AllergenProductTableSeeder::class,
            //     \Database\Seeders\Development\iseed\CertificationProductTableSeeder::class,
            //     \Database\Seeders\Development\iseed\PackagingMaterialProductTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400FreightTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400CustomersTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400PricingTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400SpecialPricingTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400SupersedesTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400StockDataTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400UpcomingPriceChangesTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400WarehouseStockTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400ZeusRetailersTableSeeder::class,
            //     // \Database\Seeders\Development\iseed\As400VendorInvoicesTableSeeder::class,
            //     // \Database\Seeders\Development\iseed\As400VendorOpenApTableSeeder::class,
            //     // \Database\Seeders\Development\iseed\As400VendorPoReceivedTableSeeder::class,
            //     \Database\Seeders\Development\iseed\As400MarginsTableSeeder::class,
            // ]);

            // // Generate Test Periods
            // \App\Models\PromoPeriod::generatePeriods();

            // $retailers = \App\Models\Retailer::where('allow_promos', true)->get();
            // foreach ($retailers as $retailer) {
            //     \App\Models\PromoPeriod::generatePeriods($retailer);
            // }
        }
    }
}
