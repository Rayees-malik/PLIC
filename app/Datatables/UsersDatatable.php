<?php

namespace App\Datatables;

use App\User;
use Bouncer;

class UsersDatatable extends BaseDatatable
{
    protected $customFilters = true;

    public function datatable($query)
    {
        $impersonateManager = app('impersonate');

        return datatables($query)
            ->editColumn('name', function ($user) {
                return '<a href="' . route('users.show', $user->id) . '" class="text-link">' . $user->name . '</a>';
            })
            ->editColumn('type', function ($user) {
                if (! $user->roles->count()) {
                    return 'Purity';
                }

                foreach ($user->roles as $role) {
                    foreach ($role->abilities as $ability) {
                        if ($ability->name == 'user.assign.broker') {
                            return 'Broker';
                        }
                    }
                }

                return 'Vendor';
            })
            ->addColumn('action', function ($user) use ($impersonateManager) {
                $viewButton = '<a href="' . route('users.show', $user->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
                $editButton = Bouncer::can('edit', User::class) ? '<a href="' . route('users.edit', $user->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>' : '';

                if (! $impersonateManager->isImpersonating() && $user->canBeImpersonated() && Bouncer::can('impersonate-users')) {
                    return $editButton . $viewButton . '<a href="' . route('impersonate', $user->id) . '" class="link-btn table-btn"><i class="material-icons">login</i>Impersonate</a>';
                }

                return $editButton . $viewButton;
            })
            ->rawcolumns(['name', 'action'])
            ->filter(function ($query) {
                if (request()->type) {
                    if (request()->type == 'purity') {
                        $query->whereDoesntHave('roles.abilities', function ($query) {
                            $query->whereIn('name', ['user.assign.broker', 'user.assign.vendor']);
                        });
                    } else {
                        $query->whereHas('roles.abilities', function ($query) {
                            $query->where('name', request()->type === 'vendor' ? 'user.assign.vendor' : 'user.assign.broker');
                        });
                    }
                }
            }, true);
    }

    public function query()
    {
        return User::withAccess()->with(['roles' => function ($query) {
            $query->whereHas('abilities', function ($query) {
                $query->whereIn('name', ['user.assign.broker', 'user.assign.vendor']);
            })->with(['abilities' => function ($query) {
                $query->whereIn('name', ['user.assign.broker', 'user.assign.vendor']);
            }]);
        }])->select('id', 'name', 'email');
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Name',
                'data' => 'name',
            ],
            [
                'title' => 'Email',
                'data' => 'email',
            ],
            [
                'title' => 'Type',
                'data' => 'type',
                'orderable' => false,
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
