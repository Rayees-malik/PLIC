<?php

namespace App\Datatables;

use App\Models\BrandDiscoRequest;

class BrandDiscoRequestsDatatable extends BaseDatatable
{
    protected $orderBy = [[2, 'desc']];

    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('created_at', function ($discoRequest) {
                return $discoRequest->created_at->toDateTimeString();
            })
            ->addColumn('action', function ($discoRequest) {
                return '<a href="' . route('branddiscos.show', $discoRequest->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
            })
            ->rawcolumns(['action'])
            ->filter(function ($query) {
                if (request()->submitted_by) {
                    $query->where('submitted_by', request()->submitted_by);
                }
            }, true);
    }

    public function query()
    {
        return BrandDiscoRequest::with([
            'user' => function ($query) {
                $query->select('id', 'name');
            },
        ]);
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Brand',
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
