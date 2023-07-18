<?php

namespace App\Models;

use App\RecordableModel;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStackDeal extends RecordableModel
{
    use Orderable;
    use HasFactory;

    const ORDER_BY = ['period_id' => 'desc'];

    protected $guarded = ['id'];

    public function scopeByBrand($query, $brand)
    {
        $brandId = is_object($brand) ? $brand->id : $brand;

        return $query->where('brand_id', $brandId);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PromoPeriod::class);
    }
}
