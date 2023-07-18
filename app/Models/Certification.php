<?php

namespace App\Models;

use App\Traits\HasPivotValue;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certification extends Model
{
    use SoftDeletes;
    use HasPivotValue;
    use Orderable;

    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
