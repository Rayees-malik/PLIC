<?php

namespace App\Filters;

use Elegant\Sanitizer\Contracts\Filter;
use Illuminate\Support\Arr;

class SumArrayFilter implements Filter
{
    public function apply($value, $options = [])
    {
        return array_sum(Arr::wrap($value));
    }
}
