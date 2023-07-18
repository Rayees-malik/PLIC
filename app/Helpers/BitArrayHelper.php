<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class BitArrayHelper
{
    public static function toString($value, $bitArray)
    {
        $value = array_sum(Arr::wrap($value));
        $values = [];
        foreach ($bitArray as $bit => $label) {
            if ($value & $bit) {
                $values[] = $label;
            }
        }

        return implode(', ', $values);
    }
}
