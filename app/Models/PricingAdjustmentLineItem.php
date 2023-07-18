<?php

namespace App\Models;

use App\RecordableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PricingAdjustmentLineItem extends RecordableModel
{
    use HasFactory;

    public $pivotOverrides = [
        'total_discount' => 'lineitem',
        'total_mcb' => 'lineitem',
        'who_to_mcb' => 'lineitem',
    ];

    protected $guarded = ['id'];

    protected $with = ['item'];

    public function pricingAdjustment(): BelongsTo
    {
        return $this->belongsTo(PricingAdjustment::class);
    }

    public function item(): MorphTo
    {
        return $this->morphTo()->forPriceAdjustment();
    }
}
