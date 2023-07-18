<?php

namespace App\Models;

use App\RecordableModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dimensions extends RecordableModel
{
    protected $guarded = ['id'];

    public function changePrefix()
    {
        return "{$this->type}_";
    }

    public function getImperialWidthAttribute()
    {
        return $this->width / 2.54;
    }

    public function getImperialDepthAttribute()
    {
        return $this->depth / 2.54;
    }

    public function getImperialHeightAttribute()
    {
        return $this->height / 2.54;
    }

    public function getImperialGrossWeightAttribute()
    {
        return $this->gross_weight * 2.2046;
    }

    public function getImperialNetWeightAttribute()
    {
        return $this->net_weight * 2.2046;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withPending();
    }
}
