<?php

namespace App\Datatables;

use App\Models\ProductDelistRequest;

class ProductDelistRequestsDatatable extends BaseDatatable
{
    protected $orderBy = [[2, 'desc']];

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('created_at', function ($delistRequest) {
                return $delistRequest->created_at->toDateTimeString();
            })
            ->addColumn('action', function ($delistRequest) {
                return '<a href="' . route('productdelists.show', $delistRequest->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
            })
            ->rawcolumns(['action']);
    }

    public function query()
    {
        return ProductDelistRequest::withAccess()->with([
            'user' => function ($query) {
                $query->select('id', 'name');
            },
        ]);
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Product',
                'data' => 'name',
            ],
            [
                'title' => 'Submitted By',
                'data' => 'user.name',
            ],
            [
                'title' => 'Timestamp',
                'data' => 'created_at',
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
