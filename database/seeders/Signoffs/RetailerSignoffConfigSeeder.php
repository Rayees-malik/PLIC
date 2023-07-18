<?php

namespace Database\Seeders\Signoffs;

use App\Models\SignoffConfig;
use App\Models\SignoffConfigStep;
use Bouncer;
use Illuminate\Database\Seeder;

class RetailerSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        1 => [
            'approval_to_type' => null,
            'approval_to' => null,
        ],
    ];

    public function run()
    {
        $signoffConfig = SignoffConfig::create([
            'model' => 'App\Models\Retailer',
            'show_route' => 'retailers.show',
        ]);

        $step1 = new SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Account Manager';
        $step1->form_view = 'retailers.form';
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffRetailer = Bouncer::ability()->where('name', 'retailer.account-manager')->first();
        $step1->abilities()->attach($signoffRetailer);
    }
}
