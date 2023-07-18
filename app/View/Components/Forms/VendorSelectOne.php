<?php

namespace App\View\Components\Forms;

use App\Models\Vendor;

class VendorSelectOne extends SelectOne
{
    public function __construct()
    {
        $this->options = Vendor::query()
            ->withPending()
            ->select('id', 'name')
            ->ordered()
            ->pluck('name', 'id')
            ->toArray();
    }
}
