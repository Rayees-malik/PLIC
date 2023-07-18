<?php

namespace App\Datatables;

use Yajra\DataTables\Services\DataTable;

abstract class BaseDatatable extends DataTable
{
    protected string $dataTableVariable = 'datatable';

    public function html()
    {
        $builder = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters($this->getBuilderParameters());

        if (property_exists($this, 'customFilters') && $this->customFilters) {
            $builder = $builder->ajax([
                'data' => 'function(d) { window.addDatatableFilter(d); }',
            ]);
        }

        return $builder;
    }

    protected function getBuilderParameters(): array
    {
        $builderParams = [
            'order' => property_exists($this, 'orderBy') ? $this->orderBy : [[0, 'asc']],
            'pageLength' => property_exists($this, 'pageLength') ? $this->pageLength : 25,
        ];

        return array_merge(parent::getBuilderParameters(), $builderParams);
    }
}
