<?php

namespace Database\Seeders\Signoffs;

use App\Models\SignoffConfig;
use App\Models\SignoffConfigStep;
use Bouncer;
use Illuminate\Database\Seeder;

class VendorSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        1 => [
            'approval_to_type' => 'role',
            'approval_to' => 'brand-administrator',
        ],
        2 => [
            'approval_to_type' => 'role',
            'approval_to' => 'costing-specialist',
        ],
    ];

    public function run()
    {
        $signoffConfig = SignoffConfig::create([
            'model' => 'App\Models\Vendor',
            'show_route' => 'vendors.show',
        ]);

        $step1 = new SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Vendor Relations';
        $step1->form_view = 'vendors.form';
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffBrand = Bouncer::ability()->where('name', 'signoff.brand')->first();
        $step1->abilities()->attach($signoffBrand);

        $step2 = new \App\Models\SignoffConfigStep;
        $step2->signoffConfig()->associate($signoffConfig);
        $step2->step = 2;
        $step2->name = 'Webseries Upload';
        $step2->form_view = 'vendors.form';
        $step2->approval_to_type = self::APPROVAL_MATRIX[2]['approval_to_type'];
        $step2->approval_to = self::APPROVAL_MATRIX[2]['approval_to'];
        $step2->save();

        $signoffWebseries = Bouncer::ability()->where('name', 'signoff.webseries')->first();
        $step2->abilities()->attach($signoffWebseries);
    }
}
