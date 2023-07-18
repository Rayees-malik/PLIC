<?php

namespace App\Actions\Database;

use App\Helpers\StatusHelper;
use App\Models\PromoLineItem;

class RemoveDiscontinuedProductPromosAction
{
    public function execute()
    {
        $promoLineItems = PromoLineItem::whereHas('product', function ($query) {
            $query->where('status', StatusHelper::DISCONTINUED);
        })->whereHas('promo', function ($query) {
            $query->whereHas('period', function ($query) {
                $query->where('start_date', '>', now());
            });
        })->get();

        $promoLineItems->each(function ($promoLineItem) {
            $promoLineItem->delete();
        });
    }
}
