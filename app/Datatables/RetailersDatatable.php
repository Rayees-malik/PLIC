<?php

namespace App\Datatables;

use App\Models\Retailer;
use Bouncer;

class RetailersDatatable extends BaseDatatable
{
    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('name', function ($retailer) {
                return '<a href="' . route('retailers.show', $retailer->id) . '" class="text-link">' . $retailer->name . '</a>';
            })
            ->addColumn('action', function ($retailer) {
                $viewButton = '<a href="' . route('retailers.show', $retailer->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
                $editButton = Bouncer::can('edit', $retailer) ? '<a href="' . route('retailers.edit', $retailer->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>' : '';

                return $editButton . $viewButton;
            })
            ->rawColumns(['name', 'action']);
    }

    public function query()
    {
        return Retailer::withAccess()->select(['id', 'name']);
    }

    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters($this->getBuilderParameters());
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Name',
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
