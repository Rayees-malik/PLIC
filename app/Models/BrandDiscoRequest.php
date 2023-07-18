<?php

namespace App\Models;

use App\Helpers\StatusHelper;
use App\RecordableModel;
use App\Traits\Orderable;
use App\Traits\RequiresSignoff;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandDiscoRequest extends RecordableModel
{
    use RequiresSignoff;
    use Orderable;
    use HasFactory;

    protected $guarded = ['id'];

    protected $eager_relations = ['user', 'brand'];

    public function onSignoffComplete($signoff)
    {
        Brand::disableRecording();
        $this->brand->status = StatusHelper::DISCONTINUED;
        $this->brand->save();
        Brand::enableRecording();
    }

    public function getSummaryArray($signoff)
    {
        return [
            'Brand' => $this->name,
            'Submitted By' => $this->user->name,
            'Disco Reason' => $this->reason,
            'Plan to Recoup $' => $this->recoup_plan,
            'A/P Owed' => $this->ap_owed,
            'YTD Sales' => $this->ytd_sales,
            'YTD Margin' => $this->ytd_margin,
            'Previous Year Sales' => $this->previous_year_sales,
            'Previous Year Margin' => $this->previous_year_margin,
            'Value of On-Hand Inventory' => $this->inventory_value,
        ];
    }

    public function getRoutePrefixAttribute()
    {
        return 'branddiscos';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id')->withTrashed();
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
