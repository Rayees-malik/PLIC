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

class SignoffConfigSeeder extends Seeder
{
    public function run()
    {
        $this->call([
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
    }
}
