<?php

namespace App\Datatables;

use App\Models\Currency;
use Bouncer;

class CurrenciesDatatable extends BaseDatatable
{
    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('name', function ($currency) {
                return '<a href="' . route('currencies.show', $currency->id) . '" class="text-link">' . $currency->name . '</a>';
            })
            ->addColumn('action', function ($currency) {
                if (Bouncer::can('edit.lookups')) {
                    return '<a href="' . route('currencies.edit', $currency->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>';
                }
            })
            ->rawcolumns(['name', 'action']);
    }

    public function query()
    {
        return Currency::select('id', 'name', 'exchange_rate');
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Currency',
                'data' => 'name',
            ],
            [
                'title' => 'Exchange Rate',
                'data' => 'exchange_rate',
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
