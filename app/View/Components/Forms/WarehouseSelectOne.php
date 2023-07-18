<?php

namespace App\View\Components\Forms;

use App\Models\Warehouse;

class WarehouseSelectOne extends SelectOne
{
    public function __construct()
    {
        $this->options = Warehouse::query()
            ->select('id', 'name', 'number')
            ->where('name', 'not like', 'QC%')
            ->ordered()
            ->get()
            ->mapWithKeys(function ($warehouse) {
                return [$warehouse->id => $warehouse->number . ' - ' . $warehouse->name];
            })
            ->toArray();
    }
}
