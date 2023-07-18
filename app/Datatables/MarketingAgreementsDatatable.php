<?php

namespace App\Datatables;

use App\Models\MarketingAgreement;
use Illuminate\Support\Facades\DB;

class MarketingAgreementsDatatable extends BaseDatatable
{
    protected $orderBy = [[3, 'desc']];

    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('account', function ($agreement) {
                return "{$agreement->name} (#{$agreement->account})";
            })
            ->editColumn('updated_at', function ($agreement) {
                return $agreement->updated_at->toFormattedDateString();
            })
            ->addColumn('action', function ($agreement) {
                return '<a href="' . route('marketingagreements.show', $agreement->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
            })
            ->rawcolumns(['action'])
            ->filter(function ($query) {
                if (request()->submitted_by) {
                    $query->where('submitted_by', request()->submitted_by);
                }

                if (request()->brand_id) {
                    $query->whereHas('lineItems.brand', function ($query) {
                        $query->where('brand_id', request()->brand_id);
                    });
                }
            }, true);
    }

    public function query()
    {
        $query = MarketingAgreement::join('users', 'users.id', 'marketing_agreements.submitted_by')
            ->join('marketing_agreement_line_items', 'marketing_agreement_line_items.marketing_agreement_id', 'marketing_agreements.id')
            ->leftJoin('brands', function ($join) {
                $join->on('marketing_agreement_line_items.brand_id', 'brands.id')
                    ->whereNull('marketing_agreement_line_items.deleted_at');
            });

        if (! auth()->user()->can('mafs.view')) {
            $query = $query->where('marketing_agreements.submitted_by', auth()->id());
        }

        return $query->select(
            'marketing_agreements.id', 'marketing_agreements.cloned_from_id', 'marketing_agreements.name',
            'marketing_agreements.retailer_invoice', 'marketing_agreements.updated_at', 'account', 'users.name as users.name',
            DB::raw("GROUP_CONCAT(DISTINCT brands.name ORDER BY brands.name SEPARATOR ', ') AS brands_concat")
        )
            ->groupBy('marketing_agreements.id');
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Id',
                'data' => 'id',
            ],
            [
                'title' => 'Account',
                'data' => 'account',
            ],
            [
                'title' => 'name',
                'data' => 'name',
                'visible' => false,
            ],
            [
                'title' => 'Brands',
                'data' => 'brands_concat',
                'searchable' => false,
            ],
            [
                'title' => 'Invoice #',
                'data' => 'retailer_invoice',
            ],
            [
                'title' => 'Submitted By',
                'data' => 'users.name',
            ],
            [
                'title' => 'Last Updated',
                'data' => 'updated_at',
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
