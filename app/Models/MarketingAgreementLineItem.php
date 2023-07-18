<?php

namespace App\Models;

use Altek\Accountant\Contracts\Identifiable;
use App\RecordableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingAgreementLineItem extends RecordableModel
{
    use HasFactory;

    public $pivotOverrides = [
        'brand_id' => 'lineitem',
        'activity' => 'lineitem',
        'promo_dates' => 'lineitem',
        'cost' => 'lineitem',
        'mcb_amount' => 'lineitem',
    ];

    protected $guarded = ['id'];

    // Pull session from base MarketingAgreement model
    public static function getSessionRelationsKey()
    {
        return MarketingAgreement::getSessionRelationsKey();
    }

    public function supplyExtra(string $event, array $properties, ?Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            extract(MarketingAgreement::loadLookups());

            $extra = [
                'brand_id' => $properties['brand_id'] > 0 ? $brands->find($properties['brand_id'])->name : '',
            ];
        }

        return $extra;
    }

    public function marketingAgreement(): BelongsTo
    {
        return $this->belongsTo(MarketingAgreement::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
