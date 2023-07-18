<?php

namespace App\Models;

use App\Scopes\PromoPeriodScope;
use App\Traits\Orderable;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PromoPeriod extends Model
{
    use Orderable;
    use HasFactory;

    const ORDER_BY = ['start_date' => 'desc'];

    const TYPES = [
        '0' => 'Catalogue',
        '1' => 'Layered',
        '2' => 'Unlayered',
    ];

    const CATALOGUE_TYPE = 0;

    const LAYERED_TYPE = 1;

    const UNLAYERED_TYPE = 2;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PromoPeriodScope);
    }

    public static function generatePeriods($owner = null)
    {
        $year = Carbon::now()->add(CarbonInterval::year())->format('Y');

        for ($i = 1; $i <= 12; $i++) {
            $date = DateTime::createFromFormat('!m', $i);
            $periodName = "{$date->format('F')} {$year}";
            if (PromoPeriod::byOwner($owner)->where('name', $periodName)->count() == 0) {
                PromoPeriod::create([
                    'owner_type' => $owner ? get_class($owner) : null,
                    'owner_id' => $owner ? $owner->id : null,
                    'name' => $periodName,
                    'start_date' => new Carbon("first day of {$periodName}"),
                    'end_date' => new Carbon("last day of {$periodName}"),
                    'type' => static::CATALOGUE_TYPE,
                    'active' => true,
                ]);
            }
        }
    }

    public function getDateRangeAttribute()
    {
        return "{$this->start_date->toFormattedDateString()} - {$this->end_date->toFormattedDateString()}";
    }

    public function getCaseStackDeal($brand)
    {
        return $this->caseStackDeals->firstWhere('brand_id', is_object($brand) ? $brand->id : $brand);
    }

    public function basePeriod(): BelongsTo
    {
        return $this->belongsTo(PromoPeriod::class, 'base_period_id');
    }

    public function promos(): HasMany
    {
        return $this->hasMany(Promo::class, 'period_id');
    }

    public function caseStackDeals(): HasMany
    {
        return $this->hasMany(CaseStackDeal::class, 'period_id');
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
