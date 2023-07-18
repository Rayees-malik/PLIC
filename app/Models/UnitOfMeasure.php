<?php

namespace App\Models;

use App\Traits\HasPivotValue;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitOfMeasure extends Model
{
    use SoftDeletes;
    use Orderable;
    use HasPivotValue;

    protected const ORDER_BY = ['unit' => 'asc'];

    protected $table = 'unit_of_measure';

    protected $fillable = ['unit', 'description'];
}
