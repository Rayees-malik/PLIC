<?php

namespace App\Datatables;

use App\Models\Product;
use Bouncer;
use Illuminate\Support\Facades\DB;

class ProductsDatatable extends BaseDatatable
{
    protected $orderBy = [[1, 'asc']];

    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('stock_id', function ($product) {
                return '<a href="' . route('products.show', $product->id) . '" class="text-link">' . $product->stock_id . '</a>';
            })
            ->editColumn('name', function ($product) {
                $subNote = $product->getLongSizeWithUnits() . ' | ' . $product->sellByUPC;

                return '<a href="' . route('products.show', $product->id) . '" class="text-link">' . $product->getName() . '</a><small class="subnote">' . $subNote . '</small>';
            })
            ->editColumn('brands.name', function ($product) {
                return '<a href="' . route('brands.show', $product->brand_id) . '" class="text-link">' . $product['brands.name'] . '</a>';
            })
            ->addColumn('action', function ($product) {
                $viewButton = '<a href="' . route('products.show', $product->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
                $editButton = Bouncer::can('edit', $product) ? '<a href="' . route('products.edit', $product->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>' : '';

                return $editButton . $viewButton;
            })
            ->rawcolumns(['stock_id', 'name', 'brands.name', 'action'])
            ->filter(function ($query) {
                if (request()->brand_id) {
                    $query->where('products.brand_id', request()->brand_id);
                }

                if (request()->status) {
                    $query->where('as400_stock_data.status', request()->status);
                }
            }, true);
    }

    public function query()
    {
        return Product::withAccess()
            ->with([
                'uom' => function ($query) {
                    $query->select('id', 'unit', 'description');
                }, ])
            ->join('brands', 'products.brand_id', 'brands.id')
            ->leftJoin('as400_stock_data', 'products.id', 'as400_stock_data.product_id')
            ->leftJoin('catalogue_categories', 'products.catalogue_category_id', 'catalogue_categories.id')
            ->select(['products.id', 'products.state', 'products.brand_id', 'brand_stock_id', 'ingredients', 'stock_id', 'products.name', 'products.name_fr', 'packaging_language', 'size',
                'uom_id', 'products.upc', 'products.inner_upc', 'products.master_upc', 'products.purity_sell_by_unit', 'products.master_units', 'products.inner_units',

                'catalogue_categories.name as catalogue_categories.name', 'brands.name as brands.name', DB::raw('ifnull(as400_stock_data.status, \'-\') as as400_stock_data_status')]);
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Stock Id',
                'data' => 'stock_id',
            ],
            [
                'title' => 'Name',
                'data' => 'name',
            ],
            [
                'title' => 'Brand',
                'data' => 'brands.name',
            ],
            [
                'title' => 'Category',
                'data' => 'catalogue_categories.name',
            ],
            [
                'title' => 'Brand Stock Id',
                'data' => 'brand_stock_id',
            ],
            [
                'title' => 'Ingredients',
                'data' => 'ingredients',
                'orderable' => false,
                'printable' => false,
                'visible' => false,
            ],
            [
                'title' => 'UPC',
                'data' => 'upc',
                'orderable' => false,
                'printable' => false,
                'visible' => false,
            ],
            [
                'title' => 'Inner UPC',
                'data' => 'inner_upc',
                'orderable' => false,
                'printable' => false,
                'visible' => false,
            ],
            [
                'title' => 'Master UPC',
                'data' => 'master_upc',
                'orderable' => false,
                'printable' => false,
                'visible' => false,
            ],
            [
                'title' => 'Status',
                'data' => 'as400_stock_data_status',
                'searchable' => false,
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
