<?php

namespace Database\Seeders\Development;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // To prevent errors below when assigning vendor_id 1 to users
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@puritylife.com',
            'password' => bcrypt('super'),
            'api_token' => Str::random(60),
        ]);
        $superAdmin->assign('super-admin');

        $admin = User::create([
            'name' => 'Admin Admin',
            'email' => 'admin@puritylife.com',
            'password' => bcrypt('admin'),
        ]);
        $admin->assign('admin');

        $user = User::create([
            'name' => 'Normal User',
            'email' => 'user@puritylife.com',
            'password' => bcrypt('user'),
        ]);

        $purchasingSpecialist = User::create([
            'name' => 'Purchasing Specialist',
            'email' => 'purchasing@puritylife.com',
            'password' => bcrypt('purchasing'),
        ]);
        $purchasingSpecialist->assign('purchasing-specialist');

        $vendorRelationsSpecialist = User::create([
            'name' => 'Vendor Relations',
            'email' => 'vendor-relations@puritylife.com',
            'password' => bcrypt('vendor'),
        ]);
        $vendorRelationsSpecialist->assign('vendor-relations-specialist');

        $costingSpecialist = User::create([
            'name' => 'Costing Specialist',
            'email' => 'costing@puritylife.com',
            'password' => bcrypt('costing'),
        ]);
        $costingSpecialist->assign('costing-specialist');

        $management1 = User::create([
            'name' => 'Management I',
            'email' => 'management1@puritylife.com',
            'password' => bcrypt('management'),
        ]);
        $management1->assign('management');

        $management2 = User::create([
            'name' => 'Management II',
            'email' => 'management2@puritylife.com',
            'password' => bcrypt('management'),
        ]);
        $management2->assign('management');

        $vendorAdmin = User::create([
            'name' => 'Vendor Admin',
            'email' => 'vendor-admin@puritylife.com',
            'password' => bcrypt('vendor'),
            'vendor_id' => 43,
        ]);
        $vendorAdmin->assign('vendor');
        $vendorAdmin->assign('vendor-admin');
        $vendorAdmin->assign('vendor-finance');

        $vendorUser = User::create([
            'name' => 'Vendor User',
            'email' => 'vendor@puritylife.com',
            'password' => bcrypt('vendor'),
            'vendor_id' => 43,
        ]);
        $vendorUser->assign('vendor');

        $brokerAdmin = User::create([
            'name' => 'Broker Admin',
            'email' => 'broker-admin@puritylife.com',
            'password' => bcrypt('broker'),
            'broker_id' => 19,
        ]);
        $brokerAdmin->assign('broker');
        $brokerAdmin->assign('vendor-admin');
        $brokerAdmin->assign('vendor-finance');

        $brokerUser = User::create([
            'name' => 'Broker User',
            'email' => 'broker@puritylife.com',
            'password' => bcrypt('broker'),
            'broker_id' => 19,
        ]);
        $brokerUser->assign('broker');

        $productSignoffs1 = User::create([
            'name' => 'Product Signoffs I',
            'email' => 'products1@puritylife.com',
            'password' => bcrypt('products'),
        ]);
        $productSignoffs1->assign('vendor-relations-specialist');
        $productSignoffs1->assign('quality-control-specialist');
        $productSignoffs1->assign('costing-specialist');
        $productSignoffs1->assign('management');

        $productSignoffs2 = User::create([
            'name' => 'Product Signoffs II',
            'email' => 'products2@puritylife.com',
            'password' => bcrypt('products'),
        ]);
        $productSignoffs2->assign('vendor-relations-specialist');
        $productSignoffs2->assign('quality-control-specialist');
        $productSignoffs2->assign('costing-specialist');
        $productSignoffs2->assign('management');

        // $qcSpecialist = User::create([
        //     'name' => 'Quality Control Specialist',
        //     'email' => 'qc@puritylife.com',
        //     'password' => bcrypt('qc'),
        // ]);
        // $qcSpecialist->assign('quality-control-specialist');

        $financeManager = User::create([
            'name' => 'Finance Manager',
            'email' => 'finance@puritylife.com',
            'password' => bcrypt('finance'),
        ]);
        $financeManager->assign('finance-manager');

        $salesManager = User::create([
            'name' => 'Sales Manager',
            'email' => 'salesmanager@puritylife.com',
            'password' => bcrypt('sales'),
        ]);
        $salesManager->assign('sales-manager');

        $salesRep = User::create([
            'name' => 'Sales Rep',
            'email' => 'sales@puritylife.com',
            'password' => bcrypt('sales'),
        ]);
        $salesRep->assign('sales-rep');

        $broker2 = User::create([
            'name' => 'Tim O\'Brien',
            'email' => 'tobrien@acosta.com',
            'password' => bcrypt('tim'),
            'broker_id' => 10,
        ]);
        $broker2->assign('broker');
        $broker2->assign('vendor-admin');
        $broker2->assign('vendor-finance');

        $broker4 = User::create([
            'name' => 'Jason Spring',
            'email' => 'jason@ghbd.ca',
            'password' => bcrypt('jason'),
            'broker_id' => 7,
        ]);
        $broker4->assign('broker');
        $broker4->assign('vendor-admin');
        $broker4->assign('vendor-finance');
    }
}
