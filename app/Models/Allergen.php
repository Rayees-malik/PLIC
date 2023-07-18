<?php

namespace App\Models;

use App\Traits\HasPivotValue;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Allergen extends Model
{
    use SoftDeletes;
    use HasPivotValue;
    use Orderable;

    protected $fillable = ['name'];

    protected $pivotChangeType = 'pivot_data';

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('contains');
    }

    public function formatPivotData($value, $key)
    {
        if ($value == -1) {
            return 'Does Not Contain';
        } elseif ($value == 1) {
            return 'Contains';
        }

        return 'May Contain';
    }
}
