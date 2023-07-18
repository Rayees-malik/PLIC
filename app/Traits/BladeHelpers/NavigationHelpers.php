<?php

namespace App\Traits\BladeHelpers;

trait NavigationHelpers
{
    public static function backOr($route)
    {
        $previousUrl = url()->previous();

        return $previousUrl == url()->current() ? $route : $previousUrl;
    }
}
