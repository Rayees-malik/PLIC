<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandFinancePOReceived extends Model
{
    public function scopeByBrand($query, $id)
    {
        $query->where('brand_number', $id);
    }
}
