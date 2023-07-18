<?php

namespace App\Datatables;

class SubmissionsDataTable extends BaseDatatable
{
    protected $orderBy = [[3, 'desc']];

    public function __construct(protected $submissions)
    {
    }

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('user.name', function ($signoff) {
                return '<a href="' . route('users.show', $signoff->user) . '" class="text-link">' . $signoff->user->name . '</a>';
            })
            ->addColumn('description', function ($signoff) {
                return $signoff->proposed->displayName;
            })
            ->editColumn('step', function ($signoff) {
                return $signoff->currentStepName;
            })
            ->editColumn('submitted_at', function ($signoff) {
                return $signoff->submitted_at ? $signoff->submitted_at->toDayDateTimeString() : $signoff->updated_at->toDayDateTimeString();
            })
            ->addColumn('action', function ($signoff) {
                return '<a href="' . route('signoffs.show', $signoff->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i> View</a>';
            })
            ->rawcolumns(['user.name', 'action'])
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
        return $this->submissions;
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Submitted By',
                'data' => 'user.name',
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
