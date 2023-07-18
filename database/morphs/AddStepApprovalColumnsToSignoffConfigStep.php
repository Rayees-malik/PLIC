<?php

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use RicorocksDigitalAgency\Morpher\Morph;

class AddStepApprovalColumnsToSignoffConfigStep extends Morph
{
    protected static $migration = '2022_06_13_222031_add_step_approval_columns_to_signoff_config_step';

    protected array $approvalMatrix;

    protected Datetime $now;

    public function prepare()
    {
        $this->approvalMatrix = [
            'App\Models\Brand' => [
                'Vendor Relations' => [
                    'approval_to_type' => 'user',
                    'approval_to' => 'vendorRelationsSpecialist',
                ],
                'Webseries Upload' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
            ],
            'App\Models\Product' => [
                'Vendor Relations' => [
                    'approval_to_type' => 'user',
                    'approval_to' => 'brand.vendorRelationsSpecialist',
                ],
                'QC' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'quality-control-specialist',
                ],
                'Finance' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
                'Management' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'management',
                ],
                'Webseries Upload' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
            ],
            'App\Models\Promo' => [
                'Vendor Relations' => [
                    'approval_to_type' => 'user',
                    'approval_to' => 'brand.vendorRelationsSpecialist',
                ],
                'Finance' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
            ],
            'App\Models\Retailer' => [
                'Account Manager' => [
                    'approval_to_type' => null,
                    'approval_to' => null,
                ],
            ],
            'App\Models\Vendor' => [
                'Vendor Relations' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'brand-administrator',
                ],
                'Webseries Upload' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
            ],
            'App\Models\PricingAdjustment' => [
                'Pricing' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'pricing',
                ],
                'Finance' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
            ],
            'App\Models\MarketingAgreement' => [
                'Sales Management' => [
                    'approval_to_type' => 'user',
                    'approval_to' => 'sendTo',
                ],
                'Accounting' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'maf-accountant',
                ],
            ],
            'App\Models\InventoryRemoval' => [
                'Management_1' => [
                    'step' => 1,
                    'approval_to_type' => 'role',
                    'approval_to' => 'inventory-management-high',
                ],
                'Management_2' => [
                    'step' => 2,
                    'approval_to_type' => 'role',
                    'approval_to' => 'inventory-management-low',
                ],
                'Warehouse QC' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'warehouse-qc',
                ],
                'Finance' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
            ],
            'App\Models\BrandDiscoRequest' => [
                'Management' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'brand-disco-signoff',
                ],
                'Finance' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
            ],
            'App\Models\ProductDelistRequest' => [
                'Purchasing' => [
                    'approval_to_type' => 'user',
                    'approval_to' => 'product.brand.purchasingSpecialist',
                ],
                'Vendor Relations' => [
                    'approval_to_type' => 'user',
                    'approval_to' => 'product.brand.vendorRelationsSpecialist',
                ],
                'Finance' => [
                    'approval_to_type' => 'role',
                    'approval_to' => 'costing-specialist',
                ],
            ],
        ];

        $this->now = now();
    }

    public function run()
    {
        $this->console->info("\n * MORPH: Dropping existing email unsubscriptions");
        DB::table('users')->update([
            'unsubscriptions' => null,
            'updated_at' => $this->now,
        ]);

        $this->console->info(' * MORPH: Changing inventory management role');
        DB::table('roles')->where('name', '=', 'inventory-management')->update([
            'name' => 'inventory-management-high',
            'title' => 'Inventory Management High Value',
            'description' => 'Allow signoff on high-value inventory removals',
        ]);

        $role = Role::where('name', '=', 'inventory-management-high')->first();

        if ($role) {
            $newRole = $role->replicate();
            $newRole->name = 'inventory-management-low';
            $newRole->title = 'Inventory Management Low Value';
            $newRole->description = 'Allow signoff on low-value inventory removals';
            $newRole->push();

            foreach ($role->abilities as $ability) {
                $newRole->abilities()->attach($ability);
            }
        }

        $this->console->info(' * MORPH: Inserting Signoff step approval notification config');
        foreach ($this->approvalMatrix as $signoffConfig => $steps) {
            foreach ($steps as $stepName => $approvalData) {
                DB::table('signoff_config_steps')
                    ->join(
                        'signoff_config',
                        'signoff_config_steps.signoff_config_id',
                        '=',
                        'signoff_config.id'
                    )
                    ->where('signoff_config.model', '=', $signoffConfig)
                    ->when(isset($approvalData['step']), function ($query) use ($approvalData) {
                        return $query->where('signoff_config_steps.step', '=', $approvalData['step']);
                    }, function ($query) use ($stepName) {
                        return $query->where('signoff_config_steps.name', '=', $stepName);
                    })
                    ->select(
                        'signoff_config_steps.*',
                        'signoff_config.id as signoff_config_id',
                        'signoff_config.model'
                    )
                    ->update(array_merge($approvalData, ['signoff_config_steps.updated_at' => $this->now]));
            }
        }
    }
}
