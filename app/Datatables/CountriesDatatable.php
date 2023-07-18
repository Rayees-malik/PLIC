<?php

namespace App\Datatables;

use App\Models\Country;
use Bouncer;

class CountriesDatatable extends BaseDatatable
{
    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('name', function ($country) {
                return '<a href="' . route('countries.show', $country->id) . '" class="text-link">' . $country->name . '</a>';
            })
            ->addColumn('action', function ($country) {
                if (Bouncer::can('edit.lookups')) {
                    return '<a href="' . route('countries.edit', $country->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>';
                }
            })
            ->rawcolumns(['name', 'action']);
    }

    public function query()
    {
        return Country::select('id', 'name');
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Country',
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
