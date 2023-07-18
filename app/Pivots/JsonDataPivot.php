<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class JsonDataPivot extends Pivot
{
    public function getJsonDataAttribute()
    {
        return json_decode($this->data, true);
    }
}
