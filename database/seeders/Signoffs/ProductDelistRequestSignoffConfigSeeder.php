<?php

namespace Database\Seeders\Signoffs;

use App\Models\SignoffConfig;
use App\Models\SignoffConfigStep;
use Bouncer;
use Illuminate\Database\Seeder;

class ProductDelistRequestSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        1 => [
            'approval_to_type' => 'user',
            'approval_to' => 'brand.purchasingSpecialist',
        ],
        2 => [
            'approval_to_type' => 'user',
            'approval_to' => 'brand.vendorRelationsSpecialist',
        ],
        3 => [
            'approval_to_type' => 'role',
            'approval_to' => 'finance',
        ],
    ];

    public function run()
    {
        $signoffConfig = SignoffConfig::create([
            'model' => 'App\Models\ProductDelistRequest',
            'show_route' => 'productdelists.show',
        ]);

        $step1 = new SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Purchasing';
        $step1->form_request = 'App\Http\Requests\ProductDelistRequests\ProductDelistRequestFormRequest';
        $step1->form_view = 'productdelists.form-controls';
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffRequest = Bouncer::ability()->where('name', 'signoff.product.purchasing')->first();
        $step1->abilities()->attach($signoffRequest);

        $step2 = new SignoffConfigStep;
        $step2->signoffConfig()->associate($signoffConfig);
        $step2->step = 2;
        $step2->name = 'Vendor Relations';
        $step2->form_request = 'App\Http\Requests\ProductDelistRequests\ProductDelistRequestFormRequest';
        $step2->form_view = 'productdelists.form-controls';
        $step2->approval_to_type = self::APPROVAL_MATRIX[2]['approval_to_type'];
        $step2->approval_to = self::APPROVAL_MATRIX[2]['approval_to'];
        $step2->save();

        $signoffRequest = Bouncer::ability()->where('name', 'signoff.product.vendorrelations')->first();
        $step2->abilities()->attach($signoffRequest);

        $step3 = new SignoffConfigStep;
        $step3->signoffConfig()->associate($signoffConfig);
        $step3->step = 3;
        $step3->name = 'Finance';
        $step3->form_request = 'App\Http\Requests\ProductDelistRequests\ProductDelistRequestFormRequest';
        $step3->form_view = 'productdelists.form-controls';
        $step3->approval_to_type = self::APPROVAL_MATRIX[3]['approval_to_type'];
        $step3->approval_to = self::APPROVAL_MATRIX[3]['approval_to'];
        $step3->save();

        $signoffRequest = Bouncer::ability()->where('name', 'signoff.product.finance')->first();
        $step2->abilities()->attach($signoffRequest);
    }
}
