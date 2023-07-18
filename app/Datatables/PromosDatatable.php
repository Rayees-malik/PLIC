<?php

namespace App\Datatables;

use Bouncer;
use Carbon\Carbon;

class PromosDatatable extends BaseDatatable
{
    protected $orderBy = [[2, 'asc']];

    protected $customFilters = true;

    public function __construct(protected $promos)
    {
    }

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('brands.name', function ($promo) {
                return '<a href="' . route('brands.show', $promo['brands.id']) . '" class="text-link">' . $promo['brands.name'] . '</a>';
            })
            ->editColumn('promo_periods.start_date', function ($promo) {
                return "{$promo['promo_periods.start_date']} - {$promo['promo_periods.end_date']}";
            })
            ->addColumn('action', function ($promo) {
                $viewButton = '<a href="' . route('promos.show', $promo->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
                $editButton = ($promo['promo_periods.active'] || ! auth()->user()->isVendor) && Bouncer::can('edit', $promo) ? '<a href="' . route('promos.edit', $promo->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>' : '';

                return $editButton . $viewButton;
            })
            ->rawColumns(['brands.name', 'action'])
            ->filter(function ($query) {
                if (request()->brand_id) {
                    $query->where('brands.id', request()->brand_id);
                }

                if (request()->period_id) {
                    $query->where('promo_periods.id', request()->period_id);
                }

                if (request()->for_date) {
                    $query->where([
                        ['promo_periods.start_date', '<=', request()->for_date],
                        ['promo_periods.end_date', '>=', request()->for_date],
                    ]);
                }

                if (! request()->period_id && ! request()->for_date) {
                    $query->where('promo_periods.start_date', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateString());
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
                'title' => 'Brand',
                'data' => 'brands.name',
            ],
            [
                'title' => 'Promo Period',
                'data' => 'promo_periods.name',
            ],
            [
                'title' => 'Dates',
                'data' => 'promo_periods.start_date',
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
