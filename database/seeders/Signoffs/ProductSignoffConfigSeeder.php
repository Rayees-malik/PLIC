<?php

namespace Database\Seeders\Signoffs;

use App\Models\SignoffConfig;
use App\Models\SignoffConfigStep;
use Bouncer;
use Illuminate\Database\Seeder;

class ProductSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        1 => [
            'approval_to_type' => 'user',
            'approval_to' => 'brand.vendorRelationsSpecialist',
        ],
        2 => [
            'approval_to_type' => null,
            'approval_to' => null,
        ],
        3 => [
            'approval_to_type' => 'role',
            'approval_to' => 'finance',
        ],
        4 => [
            'approval_to_type' => 'role',
            'approval_to' => 'management',
        ],
        5 => [
            'approval_to_type' => 'role',
            'approval_to' => 'finance',
        ],
    ];

    public function run()
    {
        $signoffConfig = SignoffConfig::create([
            'model' => 'App\Models\Product',
            'show_route' => 'products.show',
        ]);

        $step1 = new SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Vendor Relations';
        $step1->form_view = 'products.form';
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffVendorRelations = Bouncer::ability()->where('name', 'signoff.product.vendorrelations')->first();
        $step1->abilities()->attach($signoffVendorRelations);

        $step2 = new SignoffConfigStep;
        $step2->signoffConfig()->associate($signoffConfig);
        $step2->step = 2;
        $step2->name = 'QC';
        $step2->form_view = 'products.form';
        $step2->approval_to_type = self::APPROVAL_MATRIX[2]['approval_to_type'];
        $step2->approval_to = self::APPROVAL_MATRIX[2]['approval_to'];
        $step2->save();

        $signoffQC = Bouncer::ability()->where('name', 'signoff.product.qc')->first();
        $step2->abilities()->attach($signoffQC);

        $step3 = new SignoffConfigStep;
        $step3->signoffConfig()->associate($signoffConfig);
        $step3->step = 3;
        $step3->name = 'Finance';
        $step3->form_view = 'products.form';
        $step3->approval_to_type = self::APPROVAL_MATRIX[3]['approval_to_type'];
        $step3->approval_to = self::APPROVAL_MATRIX[3]['approval_to'];
        $step3->save();

        $signoffFinance = Bouncer::ability()->where('name', 'signoff.product.finance')->first();
        $step3->abilities()->attach($signoffFinance);

        $step4 = new SignoffConfigStep;
        $step4->signoffConfig()->associate($signoffConfig);
        $step4->step = 4;
        $step4->name = 'Management';
        $step4->form_view = 'products.form';
        $step4->signoffs_required = 2;
        $step4->approval_to_type = self::APPROVAL_MATRIX[4]['approval_to_type'];
        $step4->approval_to = self::APPROVAL_MATRIX[4]['approval_to'];
        $step4->save();

        $signoffManagement = Bouncer::ability()->where('name', 'signoff.product.management')->first();
        $step4->abilities()->attach($signoffManagement);

        $step5 = new SignoffConfigStep;
        $step5->signoffConfig()->associate($signoffConfig);
        $step5->step = 5;
        $step5->name = 'Webseries Upload';
        $step5->form_request = 'App\Http\Requests\EmptyFormRequest';
        $step5->form_view = 'products.signoffs.webseries';
        $step5->approval_to_type = self::APPROVAL_MATRIX[5]['approval_to_type'];
        $step5->approval_to = self::APPROVAL_MATRIX[5]['approval_to'];
        $step5->save();

        $signoffWebseries = Bouncer::ability()->where('name', 'signoff.webseries')->first();
        $step5->abilities()->attach($signoffWebseries);
    }
}
