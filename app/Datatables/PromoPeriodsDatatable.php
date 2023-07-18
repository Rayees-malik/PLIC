<?php

namespace App\Datatables;

use App\Models\PromoPeriod;

class PromoPeriodsDatatable extends BaseDatatable
{
    protected $orderBy = [[1, 'asc']];

    protected $customFilters = true;

    public function __construct(protected $periods, $reverseOrderBy = false)
    {
        if ($reverseOrderBy) {
            $this->orderBy[0][1] = 'desc';
        }
    }

    public function datatable($query)
    {
        return datatables($query)
            ->setRowId('id')
            ->editColumn('start_date', function ($period) {
                return $period->start_date->toFormattedDateString();
            })
            ->editColumn('end_date', function ($period) {
                return $period->end_date->toFormattedDateString();
            })
            ->editColumn('type', function ($period) {
                return PromoPeriod::TYPES[$period->type];
            })
            ->editColumn('active', function ($period) {
                $checked = $period->active ? ' checked' : '';

                return '<div class="checkbox-wrap">
                          <label class="checkbox">
                              <input type="checkbox" class="js-period-active" data-id="' . $period->id . '"' . $checked . '>
                              <span class="checkbox-checkmark"></span>
                              <span class="checkbox-label"></span>
                          </label>
                      </div>';
            })
            ->addColumn('action', function ($period) {
                return '<a href="' . route('promos.periods.edit', $period->id) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>';
            })
            ->rawColumns(['active', 'action'])
            ->filter(function ($query) {
                if (request()->type) {
                    $query->where('type', request()->type);
                }
            }, true);
    }

    public function query()
    {
        return $this->periods;
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Period Name',
                'data' => 'name',
            ],
            [
                'title' => 'Start Date',
                'data' => 'start_date',
                'searchable' => false,
            ],
            [
                'title' => 'End Date',
                'data' => 'end_date',
                'searchable' => false,
            ],
            [
                'title' => 'Type',
                'data' => 'type',
                'orderable' => false,
                'searchable' => false,
            ],
            [
                'title' => 'Active',
                'data' => 'active',
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
