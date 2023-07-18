<?php

namespace App\Traits;

trait HasUserInput
{
    public static function hasUserInput($array)
    {
        foreach (static::$ignoreFromUserInput as $ignored) {
            $array[$ignored] = null;
        }

        return ! empty(array_filter($array));
    }
}
