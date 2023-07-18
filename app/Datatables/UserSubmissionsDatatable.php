<?php

namespace App\Datatables;

use App\Models\Product;
use App\Models\ProductDelistRequest;
use App\Models\Signoff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class UserSubmissionsDatatable extends BaseDatatable
{
    protected $orderBy = [[3, 'desc']];

    public function __construct(protected $tab)
    {
    }

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('proposed_type', function ($signoff) {
                return $signoff->proposed::getShortClassName();
            })
            ->addColumn('description', function ($signoff) {
                return $signoff->proposed->displayName;
            })
            ->editColumn('step', function ($signoff) {
                return $signoff->currentStepName;
            })
            ->addColumn('action', function ($signoff) {
                $editRoute = "{$signoff->proposed->routePrefix}.edit";
                $actionButton = '';
                if (($signoff->rejected || $signoff->saved) && Route::has($editRoute)) {
                    $actionButton = '<a href="' . route($editRoute, $signoff->proposed) . '" class="link-btn table-btn"><i class="material-icons">edit</i> Edit</a>';
                } else {
                    $actionButton = '<a href="' . route('signoffs.show', $signoff->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i> View</a>';
                }

                $deleteButton = '';
                if ($signoff->archived || $signoff->rejected || $signoff->saved) {
                    $deleteButton = '<button type="button" class="link-btn delete table-btn" data-toggle="modal" title="Delete ';
                    $deleteButton .= $signoff->proposed::getShortClassName() . '" data-action="' . route('signoffs.delete', $signoff);
                    $deleteButton .= '" data-label="' . $signoff->proposed->name . '" data-target="#deleteModal">';
                    $deleteButton .= '<i class="material-icons">delete</i>Delete</button>';
                }

                $unsubmitButton = '';
                if (($signoff->approved && $signoff->proposed->canUnsubmitApproved) || ($signoff->pending && $signoff->step == 1 && $signoff->proposed->canUnsubmitPending)) {
                    $unsubmitButton = '<button type="button" class="link-btn delete table-btn" data-toggle="modal" title="Unsubmit ';
                    $unsubmitButton .= $signoff->proposed::getShortClassName() . '" data-action="' . route('signoffs.unsubmit', $signoff);
                    $unsubmitButton .= '" data-label="' . $signoff->proposed->name . '" data-target="#unsubmitModal">';
                    $unsubmitButton .= '<i class="material-icons">cancel_presentation</i>Unsubmit</button>';
                }

                return "{$unsubmitButton}{$deleteButton}{$actionButton}";
            })
            ->rawcolumns(['description', 'action'])
            ->filter(function ($query) {
                session(['signoff_filter' => request()->signoff_type]);
                if (request()->signoff_type) {
                    $query->where('proposed_type', request()->signoff_type);
                }

                if (request()->search && request()->search['value']) {
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
        $baseQuery = Signoff::with([
            'signoffConfig',
            'proposed' => function ($query) {
                $query->morphWith(
                    [
                        Product::class => [
                            'brand' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ],
                        ProductDelistRequest::class => [
                            'product' => function ($query) {
                                $query->with('brand:id,name')->select('id', 'name', 'brand_id');
                            },
                        ],
                    ]
                );
            },
        ])->where('user_id', auth()->id())
            ->select([
                'id',
                'signoff_config_id',
                'proposed_type',
                'proposed_id',
                'state',
                'submitted_at',
                'updated_at',
                DB::raw("CASE WHEN submitted_at IS NULL THEN DATE_FORMAT(updated_at, '%Y-%m-%d %h:%i %p') ELSE DATE_FORMAT(submitted_at, '%Y-%m-%d %h:%i %p') END AS timestamp"),
            ]);

        return match ($this->tab) {
            'rejected' => $baseQuery->rejected(),
            'approved' => $baseQuery->approved(),
            'pending' => $baseQuery->pending(),
            'draft' => $baseQuery->inProgress(),
            'outdated' => $baseQuery->archived(),
            default => $baseQuery->rejected(),
        };
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
                'title' => 'Current Step',
                'data' => 'step',
            ],
            [
                'title' => 'Timestamp',
                'data' => 'timestamp',
                'searchable' => true,
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
