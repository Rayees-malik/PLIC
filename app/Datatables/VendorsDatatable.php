<?php

namespace App\Datatables;

use App\Models\Vendor;
use Bouncer;

class VendorsDatatable extends BaseDatatable
{
    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('name', function ($vendor) {
                return '<a href="' . route('vendors.show', $vendor->id) . '" class="text-link">' . $vendor->name . '</a>';
            })
            ->addColumn('brands', function ($vendor) {
                $brands = [];
                foreach ($vendor->brands as $brand) {
                    $brands[] = '<a href="' . route('brands.show', $brand->id) . '" class="text-link">' . $brand->name . '</a>';
                }

                return implode(', ', $brands);
            })
            ->editColumn('status', function ($vendor) {
                return $vendor->getStatus();
            })
            ->addColumn('action', function ($vendor) {
                $viewButton = '<a href="' . route('vendors.show', $vendor->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
                $editButton = Bouncer::can('edit', $vendor) ? '<a href="' . route('vendors.edit', $vendor->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>' : '';

                return $editButton . $viewButton;
            })
            ->rawcolumns(['name', 'brands', 'action'])
            ->filter(function ($query) {
                if (request()->status) {
                    $query->where('status', request()->status);
                }
            }, true);
    }

    public function query()
    {
        return Vendor::with(['brands' => function ($query) {
            $query->initial()->select('id', 'vendor_id', 'name')->ordered();
        }])
            ->withAccess()
            ->select(['id', 'name', 'status']);
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Vendor',
                'data' => 'name',
            ],
            [
                'title' => 'Brands',
                'data' => 'brands',
            ],
            [
                'title' => 'Status',
                'data' => 'status',
            ],
            [
                'title' => '',
                'data' => 'action',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ],
        ];
    }
}
