<?php

namespace App\Helpers;

class FormHelper
{
    public static function parseControlArray($data, $prefix = '')
    {
        $models = [];

        foreach ($data as $field => $rows) {
            $cleanKey = str_replace($prefix, '', $field);
            foreach ($rows as $index => $value) {
                $models[$index][$cleanKey] = $value;
            }
        }

        return $models;
    }
}
