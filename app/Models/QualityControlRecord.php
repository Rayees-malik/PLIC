<?php

namespace App\Models;

use App\RecordableModel;
use App\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class QualityControlRecord extends RecordableModel implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        'matches_written_specification' => 'boolean',
    ];

    protected $guarded = [];

    protected function unitsTaken(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return ($attributes['number_units_sent_for_testing'] ?? 0) +
                ($attributes['number_units_retained'] ?? 0) +
                ($attributes['number_units_for_stability'] ?? 0);
            },
        );
    }

    /**
     * Get the product associated with the QualityControlRecord
     *
     * @return \Illuminate\Database\Eloquent\Relations\Belongs
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the vendor that owns the QualityControlRecord
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the user that owns the QualityControlRecord
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): MediaCollection
    {
        return $this->getMedia('qc-files');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
