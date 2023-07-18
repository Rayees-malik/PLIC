<?php

namespace Database\Seeders\Signoffs;

use App\Models\SignoffConfig;
use App\Models\SignoffConfigStep;
use Bouncer;
use Illuminate\Database\Seeder;

class PromoSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        1 => [
            'approval_to_type' => 'user',
            'approval_to' => 'brand.vendorRelationsSpecialist',
        ],
        2 => [
            'approval_to_type' => 'role',
            'approval_to' => 'finance',
        ],
    ];

    public function run()
    {
        $signoffConfig = SignoffConfig::create([
            'model' => 'App\Models\Promo',
            'show_route' => 'promos.show',
        ]);

        $step1 = new SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Vendor Relations';
        $step1->form_request = 'App\Http\Requests\Promos\PromoFormRequest';
        $step1->form_view = 'promos.form-controls';
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffPromoVendorRelations = Bouncer::ability()->where('name', 'signoff.product.promo.vendorrelations')->first();
        $signoffRetailerPromos = Bouncer::ability()->where('name', 'signoff.retailer.promo')->first();
        $step1->abilities()->attach($signoffPromoVendorRelations);
        $step1->abilities()->attach($signoffRetailerPromos);

        $step2 = new \App\Models\SignoffConfigStep;
        $step2->signoffConfig()->associate($signoffConfig);
        $step2->step = 2;
        $step2->name = 'Finance';
        $step2->form_request = 'App\Http\Requests\Promos\PromoFormRequest';
        $step2->form_view = 'promos.form-controls';
        $step2->approval_to_type = self::APPROVAL_MATRIX[2]['approval_to_type'];
        $step2->approval_to = self::APPROVAL_MATRIX[2]['approval_to'];
        $step2->save();

        $signoffPromoPricing = Bouncer::ability()->where('name', 'signoff.product.promo.finance')->first();
        $step2->abilities()->attach($signoffPromoPricing);
    }
}
