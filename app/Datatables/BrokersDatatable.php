<?php

namespace App\Datatables;

use App\Models\Broker;
use Bouncer;

class BrokersDatatable extends BaseDatatable
{
    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('name', function ($broker) {
                return '<a href="' . route('brokers.edit', $broker->id) . '" class="text-link">' . $broker->name . '</a>';
            })
            ->addColumn('action', function ($broker) {
                if (Bouncer::can('edit.lookups')) {
                    return '<a href="' . route('brokers.edit', $broker->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>';
                }
            })
            ->rawcolumns(['name', 'action']);
    }

    public function query()
    {
        return Broker::select('id', 'name');
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Broker',
                'data' => 'name',
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
