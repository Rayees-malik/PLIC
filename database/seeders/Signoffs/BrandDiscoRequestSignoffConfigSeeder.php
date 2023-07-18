<?php

namespace Database\Seeders\Signoffs;

use App\Models\SignoffConfig;
use App\Models\SignoffConfigStep;
use Bouncer;
use Illuminate\Database\Seeder;

class BrandDiscoRequestSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        // management
        1 => [
            'approval_to_type' => null,
            'approval_to' => null,
        ],
        2 => [
            'approval_to_type' => 'role',
            'approval_to' => 'costing-specialist',
        ],
    ];

    public function run()
    {
        $signoffConfig = SignoffConfig::create([
            'model' => 'App\Models\BrandDiscoRequest',
            'show_route' => 'branddiscos.show',
        ]);

        $step1 = new SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Management';
        $step1->form_request = 'App\Http\Requests\BrandDiscoRequests\BrandDiscoRequestFormRequest';
        $step1->form_view = 'branddiscos.form-controls';
        $step1->signoffs_required = 4;
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffRequest = Bouncer::ability()->where('name', 'signoff.brand.disco')->first();
        $step1->abilities()->attach($signoffRequest);

        $step2 = new SignoffConfigStep;
        $step2->signoffConfig()->associate($signoffConfig);
        $step2->step = 2;
        $step2->name = 'Finance';
        $step2->form_request = 'App\Http\Requests\BrandDiscoRequests\BrandDiscoRequestFormRequest';
        $step2->form_view = 'branddiscos.form-controls';
        $step2->approval_to_type = self::APPROVAL_MATRIX[2]['approval_to_type'];
        $step2->approval_to = self::APPROVAL_MATRIX[2]['approval_to'];
        $step2->save();

        $signoffRequest = Bouncer::ability()->where('name', 'signoff.brand.disco.finance')->first();
        $step2->abilities()->attach($signoffRequest);
    }
}
