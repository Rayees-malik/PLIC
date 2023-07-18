<?php

namespace App\Datatables;

use App\Models\Brand;
use Bouncer;

class BrandsDatatable extends BaseDatatable
{
    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('name', function ($brand) {
                return '<a href="' . route('brands.show', $brand->id) . '" class="text-link">' . $brand->name . '</a>';
            })
            ->editColumn('vendors.name', function ($brand) {
                return '<a href="' . route('vendors.show', $brand['vendors.id']) . '" class="text-link">' . $brand['vendors.name'] . '</a>';
            })
            ->editColumn('status', function ($brand) {
                return $brand->getStatus();
            })
            ->addColumn('action', function ($brand) {
                $viewButton = '<a href="' . route('brands.show', $brand->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
                $editButton = Bouncer::can('edit', $brand) ? '<a href="' . route('brands.edit', $brand->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>' : '';
                $categoriesButton = '<a href="' . route('brands.categories', $brand->id) . '" class="link-btn table-btn"><i class="material-icons">view_list</i>Categories</a>';

                return $categoriesButton . $editButton . $viewButton;
            })
            ->rawcolumns(['name', 'vendors.name', 'action'])
            ->filter(function ($query) {
                if (request()->vendor_id) {
                    $query->where('vendor_id', request()->vendor_id);
                }

                if (request()->status) {
                    $query->where('brands.status', request()->status);
                }
            }, true);
    }

    public function query()
    {
        return Brand::withAccess()
            ->join('vendors', 'brands.vendor_id', 'vendors.id')
            ->select(['brands.id', 'brands.brand_number', 'vendors.name as vendors.name', 'vendors.id as vendors.id', 'brands.name', 'brands.status']);
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Name',
                'data' => 'name',
            ],
            [
                'title' => 'Brand Number',
                'data' => 'brand_number',
            ],
            [
                'title' => 'Vendor',
                'data' => 'vendors.name',
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
