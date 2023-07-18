<?php

namespace App\Models;

use App\Traits\HasPivotValue;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributor extends Model
{
    use SoftDeletes;
    use Orderable;
    use HasPivotValue;

    protected $protected = ['id'];

    protected $pivotChangeType = 'concat';
}
