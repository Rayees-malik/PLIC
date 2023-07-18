<?php

namespace Database\Seeders\Signoffs;

use Bouncer;
use Illuminate\Database\Seeder;

class PricingAdjustmentSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        1 => [
            'approval_to_type' => 'role',
            'approval_to' => 'pricing',
        ],
        2 => [
            'approval_to_type' => 'role',
            'approval_to' => 'costing-specialist',
        ],
    ];

    public function run()
    {
        $signoffConfig = \App\Models\SignoffConfig::create([
            'model' => 'App\Models\PricingAdjustment',
            'show_route' => 'pricingadjustments.show',
        ]);

        $step1 = new \App\Models\SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Pricing';
        $step1->form_request = 'App\Http\Requests\PricingAdjustments\PricingAdjustmentFormRequest';
        $step1->form_view = 'pricingadjustments.form-controls';
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffPAFPricing = Bouncer::ability()->where('name', 'signoff.paf.pricing')->first();
        $step1->abilities()->attach($signoffPAFPricing);

        $step2 = new \App\Models\SignoffConfigStep;
        $step2->signoffConfig()->associate($signoffConfig);
        $step2->step = 2;
        $step2->name = 'Finance';
        $step2->form_request = 'App\Http\Requests\PricingAdjustments\PricingAdjustmentFormRequest';
        $step2->form_view = 'pricingadjustments.form-controls';
        $step2->approval_to_type = self::APPROVAL_MATRIX[2]['approval_to_type'];
        $step2->approval_to = self::APPROVAL_MATRIX[2]['approval_to'];
        $step2->save();

        $signoffPAFFinance = Bouncer::ability()->where('name', 'signoff.paf.finance')->first();
        $step2->abilities()->attach($signoffPAFFinance);
    }
}
