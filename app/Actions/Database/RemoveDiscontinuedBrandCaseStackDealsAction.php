<?php

namespace App\Actions\Database;

use App\Helpers\StatusHelper;
use App\Models\CaseStackDeal;

class RemoveDiscontinuedBrandCaseStackDealsAction
{
    public function execute()
    {
        CaseStackDeal::whereHas('brand', function ($query) {
            $query->where('status', StatusHelper::DISCONTINUED);
        })->whereHas('period', function ($query) {
            $query->where('start_date', '>', now());
        })->delete();
    }
}
