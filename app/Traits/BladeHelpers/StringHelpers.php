<?php

namespace App\Traits\BladeHelpers;

trait StringHelpers
{
    public static function prefixIfValue($value, $prefix)
    {
        return $value ? "{$prefix}{$value}" : null;
    }

    public static function suffixIfValue($value, $suffix)
    {
        return $value ? "{$value}{$suffix}" : null;
    }
}
