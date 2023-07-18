<?php

namespace App\Actions\Database;

use App\Helpers\StatusHelper;
use App\Models\Promo;

class RemoveDiscontinuedBrandPromosAction
{
    public function execute()
    {
        $promos = Promo::with('lineItems')
            ->whereHas('brand', function ($query) {
                $query->where('status', StatusHelper::DISCONTINUED);
            })->whereHas('period', function ($query) {
                $query->where('start_date', '>', now());
            })->each(function ($promo) {
                $promo->lineItems()->delete();
                $promo->delete();
            });
    }
}
