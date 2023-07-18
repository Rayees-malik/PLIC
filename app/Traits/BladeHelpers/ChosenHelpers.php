<?php

namespace App\Traits\BladeHelpers;

use Illuminate\Support\Arr;

trait ChosenHelpers
{
    public static function initChosenSelect($classes)
    {
        $rootPath = dirname(__FILE__) . '/chosen-config/';

        $classes = Arr::wrap($classes);
        $customClasses = Arr::isAssoc($classes);

        $js = '';
        foreach ($classes as $key => $class) {
            if (file_exists("{$rootPath}{$class}.js")) {
                $js .= str_replace('$class', $customClasses ? $key : $class, file_get_contents("{$rootPath}{$class}.js", false));
            }
        }

        return str_replace('$wrapper', $js, file_get_contents("{$rootPath}wrapper.js", false));
    }
}
