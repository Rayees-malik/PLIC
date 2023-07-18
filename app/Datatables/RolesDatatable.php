<?php

namespace App\Datatables;

use Bouncer;

class RolesDatatable extends BaseDatatable
{
    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('title', function ($role) {
                return '<a href="' . route('roles.show', $role->name) . '" class="text-link">' . $role->title . '</a><small class="subnote">' . $role->description . '</small>';
            })
            ->addColumn('abilities', function ($role) {
                $MAX_PERMISSIONS = 4;
                if ($role->abilities->count() > $MAX_PERMISSIONS) {
                    return ucwords($role->abilities->slice(0, $MAX_PERMISSIONS - 1)->pluck('title')->implode(', ') . ', ' .
                        '<a class="show-more-permissions" href="javascript: void(0);" onclick="$(this).hide().next(\'span\').show();">' .
                        ($role->abilities->count() - ($MAX_PERMISSIONS - 1)) . ' ' . 'more</a>' .
                        '<span style="display: none;">' . ucwords($role->abilities->splice($MAX_PERMISSIONS - 1)->pluck('title')->implode(', ')) . '</span>');
                } else {
                    return ucwords($role->abilities->pluck('title')->implode(', '));
                }
            })
            ->addColumn('view', function ($role) {
                $viewButton = '<a href="' . route('roles.show', $role->name) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';

                $editButton = '';
                if (Bouncer::can('user.roles.edit') && $role->name !== 'admin' && $role->name !== 'super-admin') {
                    $editButton = '<a href="' . route('roles.edit', $role->name) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>';
                }

                return $editButton . $viewButton;
            })
            ->rawcolumns(['abilities', 'title', 'view'])
            ->filter(function ($query) {
                if (request()->ability_id) {
                    $query->whereHas('abilities', function ($query) {
                        $query->where('abilities.id', request()->ability_id);
                    });
                }
            }, true);
    }

    public function query()
    {
        return Bouncer::role()
            ->with(['abilities' => function ($query) {
                $query->wherePivot('forbidden', false)->select('title');
            }])
            ->select(['id', 'name', 'title'])
            ->withCount('users');
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Role',
                'data' => 'title',
            ],
            [
                'title' => 'Abilities',
                'data' => 'abilities',
                'orderable' => false,
                'searchable' => true,
                'exportable' => false,
                'printable' => false,
            ],
            [
                'title' => '# Users',
                'data' => 'users_count',
                'searchable' => false,
            ],
            [
                'title' => '',
                'data' => 'view',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ],
        ];
    }
}
