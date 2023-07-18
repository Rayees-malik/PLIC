<?php

namespace App\Helpers;

class ExcelHelper
{
    public static function indexToColumn($index)
    {
        for ($col = ''; $index >= 0; $index = intval($index / 26) - 1) {
            $col = chr($index % 26 + 0x41) . $col;
        }

        return $col;
    }
}
