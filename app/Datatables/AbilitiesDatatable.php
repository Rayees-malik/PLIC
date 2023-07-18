<?php

namespace App\Datatables;

use App\Models\Ability;
use Bouncer;

class AbilitiesDatatable extends BaseDatatable
{
    protected $orderBy = [[1, 'asc'], [0, 'asc']];

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('title', function ($ability) {
                return '<a href="' . route('abilities.edit', $ability->name) . '" class="text-link">' . $ability->title . '</a>';
            })
            ->addColumn('action', function ($ability) {
                if (Bouncer::can('user.roles.edit')) {
                    return '<a href="' . route('abilities.edit', $ability->name) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>';
                }
            })
            ->rawcolumns(['title', 'action']);
    }

    public function query()
    {
        return Ability::filterModelAbilities()->select(['id', 'name', 'title', 'category', 'description']);
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Ability',
                'data' => 'title',
            ],
            [
                'title' => 'Category',
                'data' => 'category',
            ],
            [
                'title' => 'Description',
                'data' => 'description',
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
