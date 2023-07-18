<?php

namespace App\Datatables;

use App\Models\InventoryRemoval;

class InventoryRemovalsDatatable extends BaseDatatable
{
    protected $orderBy = [[2, 'desc']];

    protected $customFilters = true;

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('created_at', function ($removal) {
                return $removal->created_at->toDateTimeString();
            })
            ->addColumn('action', function ($removal) {
                return '<a href="' . route('inventoryremovals.show', $removal->id) . '" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a>';
            })
            ->rawcolumns(['action'])
            ->filter(function ($query) {
                if (request()->submitted_by) {
                    $query->where('submitted_by', request()->submitted_by);
                }
                if (request()->warehouse || request()->brand_id) {
                    $query->whereHas('lineItems', function ($query) {
                        if (request()->warehouse) {
                            $query->where('warehouse', request()->warehouse);
                        }
                        if (request()->brand_id) {
                            $query->whereHas('product', function ($query) {
                                $query->where('brand_id', request()->brand_id);
                            });
                        }
                    });
                }
            }, true);
    }

    public function query()
    {
        return InventoryRemoval::withAccess()->with([
            'lineItems',
            'user' => function ($query) {
                $query->select('id', 'name');
            },
        ]);
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Name',
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
