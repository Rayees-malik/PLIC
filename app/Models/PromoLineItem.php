<?php

namespace App\Models;

use App\RecordableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class PromoLineItem extends RecordableModel
{
    use HasFactory;

    public $pivotOverrides = [
        'brand_discount' => 'promo_lineitem',
        'pl_discount' => 'promo_lineitem',
        'data' => 'promo_lineitem_data',
    ];

    protected $guarded = ['id'];

    protected $casts = ['data' => 'json'];

    // Pull session from base Promo model
    public static function getSessionRelationsKey()
    {
        return Promo::getSessionRelationsKey();
    }

    public function supplyExtra(string $event, array $properties, ?\Altek\Accountant\Contracts\Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            if (Arr::has($properties, 'oi')) {
                $extra['oi'] = $properties['oi'] == '1' ? 'Yes' : 'No';
            }
        }

        return $extra;
    }

    public function getCustomFieldPivotType($field, $ownerId = null)
    {
        $promoConfig = $ownerId ? Arr::get(Config::get('retailer-promos'), $ownerId) : null;

        return Arr::get($promoConfig, "lineItemFields.{$field}.pivotType") ?? 'pivot_data';
    }

    public function getSummaryArray()
    {
        return [
            'PL #' => $this->product->stock_id,
            'Vendor Number' => $this->product->brand->brand_number,
            'Product' => $this->product->name,
            'MCB / OI' => $this->oi === 1 ? 'OI' : 'MCB',
            'MCB / OI Amount' => $this->brand_discount,
            'PL Discount' => $this->pl_discount,
            'Total Discount' => $this->brand_discount + $this->pl_discount,
            'Discount Dates' => $this->promo->oi_period_dates,
        ];
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class)->allStates();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withPending();
    }
}
