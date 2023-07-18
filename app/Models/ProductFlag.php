<?php

namespace App\Models;

use App\Traits\HasPivotValue;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFlag extends Model
{
    use SoftDeletes;
    use Orderable;
    use HasPivotValue;

    protected $guarded = ['id'];

    protected $pivotChangeType = 'concat';

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
