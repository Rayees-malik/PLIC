<?php

namespace App\Datatables;

use App\Models\PricingAdjustment;
use Illuminate\Support\Facades\DB;

class PricingAdjustmentsDatatable extends BaseDatatable
{
    protected $orderBy = [[0, 'desc']];

    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->addColumn('brands', function ($adjustment) {
                $brands = [];
                foreach ($adjustment->lineItems as $lineItem) {
                    if (! $lineItem->item) {
                        continue;
                    }

                    if ($lineItem->item->brand) {
                        $brands[] = $lineItem->item->brand->name;
                    } else {
                        $brands[] = $lineItem->item->name;
                    }
                }

                return implode(', ', array_unique(array_filter($brands)));
            })
            ->addColumn('action', function ($adjustment) {
                return '<a href="' . route('pricingadjustments.show', $adjustment->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
            })
            ->rawcolumns(['action'])
            ->filter(function ($query) {
                if (request()->account) {
                    $accounts = array_filter(explode('|', request()->account));

                    $query->where(function ($query) use ($accounts) {
                        foreach ($accounts as $account) {
                            $query->orWhereRaw('lower(name) like ?', ["%{$account}%"]);
                        }
                    });
                }

                if (request()->submitted_by) {
                    $query->where('submitted_by', request()->submitted_by);
                }

                if (request()->brand_id) {
                    $query->whereHas('lineItems', function ($query) {
                        $query->whereHasMorph('item', '*', function ($query, $type) {
                            $column = $type == \App\Models\Brand::class ? 'brands.id' : 'brand_id';
                            $query->where($column, request()->brand_id);
                        });
                    });
                }
            }, true);
    }

    public function query()
    {
        return PricingAdjustment::with(
            [
                'lineItems.item' => function ($query) {
                    $query->morphWith([\App\Models\Product::class => ['brand' => function ($query) {
                        $query->select('id', 'name');
                    }]]);
                },
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->when(auth()->user()->cannot('pafs.view'), function ($query) {
                $query->where('submitted_by', auth()->id());
            })
            ->select([
                'pricing_adjustments.id',
                'cloned_from_id',
                'name',
                'start_date',
                'end_date',
                'submitted_by',
                DB::raw("CASE WHEN end_date IS NULL THEN DATE_FORMAT(start_date, '%Y-%m-%d') ELSE CONCAT(DATE_FORMAT(start_date, '%Y-%m-%d'), ' - ', DATE_FORMAT(end_date, '%Y-%m-%d')) END as date"),
            ]);
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Id',
                'data' => 'id',
            ],
            [
                'title' => 'Account(s)',
                'data' => 'name',
            ],
            [
                'title' => 'Brands',
                'data' => 'brands',
                'searchable' => false,
            ],
            [
                'title' => 'start_date',
                'data' => 'start_date',
                'visible' => false,
            ],
            [
                'title' => 'Date',
                'data' => 'date',
                'searchable' => false,
            ],
            [
                'title' => 'Submitted By',
                'data' => 'user.name',
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
