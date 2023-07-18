<?php

namespace App\Models;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FutureLandedCost extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Orderable;

    protected const ORDER_BY = ['change_date' => 'asc'];

    protected $guarded = ['id'];

    protected $casts = ['change_date' => 'date'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withPending();
    }
}
