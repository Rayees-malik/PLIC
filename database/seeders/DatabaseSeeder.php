<?php

namespace Database\Seeders;

use Database\Seeders\Signoffs\BrandDiscoRequestSignoffConfigSeeder;
use Database\Seeders\Signoffs\BrandSignoffConfigSeeder;
use Database\Seeders\Signoffs\InventoryRemovalSignoffConfigSeeder;
use Database\Seeders\Signoffs\MarketingAgreementSignoffConfigSeeder;
use Database\Seeders\Signoffs\PricingAdjustmentSignoffConfigSeeder;
use Database\Seeders\Signoffs\ProductDelistRequestSignoffConfigSeeder;
use Database\Seeders\Signoffs\ProductSignoffConfigSeeder;
use Database\Seeders\Signoffs\PromoSignoffConfigSeeder;
use Database\Seeders\Signoffs\RetailerSignoffConfigSeeder;
use Database\Seeders\Signoffs\VendorSignoffConfigSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Users
            RolesAndAbilitiesSeeder::class,

            // Lookups
            UnitOfMeasureTableSeeder::class,
            CountriesTableSeeder::class,
            CurrenciesTableSeeder::class,
            AllergensTableSeeder::class,
            DistributorsTableSeeder::class,
            WarehousesTableSeeder::class,

            // Products
            ProductFlagsTableSeeder::class,
            CertificationsTableSeeder::class,
            PackagingMaterialsTableSeeder::class,
            ProductCategoriesSubcategoriesTableSeeder::class,

            // Signoffs
            BrandDiscoRequestSignoffConfigSeeder::class,
            BrandSignoffConfigSeeder::class,
            InventoryRemovalSignoffConfigSeeder::class,
            MarketingAgreementSignoffConfigSeeder::class,
            PricingAdjustmentSignoffConfigSeeder::class,
            ProductDelistRequestSignoffConfigSeeder::class,
            ProductSignoffConfigSeeder::class,
            PromoSignoffConfigSeeder::class,
            RetailerSignoffConfigSeeder::class,
            VendorSignoffConfigSeeder::class,
        ]);

        if (App::environment(['development', 'local', 'testing'])) {
            $this->call([DevelopmentDataSeeder::class]);
        }
    }
}
