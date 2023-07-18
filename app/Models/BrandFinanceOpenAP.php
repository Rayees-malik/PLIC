<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandFinanceOpenAP extends Model
{
    public static function getBalance($id)
    {
        $total = 0;

        foreach (BrandFinanceOpenAP::byBrand($id)->get('balance') as $openAP) {
            $total += $openAP->balance;
        }

        return '$' . number_format($total, 2);
    }

    public function scopeByBrand($query, $id)
    {
        $query->where('brand_number', $id);
    }
}
