<?php

use Illuminate\Support\Facades\DB;
use RicorocksDigitalAgency\Morpher\Morph;

class AddFinalApprovalToColumn extends Morph
{
    protected static $migration = '2022_06_13_233534_add_final_approval_to_column';

    protected array $finalApprovalMatrix;

    public function prepare()
    {
        $this->finalApprovalMatrix = [
            'App\Models\Brand' => [
                'listing' => [
                    'emails' => [
                        'reporting@puritylife.com',
                        'pricing@puritylife.com',
                        'accountspayable@puritylife.com',
                        'nathan.wright@puritylife.com',
                        'Amanda.coish@puritylife.com',
                        'jennifer.leclaire@puritylife.com',
                    ],
                    'roles' => [],
                    'users' => ['purchasingSpecialist'],
                ],
                'discontinuation' => [
                    'emails' => ['reporting@puritylife.com'],
                    'roles' => [],
                    'users' => ['purchasingSpecialist'],
                ],
            ],
            'App\Models\BrandDiscoRequest' => [
                'discontinuation' => [
                    'emails' => ['reporting@puritylife.com'],
                    'roles' => [],
                    'users' => ['brand.purchasingSpecialist'],
                ],
            ],
            'App\Models\Product' => [
                'listing' => [
                    'users' => [
                        'brand.purchasingSpecialist',
                    ],
                ],
                'relisting' => [
                    'users' => [
                        'brand.purchasingSpecialist',
                    ],
                ],
                'price change' => [
                    'emails' => [
                        'pricing@puritylife.com',
                    ],
                ],
            ],
            'App\Models\ProductDelistRequest' => [
                'discontinuation' => [
                    'users' => [
                        'product.brand.purchasingSpecialist',
                    ],
                    'emails' => [
                        'marketing@puritylife.com',
                    ],
                ],
            ],
            'App\Models\Promo' => [
                'change' => [
                    'users' => [
                        'purchasingSpecialist',
                    ],
                ],
                'listing' => [
                    'users' => [
                        'purchasingSpecialist',
                    ],
                ],
            ],
        ];
    }

    public function run()
    {
        $this->console->info("\n * MORPH: Adding final approval to column to signoff_config table");
        foreach ($this->finalApprovalMatrix as $signoffConfig => $config) {
            DB::table('signoff_config')
                ->where('signoff_config.model', '=', $signoffConfig)
                ->update(['final_approval_to' => $config]);
        }
    }
}
