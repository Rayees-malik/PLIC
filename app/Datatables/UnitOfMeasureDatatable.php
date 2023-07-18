<?php

namespace App\Datatables;

use App\Models\UnitOfMeasure;
use Bouncer;

class UnitOfMeasureDatatable extends BaseDatatable
{
    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('description', function ($uom) {
                return '<a href="' . route('uom.show', $uom->id) . '" class="text-link">' . $uom->description . '</a>';
            })
            ->addColumn('action', function ($uom) {
                if (Bouncer::can('edit.lookups')) {
                    return '<a href="' . route('uom.edit', $uom->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>';
                }
            })
            ->rawcolumns(['description', 'action']);
    }

    public function query()
    {
        return UnitOfMeasure::select('id', 'description', 'unit');
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Description',
                'data' => 'description',
            ],
            [
                'title' => 'Unit',
                'data' => 'unit',
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
