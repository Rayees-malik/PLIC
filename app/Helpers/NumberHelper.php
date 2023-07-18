<?php

namespace App\Helpers;

class NumberHelper
{
    public static function toAccountingDollar($num)
    {
        return $num >= 0 ? '$' . number_format($num, 2) : '($' . number_format(abs($num), 2) . ')';
    }
}
