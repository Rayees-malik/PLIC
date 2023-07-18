<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BrandFinance extends Model
{
    protected $guarded = ['id'];

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_number')->allStates();
    }

    public function scopeByBrand($query, $id)
    {
        $query->where('brand_number', $id)->get()->groupBy('voucher_date');
    }

    public function scopeByBrandAndType($query, $id, $type)
    {
        $query->where([
            'brand_number' => $id,
            'type' => $type,
        ])->get()->groupBy('voucher_date');
    }

    public function scopeByBrandGrouped($query, $id)
    {
        $query->where('brand_number', $id)->get('voucher_date')->groupBy(function ($val) {
            return Carbon::parse($val->voucher_date)->format('Y');
        });
    }

    public function scopeByBrandAndTypeGrouped($query, $type, $id)
    {
        $query->where([
            'brand_number' => $id,
            'type' => $type,
        ])->get('voucher_date')->groupBy(function ($val) {
            return Carbon::parse($val->voucher_date)->format('Y');
        });
    }
}
