<?php

namespace App\Datatables;

use App\Models\Signoff;

class SignoffsDatatable extends BaseDatatable
{
    protected $orderBy = [[5, 'desc']];

    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('proposed_type', function ($signoff) {
                $type = $signoff->proposed::getShortClassName();

                if ($type == 'Product' && $signoff->proposed->unit_cost > $signoff->initial->unit_cost) {
                    return '<span class="d-flex">' . $type . '<i class="pl-2 material-icons">price_change</i></span>';
                }

                return $type;
            })
            ->addColumn('description', function ($signoff) {
                $description = $signoff->proposed->displayName;

                if ($signoff->proposed::getShortClassName(false) == 'MarketingAgreement') {
                    $total = $signoff->proposed->lineItems()->sum('cost');
                    $taxRate = $signoff->proposed->tax_rate;

                    if (is_null($taxRate) || $taxRate == 0) {
                        $description .= '<span class="tw-font-bold tw-pl-2">$' . number_format($total, 2) . '</span>';
                    } else {
                        $description .= '<span class="tw-font-bold tw-pl-2">$' . number_format($total * (1 + ($taxRate / 100)), 2) . '</span>';
                    }
                }

                return $description;
            })
            ->editColumn('user.name', function ($signoff) {
                return '<a href="' . route('users.show', $signoff->user) . '" class="text-link">' . $signoff->user->name . '</a>';
            })
            ->editColumn('step', function ($signoff) {
                return $signoff->currentStepName;
            })
            ->editColumn('new_submission', function ($signoff) {
                if ($signoff->initial_type === \App\Models\Product::class) {
                    if (optional($signoff->initial->as400StockData)->status == 'D') {
                        return 'Relist';
                    }
                }

                return $signoff->new_submission ? 'New' : 'Update';
            })
            ->editColumn('submitted_at', function ($signoff) {
                return $signoff->submitted_at ? $signoff->submitted_at->toDayDateTimeString() : $signoff->updated_at->toDayDateTimeString();
            })
            ->addColumn('action', function ($signoff) {
                return '<a href="' . route('signoffs.edit', $signoff->id) . '" class="link-btn table-btn"><i class="material-icons">fact_check</i> Review</a>';
            })
            ->rawcolumns(['proposed_type', 'description', 'user.name', 'action'])
            ->filter(function ($query) {
                session(['signoff_filter' => request()->signoff_type]);
                if (request()->signoff_type) {
                    $query->where('proposed_type', request()->signoff_type);
                }

                if (request()->search['value']) {
                    $searchTerm = '%' . request()->search['value'] . '%';
                    $query->where(function ($query) use ($searchTerm) {
                        $query->whereHas('user', function ($query) use ($searchTerm) {
                            $query->whereRaw('lower(name) like ?', [$searchTerm]);
                        })->orWhereHasMorph('proposed', '*', function ($query, $type) use ($searchTerm) {
                            switch ($type) {
                                case \App\Models\Product::class:
                                    $query->allStates()
                                        ->whereRaw('lower(name) like ?', [$searchTerm])
                                        ->orWhereRaw('lower(stock_id) like ?', [$searchTerm])
                                        ->orWhereHas('brand', function ($query) use ($searchTerm) {
                                            $query->whereRaw('lower(name) like ?', [$searchTerm]);
                                        });
                                    break;
                                case \App\Models\PricingAdjustment::class:
                                    $query->allStates()
                                        ->whereRaw('lower(name) like ?', [$searchTerm])
                                        ->orWhereRaw('id like ?', [$searchTerm])
                                        ->orWhereRaw('cloned_from_id like ?', [$searchTerm]);
                                    break;
                                case \App\Models\MarketingAgreement::class:
                                    $query->allStates()
                                        ->whereRaw('lower(name) like ?', [$searchTerm])
                                        ->whereRaw('lower(retailer_invoice) like ?', [$searchTerm]);
                                    break;
                                case \App\Models\ProductDelistRequest::class:
                                    $query->allStates()
                                        ->whereRaw('lower(name) like ?', [$searchTerm])
                                        ->orWhereHas('product.brand', function ($query) use ($searchTerm) {
                                            $query->whereRaw('lower(name) like ?', [$searchTerm]);
                                        });
                                    break;
                                default:
                                    $query->allStates()->whereRaw('lower(name) like ?', [$searchTerm]);
                            }
                        });
                    });
                }
            }, false);
    }

    public function query()
    {
        // TODO: Combine into a reusable scope for with UserController::submissions()
        return Signoff::with([
            'proposed' => function ($query) {
                $query->morphWith(
                    [
                        \App\Models\Product::class => [
                            'as400StockData',
                            'brand' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ],
                        \App\Models\ProductDelistRequest::class => [
                            'product' => function ($query) {
                                $query->with([
                                    'brand' => function ($query) {
                                        $query->select('id', 'name');
                                    },
                                ])->select('id', 'brand_id');
                            },
                        ],
                    ]
                );
            },
            'initial' => function ($query) {
                $query->morphWith(
                    [
                        \App\Models\Product::class => [
                            'as400StockData',
                            'brand' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ],
                        \App\Models\ProductDelistRequest::class => [
                            'product' => function ($query) {
                                $query->with([
                                    'brand' => function ($query) {
                                        $query->select('id', 'name');
                                    },
                                ])->select('id', 'brand_id');
                            },
                        ],
                    ]
                );
            },
            'user' => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->pending()
            ->forUser();
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Type',
                'data' => 'proposed_type',
            ],
            [
                'title' => 'Description',
                'data' => 'description',
            ],
            [
                'title' => 'Submitted By',
                'data' => 'user.name',
                'orderable' => false,
            ],
            [
                'title' => 'Current Step',
                'data' => 'step',
                'searchable' => false,
            ],
            [
                'title' => 'Status',
                'data' => 'new_submission',
                'searchable' => false,
            ],
            [
                'title' => 'Date',
                'data' => 'submitted_at',
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
