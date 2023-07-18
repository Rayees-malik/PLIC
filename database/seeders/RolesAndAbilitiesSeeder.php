<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\InventoryRemoval;
use App\Models\MarketingAgreement;
use App\Models\PricingAdjustment;
use App\Models\Product;
use App\Models\Promo;
use App\Models\PromoPeriod;
use App\Models\QualityControlRecord;
use App\Models\Retailer;
use App\Models\Vendor;
use App\User;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\BouncerFacade as Bouncer;

class RolesAndAbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;

        $this->insertAbilities($timestamp);
        $this->insertRoles($timestamp);

        Bouncer::allow('super-admin')->everything();

        Bouncer::allow('admin')->everything();
        Bouncer::forbid('admin')->to('admin.edit');

        Bouncer::allow('purchasing-specialist')->toManage([Product::class, InventoryRemoval::class]);
        Bouncer::allow('purchasing-specialist')->to(['vendor.access-all', 'signoff', 'signoff.product.purchasing', 'promo.view.discos']);

        Bouncer::allow('pricing')->toManage([User::class, Product::class, Promo::class, PromoPeriod::class]);
        Bouncer::allow('pricing')->to(['vendor.access-all', 'signoff.paf.pricing', 'admin.menu', 'promo.view.discos']);

        Bouncer::allow('vendor-relations-specialist')->toManage([Product::class, Vendor::class, Brand::class, Promo::class, User::class]);
        Bouncer::allow('vendor-relations-specialist')->to(['admin.menu', 'brand.disco.request', 'download.user-manual', 'exports.listingforms', 'exports.viewmenu', 'promo.monthly.edit', 'promo.view.discos', 'signoff.brand', 'signoff.product.promo.vendorrelations', 'signoff.product.vendorrelations', 'signoff.vendor.disco', 'signoff.vendor', 'signoff', 'vendor.access-all']);

        Bouncer::allow('brand-administrator')->to(['brand.administrator', 'download.user-manual']);

        Bouncer::allow('quality-control-specialist')->toManage([Product::class, User::class]);
        Bouncer::allow('quality-control-specialist')->to(['vendor.access-all', 'signoff', 'signoff.product.qc', 'admin.menu']);

        Bouncer::allow('sales-manager')->toManage([InventoryRemoval::class, MarketingAgreement::class, PricingAdjustment::class, Product::class, Retailer::class]);
        Bouncer::allow('sales-manager')->to(['admin.menu', 'exports.listingforms', 'exports.viewmenu', 'promo.view.discos', 'retailer.account-manager', 'signoff.maf', 'signoff.retailer.promo', 'signoff', 'vendor.access-all']);

        Bouncer::allow('marketing')->to('view', Product::class);
        Bouncer::allow('marketing')->to(['vendor.access-all', 'exports.viewmenu', 'product.view.submissions', 'promo.view.discos']);

        Bouncer::allow('maf-accountant')->to('signoff.maf.accounting');

        Bouncer::allow('sales-rep')->to('view', Product::class);
        Bouncer::allow('sales-rep')->to(['create', 'edit', 'view'], [MarketingAgreement::class, PricingAdjustment::class, InventoryRemoval::class, Retailer::class]);
        Bouncer::allow('sales-rep')->to(['admin.menu', 'exports.listingforms', 'exports.viewmenu', 'promo.view.discos', 'retailer.account-manager', 'signoff.retailer.promo', 'signoff', 'vendor.access-all']);

        Bouncer::allow('customer-care')->to(['vendor.access-all', 'promo.view.discos']);
        Bouncer::allow('customer-care')->to('view', Product::class);

        Bouncer::allow('vendor-finance')->to('finance.vendor');

        Bouncer::allow('finance')->to(['finance.vendor.all', 'promo.view.discos', 'vendor.access-all']);

        Bouncer::allow('finance-manager')->to(['finance.delete-media', 'finance.force-upload', 'finance.vendor.all', 'promo.view.discos', 'user.create.finance', 'vendor.access-all']);

        Bouncer::allow('management')->to(['signoff', 'signoff.product.management', 'vendor.access-all']);

        Bouncer::allow('inventory-management-low')->to(['signoff', 'signoff.inventory-removals.management', 'vendor.access-all']);
        Bouncer::allow('inventory-management-high')->to(['signoff', 'signoff.inventory-removals.management', 'vendor.access-all']);

        Bouncer::allow('brand-disco-signoff')->to(['signoff', 'signoff.brand.disco', 'vendor.access-all']);

        Bouncer::allow('costing-specialist')->toManage([Brand::class, Product::class, Promo::class, PromoPeriod::class, Vendor::class]);
        Bouncer::allow('costing-specialist')->to(['brand.edit.number', 'exports.listingforms', 'exports.viewmenu', 'product.costing', 'product.edit.stockid', 'promo.monthly.edit', 'promo.update.discos', 'signoff.brand.disco.finance', 'signoff.inventory-removals.finance', 'signoff.paf.finance', 'signoff.product.finance', 'signoff.product.promo.finance', 'signoff.webseries', 'signoff', 'vendor.access-all', 'vendor.margin-update']);

        Bouncer::allow('vendor')->to(['create', 'edit', 'view'], [Vendor::class, Brand::class, Product::class, Promo::class]);
        Bouncer::allow('vendor')->to(['vendor', 'user.assign.vendor', 'download.user-manual']);

        Bouncer::allow('vendor-admin')->toManage(User::class);
        Bouncer::allow('vendor-admin')->to(['create', 'edit', 'view'], [Vendor::class, Brand::class, Product::class, Promo::class]);
        Bouncer::allow('vendor-admin')->to(['vendor', 'user.assign.vendor', 'download.user-manual']);

        Bouncer::allow('vendor-view-only')->to('view', [Product::class, Brand::class, Vendor::class, Promo::class]);
        Bouncer::allow('vendor-view-only')->to(['vendor', 'user.assign.vendor']);

        Bouncer::allow('broker-admin')->toManage(User::class);
        Bouncer::allow('broker-admin')->to('view', [Product::class, Brand::class, Vendor::class, Promo::class]);
        Bouncer::allow('broker-admin')->to(['vendor', 'user.assign.broker', 'vendor.user.create', 'download.user-manual']);

        Bouncer::allow('broker')->to('view', [Product::class, Brand::class, Vendor::class, Promo::class]);
        Bouncer::allow('broker')->to(['vendor', 'user.assign.broker', 'download.user-manual']);

        Bouncer::allow('broker-view-only')->to('view', [Product::class, Brand::class, Vendor::class, Promo::class]);
        Bouncer::allow('broker-view-only')->to(['vendor', 'user.assign.broker']);

        Bouncer::allow('full-view-access')->to('vendor.access-all');
        Bouncer::allow('full-view-access')->to('view', Product::class);

        Bouncer::allow('warehouse-qc')->to('signoff.inventory-removals.qc');

        Bouncer::allow('warehouse-shipping')->to('signoff.inventory-removals.shipping');

        Bouncer::allow('warehouse-01')->to('warehouse.01');
        Bouncer::allow('warehouse-04')->to('warehouse.04');
        Bouncer::allow('warehouse-08')->to('warehouse.08');
        Bouncer::allow('warehouse-09')->to('warehouse.09');

        Bouncer::allow('qc-technician-01')->to('qc.menu');
        Bouncer::allow('qc-technician-01')->to('qc-technician-01');
        Bouncer::allow('qc-technician-01')->to(['create', 'update'], QualityControlRecord::class);
        Bouncer::allow('qc-technician-01')->toOwn(QualityControlRecord::class);

        Bouncer::allow('qc-technician-04')->to('qc.menu');
        Bouncer::allow('qc-technician-04')->to('qc-technician-04');
        Bouncer::allow('qc-technician-04')->to(['create', 'update'], QualityControlRecord::class);
        Bouncer::allow('qc-technician-04')->toOwn(QualityControlRecord::class);

        Bouncer::allow('qc-technician-08')->to('qc.menu');
        Bouncer::allow('qc-technician-08')->to('qc-technician-08');
        Bouncer::allow('qc-technician-08')->to(['create', 'update'], QualityControlRecord::class);
        Bouncer::allow('qc-technician-08')->toOwn(QualityControlRecord::class);

        Bouncer::allow('qc-technician-09')->to('qc.menu');
        Bouncer::allow('qc-technician-09')->to('qc-technician-09');
        Bouncer::allow('qc-technician-09')->to(['create', 'update'], QualityControlRecord::class);
        Bouncer::allow('qc-technician-09')->toOwn(QualityControlRecord::class);

        Bouncer::allow('qc-admin')->to('qc.view-all-qc-records');
        Bouncer::allow('qc-admin')->to('qc.menu');
        Bouncer::allow('qc-admin')->toManage(QualityControlRecord::class);
        Bouncer::allow('qc-admin')->toOwn(QualityControlRecord::class);
    }

    private function insertAbilities(Datetime $timestamp)
    {
        $abilities = [
            [
                'name' => '*',
                'title' => 'All Abilities',
                'description' => null,
                'entity_type' => '*',
                'category' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage products',
                'entity_type' => 'App\Models\Product',
                'category' => 'Products',
                'description' => 'Manage products',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage inventory removals',
                'entity_type' => 'App\Models\InventoryRemoval',
                'category' => 'Inventory Removals',
                'description' => 'Manage inventory removals',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage users',
                'entity_type' => 'App\User',
                'category' => 'Admin',
                'description' => 'Manage users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage promos',
                'entity_type' => 'App\Models\Promo',
                'category' => 'Promos',
                'description' => 'Manage promos',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage promo periods',
                'entity_type' => 'App\Models\PromoPeriod',
                'category' => 'Promos',
                'description' => 'Manage promo periods',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage vendors',
                'entity_type' => 'App\Models\Vendor',
                'category' => 'Vendors',
                'description' => 'Manage vendors',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage brands',
                'entity_type' => 'App\Models\Brand',
                'category' => 'Brands',
                'description' => 'Manage brands',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage marketing agreements',
                'entity_type' => 'App\Models\MarketingAgreement',
                'category' => 'MAFs',
                'description' => 'Manage marketing agreements',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage pricing adjustments',
                'entity_type' => 'App\Models\PricingAdjustment',
                'category' => 'PAFs',
                'description' => 'Manage pricing adjustments',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage brand disco requests',
                'entity_type' => 'App\Models\BrandDiscoRequest',
                'category' => 'Brands',
                'description' => 'Manage brand disco requests',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage retailers',
                'entity_type' => 'App\Models\Retailer',
                'category' => 'Retailers',
                'description' => 'Manage retailers',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => '*',
                'title' => 'Manage quality control records',
                'entity_type' => 'App\Models\QualityControlRecord',
                'category' => 'Quality Control',
                'description' => 'Manage quality control records',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'promo.view.discos',
                'title' => 'View Disco Promos',
                'description' => 'View promos for disco products',
                'entity_type' => null,
                'category' => 'Promos',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'promo.notify.unsubmit',
                'title' => 'Receive Promo Unsubmit Notifications',
                'description' => 'Receive Promo Unsubmit Notifications',
                'entity_type' => null,
                'category' => 'Promos',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'impersonate-users',
                'title' => 'Impersonate Users',
                'entity_type' => null,
                'category' => 'Admin',
                'description' => 'Allow user to impersonate other users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'imports.glaccounts',
                'title' => 'View Customer GL Accounts Import',
                'entity_type' => null,
                'category' => 'Imports',
                'description' => 'View Customer GL Accounts Import',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'imports.viewmenu',
                'title' => 'View Imports Menu',
                'entity_type' => null,
                'category' => 'Imports',
                'description' => 'View Imports Menu',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'download.user-manual',
                'title' => 'Download User Manual',
                'entity_type' => null,
                'category' => 'Media',
                'description' => 'Download User Manual',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'exports.listingforms',
                'title' => 'Export Listing Forms',
                'entity_type' => null,
                'category' => 'Reports',
                'description' => 'Export Listing Forms',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'exports.viewmenu',
                'title' => 'View Reports Menu',
                'entity_type' => null,
                'category' => 'Reports',
                'description' => 'View Reports Menu',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create marketing agreements',
                'entity_type' => 'App\Models\MarketingAgreement',
                'category' => 'Marketing agreements',
                'description' => 'Create marketing agreements',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create pricing adjustments',
                'entity_type' => 'App\Models\PricingAdjustment',
                'category' => 'PAFs',
                'description' => 'Create pricing adjustments',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create inventory removals',
                'entity_type' => 'App\Models\InventoryRemoval',
                'category' => 'Inventory removals',
                'description' => 'Create inventory removals',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create vendors',
                'entity_type' => 'App\Models\Vendor',
                'category' => 'Vendors',
                'description' => 'Create vendors',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create brands',
                'entity_type' => 'App\Models\Brand',
                'category' => 'Brands',
                'description' => 'Create brands',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create products',
                'entity_type' => 'App\Models\Product',
                'category' => 'Products',
                'description' => 'Create products',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create promos',
                'entity_type' => 'App\Models\Promo',
                'category' => 'Promos',
                'description' => 'Create promos',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create retailers',
                'entity_type' => 'App\Models\Retailer',
                'category' => 'Retailers',
                'description' => 'Create retailers',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'create',
                'title' => 'Create quality control records',
                'entity_type' => 'App\Models\QualityControlRecord',
                'category' => 'Quality Control Records',
                'description' => 'Create quality control records',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'edit',
                'title' => 'Edit marketing agreements',
                'entity_type' => 'App\Models\MarketingAgreement',
                'category' => null,
                'description' => 'Edit marketing agreements',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'edit',
                'title' => 'Edit pricing adjustments',
                'entity_type' => 'App\Models\PricingAdjustment',
                'category' => 'PAFs',
                'description' => 'Edit pricing adjustments',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'edit',
                'title' => 'Edit inventory removals',
                'entity_type' => 'App\Models\InventoryRemoval',
                'category' => 'Inventory Removals',
                'description' => 'Edit inventory removals',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'edit',
                'title' => 'Edit vendors',
                'entity_type' => 'App\Models\Vendor',
                'category' => 'Vendors',
                'description' => 'Edit vendors',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'edit',
                'title' => 'Edit brands',
                'entity_type' => 'App\Models\Brand',
                'category' => 'Brands',
                'description' => 'Edit brands',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'edit',
                'title' => 'Edit products',
                'entity_type' => 'App\Models\Product',
                'category' => 'Products',
                'description' => 'Edit products',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'edit',
                'title' => 'Edit promos',
                'entity_type' => 'App\Models\Promo',
                'category' => 'Promos',
                'description' => 'Edit promos',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'edit',
                'title' => 'Edit retailers',
                'entity_type' => 'App\Models\Retailer',
                'category' => 'Retailers',
                'description' => 'Edit retailers',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'view',
                'title' => 'View products',
                'entity_type' => 'App\Models\Product',
                'category' => 'Products',
                'description' => 'View products',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'view',
                'title' => 'View marketing agreements',
                'entity_type' => 'App\Models\MarketingAgreement',
                'category' => 'MAFs',
                'description' => 'View marketing agreements',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'view',
                'title' => 'View pricing adjustments',
                'entity_type' => 'App\Models\PricingAdjustment',
                'category' => 'PAFs',
                'description' => 'View pricing adjustments',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'view',
                'title' => 'View inventory removals',
                'entity_type' => 'App\Models\InventoryRemoval',
                'category' => 'Inventory Removals',
                'description' => 'View inventory removals',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'view',
                'title' => 'View vendors',
                'entity_type' => 'App\Models\Vendor',
                'category' => 'Vendors',
                'description' => 'View vendors',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'view',
                'title' => 'View brands',
                'entity_type' => 'App\Models\Brand',
                'category' => 'Brands',
                'description' => 'View brands',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'view',
                'title' => 'View promos',
                'entity_type' => 'App\Models\Promo',
                'category' => 'Promos',
                'description' => 'View promos',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'view',
                'title' => 'View retailers',
                'entity_type' => 'App\Models\Retailer',
                'category' => 'Retailers',
                'description' => 'View retailers',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'promo.monthly.edit',
                'title' => 'Edit Monthly Promos',
                'entity_type' => null,
                'category' => 'Promos',
                'description' => 'Edit monthly catalogue promos',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'brand.edit.categories',
                'title' => 'Edit Brand Catalogue Categories',
                'entity_type' => null,
                'category' => 'Brands',
                'description' => 'Edit Brand Catalogue Categories',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'brand.disco.request',
                'title' => 'Request Brand Disco',
                'entity_type' => null,
                'category' => 'Brands',
                'description' => 'Submit a request to disco a brand',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'admin',
                'title' => 'Tag a Role/User as Admin',
                'entity_type' => null,
                'description' => 'Tags a role/user as admin to prevent editing',
                'category' => 'Admin',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'admin.edit',
                'title' => 'Edit Admin Accounts',
                'entity_type' => null,
                'description' => 'Edit accounts with Administrative privledges',
                'category' => 'Admin',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'lookups.edit',
                'title' => 'Edit Lookup Tables',
                'entity_type' => null,
                'description' => 'Edit the various lookup tables',
                'category' => 'Admin',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'admin.menu',
                'title' => 'View Admin Menu',
                'entity_type' => null,
                'description' => 'View the Admin header menu',
                'category' => 'Admin',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'user.roles.edit',
                'title' => 'Edit User Roles',
                'entity_type' => null,
                'description' => 'Edit the roles assigned to users',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'user.create.finance',
                'title' => 'Create Finance Users',
                'entity_type' => null,
                'description' => 'Allows adding the finance role to users',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'user.assign.vendor',
                'title' => 'Vendor User',
                'entity_type' => null,
                'description' => 'Allows vendors to be assigned.',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'user.assign.broker',
                'title' => 'Broker User',
                'entity_type' => null,
                'description' => 'Allows a broker to be assigned giving access to all of that broker\'s vendors.',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff',
                'title' => 'Signoff Tag',
                'entity_type' => null,
                'description' => 'Tags a user as having access to the Signoffs section',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.vendor',
                'title' => 'Signoff Vendors',
                'entity_type' => null,
                'description' => 'Signoff Vendor submissions/updates',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.brand',
                'title' => 'Signoff Brands',
                'entity_type' => null,
                'description' => 'Signoff Vendor submissions/updates',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.webseries',
                'title' => 'Signoff Upload to Webseries',
                'entity_type' => null,
                'description' => 'Signoff Upload to Webseries Steps',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.product.purchasing',
                'title' => 'Signoff Products - Purchasing',
                'entity_type' => null,
                'description' => 'Signoff the Purchasing step for Products',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.product.vendorrelations',
                'title' => 'Signoff Products - Vendor Relations Step',
                'entity_type' => null,
                'description' => 'Signoff the Vendor Relations step for Products',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.product.qc',
                'title' => 'Signoff Products - QC Step',
                'entity_type' => null,
                'description' => 'Signoff the QC step for Products',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.vendor.disco',
                'title' => 'Signoff Vendor Disco Requests',
                'entity_type' => null,
                'description' => 'Signoff the first step of Vendor Disco requests',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.product.promo.vendorrelations',
                'title' => 'Signoff Product Promos - Vendor Relations Step',
                'entity_type' => null,
                'description' => 'Signoff the Vendor Relations step of Product Promos',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.product.promo.finance',
                'title' => 'Signoff Product Promos - Finance Step',
                'entity_type' => null,
                'description' => 'Signoff the Finance step of Product Promos',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.maf',
                'title' => 'Signoff MAFs',
                'entity_type' => null,
                'description' => 'Signoff MAFs',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.paf.pricing',
                'title' => 'Signoff PAFs',
                'entity_type' => null,
                'description' => 'Signoff PAFs Pricing',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.paf.finance',
                'title' => 'Signoff PAFs Finance',
                'entity_type' => null,
                'description' => 'Signoff PAFs Finance',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.retailer.promo',
                'title' => 'Signoff Retailer Promos',
                'entity_type' => null,
                'description' => 'Allow signing off promos for assigned Retailers',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.product.management',
                'title' => 'Signoff Products - Management Step',
                'entity_type' => null,
                'description' => 'Signoff the Management step for Products',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.inventory-removals.management',
                'title' => 'Signoff Inventory Removals - Management Step',
                'entity_type' => null,
                'description' => 'Allow signing off management step for Inventory Removal Requests',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.inventory-removals.qc',
                'title' => 'Signoff Inventory Removals - Warehouse QC Step',
                'entity_type' => null,
                'description' => 'Allow signing off warehouse QC step for Inventory Removal Requests',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.inventory-removals.shipping',
                'title' => 'Signoff Inventory Removals - Warehouse Shipping Step',
                'entity_type' => null,
                'description' => 'Allow signing off warehouse shipping step for Inventory Removal Requests',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.inventory-removals.finance',
                'title' => 'Signoff Inventory Removals - Finance Step',
                'entity_type' => null,
                'description' => 'Allow signing off finance step for Inventory Removal Requests',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.product.finance',
                'title' => 'Signoff Products - Finance Step',
                'entity_type' => null,
                'description' => 'Signoff the Finance step for Products',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.brand.disco',
                'title' => 'Signoff Brand Disco Requests',
                'entity_type' => null,
                'description' => 'Signoff Brand Disco Requests',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.brand.disco.finance',
                'title' => 'Signoff Brand Disco Requests - Finance Step',
                'entity_type' => null,
                'description' => 'Signoff Brand Disco Requests Finance Step',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'signoff.maf.accounting',
                'title' => 'MAF Accounting',
                'entity_type' => null,
                'description' => 'Access accounting exports and signoff on MAFs',
                'category' => 'Signoffs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'finance.vendor.all',
                'title' => 'Vendor Finance (All Brands)',
                'entity_type' => null,
                'description' => 'Access Finance for all Vendors',
                'category' => 'Finance',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'finance.vendor',
                'title' => 'Vendor Finance',
                'entity_type' => null,
                'description' => 'Access finance for assigned Vendors',
                'category' => 'Finance',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'finance.force-upload',
                'title' => 'Finance - Force Upload',
                'entity_type' => null,
                'description' => 'Allow triggering the Force Upload Payments&Deductions process',
                'category' => 'Finance',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'finance.delete-media',
                'title' => 'Finance - Delete Media',
                'entity_type' => null,
                'description' => 'Allow deleting media files in Payments&Deductions',
                'category' => 'Finance',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'retailer.account-manager',
                'title' => 'Retailer Account Manager',
                'entity_type' => null,
                'description' => 'Retailer Key Account Manager',
                'category' => 'Retailers',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'vendor',
                'title' => 'Vendor Tag',
                'entity_type' => null,
                'description' => 'Tags a User as a Vendor',
                'category' => 'Vendors',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'vendor.margin-update',
                'title' => 'Vendor Margin Update',
                'entity_type' => null,
                'description' => 'Update Vendor Margin and recalculate pricing',
                'category' => 'Vendors',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'vendor.access-all',
                'title' => 'Access All Vendors',
                'entity_type' => null,
                'description' => 'Grants Access to All Vendors',
                'category' => 'Vendors',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'brand.administrator',
                'title' => 'Brand Administrator',
                'entity_type' => null,
                'description' => 'Handle Signoffs for New Accounts',
                'category' => 'Vendors',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'mafs.view',
                'title' => 'View All MAFs',
                'entity_type' => null,
                'description' => 'View All MAFs',
                'category' => 'MAFs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'pafs.view',
                'title' => 'View All PAFs',
                'entity_type' => null,
                'description' => 'View All PAFs',
                'category' => 'PAFs',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc.view-all-qc-records',
                'title' => 'View All QC Records',
                'entity_type' => null,
                'description' => 'View All QC Records',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc.menu',
                'title' => 'View QC Menu',
                'entity_type' => null,
                'description' => 'Allow user to view the QC menu option',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-technician-01',
                'title' => 'Manage QC records - Warehouse 01',
                'entity_type' => null,
                'description' => 'Allow user to manage QC records for warehouse 01',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-technician-04',
                'title' => 'Manage QC records - Warehouse 04',
                'entity_type' => null,
                'description' => 'Allow user to manage QC records for warehouse 04',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-technician-08',
                'title' => 'Manage QC records - Warehouse 08',
                'entity_type' => null,
                'description' => 'Allow user to manage QC records for warehouse 08',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-technician-09',
                'title' => 'Manage QC records - Warehouse 09',
                'entity_type' => null,
                'description' => 'Allow user to manage QC records for warehouse 09',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];

        DB::table('abilities')->insert($abilities);
    }

    private function insertRoles(Datetime $timestamp)
    {
        $roles = [
            [
                'name' => 'super-admin',
                'title' => 'Super Admin',
                'description' => 'Full access to all systems',
                'category' => 'Admin',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'admin',
                'title' => 'Admin',
                'description' => 'Full access to most systems',
                'category' => 'Admin',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'purchasing-specialist',
                'title' => 'Purchasing Specialist',
                'description' => 'Purchasing Specialist',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'pricing',
                'title' => 'Pricing',
                'description' => 'Signoff PAFs and manage users',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'vendor-relations-specialist',
                'title' => 'Vendor Relations Specialist',
                'description' => 'Vendor Relations Specialist',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'brand-administrator',
                'title' => 'Brand Administrator',
                'description' => 'Manage Signoffs for new Accounts',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'quality-control-specialist',
                'title' => 'Quality Control Specialist',
                'description' => 'Quality Control Specialist',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'sales-manager',
                'title' => 'Sales Manager',
                'description' => 'Approving MAFs',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'marketing',
                'title' => 'Marketing',
                'category' => 'Marketing',
                'description' => 'View access to products, vendors, brands, promos.  Reporting access to all exports.',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'maf-accountant',
                'title' => 'MAF Accountant',
                'description' => 'Access to the Accounting step on MAFs',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'sales-rep',
                'title' => 'Sales Rep',
                'description' => 'View all products, limited export access, have retailers assigned',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'customer-care',
                'title' => 'Customer Care',
                'description' => 'View all products',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'vendor-finance',
                'title' => 'Vendor Finance',
                'description' => 'View finance data for assigned vendors',
                'category' => 'Vendor',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'finance',
                'title' => 'Finance',
                'description' => 'View finance data for all Vendors',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'finance-manager',
                'title' => 'Finance Manager',
                'description' => 'Trigger upload process and add finance tag to vendor users',
                'category' => 'Management',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'management',
                'title' => 'Management',
                'description' => 'Signoff (mass) products',
                'category' => 'Management',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'inventory-management-low',
                'title' => 'Inventory Management Low Value',
                'description' => 'Allow signoff on low-value inventory removals',
                'category' => 'Management',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'inventory-management-high',
                'title' => 'Inventory Management High Value',
                'description' => 'Allow signoff on high-value inventory removals',
                'category' => 'Management',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'brand-disco-signoff',
                'title' => 'Brand Disco Signoff',
                'description' => 'Signoff brand disco requests',
                'category' => 'Management',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'costing-specialist',
                'title' => 'Costing Specialist',
                'description' => 'Signoff Product Pricing step, exchange rate and margin updates, final signoff for promos, manage disco promos',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'vendor-admin',
                'title' => 'Vendor Admin',
                'description' => 'Create other Vendor user accounts for accessible Vendors',
                'category' => 'Vendor',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'vendor',
                'title' => 'Vendor',
                'description' => 'Vendor level access (brand, product, promo)',
                'category' => 'Vendor',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'vendor-view-only',
                'title' => 'Vendor View Only',
                'description' => 'Vendor level access (read-only) (brand, product, promo)',
                'category' => 'Vendor',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'broker-admin',
                'title' => 'Broker Admin',
                'description' => 'Create other Broker user accounts for assigned broker',
                'category' => 'Vendor',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'broker',
                'title' => 'Broker',
                'description' => 'Vendor level access (brand, product, promo) for multiple vendors',
                'category' => 'Vendor',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'broker-view-only',
                'title' => 'Broker View Only',
                'description' => 'Vendor level access (read-only) (brand, product, promo) for multiple vendors',
                'category' => 'Vendor',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'full-view-access',
                'title' => 'Full View Access',
                'description' => 'Read only, all vendors (brand, product, promo)',
                'category' => 'Users',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'warehouse-qc',
                'title' => 'Warehouse QC',
                'description' => 'Signoff Inventory Removal requests',
                'category' => 'Warehouse',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'warehouse-shipping',
                'title' => 'Warehouse Shipping',
                'description' => 'Signoff Inventory Removal requests',
                'category' => 'Warehouse',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'warehouse-01',
                'title' => 'Warehouse 01 Tag',
                'description' => 'Warehouse 01 Tag',
                'category' => 'Warehouse',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'warehouse-04',
                'title' => 'Warehouse 04 Tag',
                'description' => 'Warehouse 04 Tag',
                'category' => 'Warehouse',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'warehouse-08',
                'title' => 'Warehouse 08 Tag',
                'description' => 'Warehouse 08 Tag',
                'category' => 'Warehouse',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'warehouse-09',
                'title' => 'Warehouse 09 Tag',
                'description' => 'Warehouse 09 Tag',
                'category' => 'Warehouse',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-technician-01',
                'title' => 'Quality Control Technician - Warehouse 01',
                'description' => 'Quality Control Technician Role for Warehouse 01',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-technician-04',
                'title' => 'Quality Control Technician - Warehouse 04',
                'description' => 'Quality Control Technician Role for Warehouse 04',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-technician-08',
                'title' => 'Quality Control Technician - Warehouse 08',
                'description' => 'Quality Control Technician Role for Warehouse 08',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-technician-09',
                'title' => 'Quality Control Technician - Warehouse 09',
                'description' => 'Quality Control Technician Role for Warehouse 09',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'qc-admin',
                'title' => 'Quality Control Technician',
                'description' => 'Quality Control Admin Role',
                'category' => 'Quality Control',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
