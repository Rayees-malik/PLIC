<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailerListing extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['data' => 'json'];

    public static function deleteByRetailer($retailer)
    {
        $retailerId = is_object($retailer) ? $retailer->id : $retailer;
        RetailerListing::where('retailer_id', $retailerId)->delete();
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
