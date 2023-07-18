<?php

namespace App\Models;

use App\RecordableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegulatoryInfo extends RecordableModel
{
    use HasFactory;

    protected $table = 'regulatory_info';

    protected $guarded = ['id'];

    protected $clear_on_clone = ['npn'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withPending();
    }
}
