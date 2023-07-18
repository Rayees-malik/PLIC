<?php

namespace Database\Seeders\Signoffs;

use App\Models\SignoffConfig;
use App\Models\SignoffConfigStep;
use Bouncer;
use Illuminate\Database\Seeder;

class InventoryRemovalSignoffConfigSeeder extends Seeder
{
    private const APPROVAL_MATRIX = [
        // management
        1 => [
            'approval_to_type' => 'role',
            'approval_to' => 'inventory-management-high',
        ],
        // management
        2 => [
            'approval_to_type' => 'role',
            'approval_to' => 'inventory-management-low',
        ],
        3 => [
            'approval_to_type' => 'role',
            'approval_to' => 'warehouse-qc',
        ],
        4 => [
            'approval_to_type' => 'role',
            'approval_to' => 'costing-specialist',
        ],
    ];

    public function run()
    {
        $signoffConfig = SignoffConfig::create([
            'model' => 'App\Models\InventoryRemoval',
            'show_route' => 'inventoryremovals.show',
        ]);

        $step1 = new SignoffConfigStep;
        $step1->signoffConfig()->associate($signoffConfig);
        $step1->step = 1;
        $step1->name = 'Management';
        $step1->form_request = 'App\Http\Requests\InventoryRemovals\InventoryRemovalFormRequest';
        $step1->form_view = 'inventoryremovals.form-controls';
        $step1->signoffs_required = 2;
        $step1->approval_to_type = self::APPROVAL_MATRIX[1]['approval_to_type'];
        $step1->approval_to = self::APPROVAL_MATRIX[1]['approval_to'];
        $step1->save();

        $signoffManagement = Bouncer::ability()->where('name', 'signoff.inventory-removals.management')->first();
        $step1->abilities()->attach($signoffManagement);

        $step2 = new SignoffConfigStep;
        $step2->signoffConfig()->associate($signoffConfig);
        $step2->step = 2;
        $step2->name = 'Management';
        $step2->form_request = 'App\Http\Requests\InventoryRemovals\InventoryRemovalFormRequest';
        $step2->form_view = 'inventoryremovals.form-controls';
        $step2->approval_to_type = self::APPROVAL_MATRIX[2]['approval_to_type'];
        $step2->approval_to = self::APPROVAL_MATRIX[2]['approval_to'];
        $step2->save();

        $step2->abilities()->attach($signoffManagement);

        $step3 = new SignoffConfigStep;
        $step3->signoffConfig()->associate($signoffConfig);
        $step3->step = 3;
        $step3->name = 'Warehouse QC';
        $step3->form_request = 'App\Http\Requests\InventoryRemovals\InventoryRemovalFormRequest';
        $step3->form_view = 'inventoryremovals.form-controls';
        $step3->approval_to_type = self::APPROVAL_MATRIX[3]['approval_to_type'];
        $step3->approval_to = self::APPROVAL_MATRIX[3]['approval_to'];
        $step3->save();

        $signoffQC = Bouncer::ability()->where('name', 'signoff.inventory-removals.qc')->first();
        $step3->abilities()->attach($signoffQC);

        $step4 = new SignoffConfigStep;
        $step4->signoffConfig()->associate($signoffConfig);
        $step4->step = 4;
        $step4->name = 'Finance';
        $step4->form_request = 'App\Http\Requests\InventoryRemovals\InventoryRemovalFormRequest';
        $step4->form_view = 'inventoryremovals.form-controls';
        $step4->approval_to_type = self::APPROVAL_MATRIX[4]['approval_to_type'];
        $step4->approval_to = self::APPROVAL_MATRIX[4]['approval_to'];
        $step4->save();

        $signoffFinance = Bouncer::ability()->where('name', 'signoff.inventory-removals.finance')->first();
        $step4->abilities()->attach($signoffFinance);
    }
}
