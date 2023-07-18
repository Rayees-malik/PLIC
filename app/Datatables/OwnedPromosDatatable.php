<?php

namespace App\Datatables;

use Bouncer;

class OwnedPromosDatatable extends BaseDatatable
{
    protected $orderBy = [[3, 'desc']];

    protected $customFilters = true;

    public function __construct(protected $promos)
    {
    }

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('retailers.name', function ($promo) {
                return '<a href="' . route('retailers.show', $promo['retailers.id']) . '" class="text-link">' . $promo['retailers.name'] . '</a>';
            })
            ->editColumn('brands.name', function ($promo) {
                return '<a href="' . route('brands.show', $promo['brands.id']) . '" class="text-link">' . $promo['brands.name'] . '</a>';
            })
            ->addColumn('action', function ($promo) {
                $viewButton = '<a href="' . route('promos.show', $promo->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
                $editButton = Bouncer::can('edit', $promo) ? '<a href="' . route('promos.edit', $promo->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>' : '';

                return $editButton . $viewButton;
            })
            ->rawColumns(['retailers.name', 'brands.name', 'action'])
            ->filter(function ($query) {
                // TODO: remove hardcoded retailers
                if (request()->retailer_id) {
                    $query->where('retailers.id', request()->retailer_id);
                }

                if (request()->brand_id) {
                    $query->where('brands.id', request()->brand_id);
                }
            }, true);
    }

    public function query()
    {
        return $this->promos;
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Retailer',
                'data' => 'retailers.name',
            ],
            [
                'title' => 'Brand',
                'data' => 'brands.name',
            ],
            [
                'title' => 'Promo Period',
                'data' => 'promo_periods.name',
            ],
            [
                'title' => 'Start Date',
                'data' => 'promo_periods.start_date',
                'orderable' => false,
                'printable' => false,
                'visible' => false,
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
