<?php

namespace Database\Seeders\Signoffs;

use App\Models\SignoffConfig;
use App\Models\SignoffConfigStep;
use Bouncer;
use Illuminate\Database\Seeder;

class MarketingAgreementSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        1 => [
            'approval_to_type' => 'role',
            'approval_to' => 'sales-manager',
        ],
        2 => [
            'approval_to_type' => 'role',
            'approval_to' => 'maf-accounting',
        ],
    ];

    public function run()
    {
        $signoffConfig = SignoffConfig::create([
            'model' => 'App\Models\MarketingAgreement',
            'show_route' => 'marketingagreements.show',
        ]);

        $step1 = new SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Sales Management';
        $step1->form_request = 'App\Http\Requests\MarketingAgreements\MarketingAgreementFormRequest';
        $step1->form_view = 'marketingagreements.form-controls';
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffMAF = Bouncer::ability()->where('name', 'signoff.maf')->first();
        $step1->abilities()->attach($signoffMAF);

        $step2 = new SignoffConfigStep;
        $step2->signoffConfig()->associate($signoffConfig);
        $step2->step = 2;
        $step2->name = 'Accounting';
        $step2->form_request = 'App\Http\Requests\MarketingAgreements\MarketingAgreementFormRequest';
        $step2->form_view = 'marketingagreements.form-controls';
        $step2->approval_to_type = self::APPROVAL_MATRIX[2]['approval_to_type'];
        $step2->approval_to = self::APPROVAL_MATRIX[2]['approval_to'];
        $step2->save();

        $accountingMAF = Bouncer::ability()->where('name', 'signoff.maf.accounting')->first();
        $step2->abilities()->attach($accountingMAF);
    }
}
