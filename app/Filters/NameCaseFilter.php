<?php

namespace App\Filters;

use Elegant\Sanitizer\Contracts\Filter;

class NameCaseFilter implements Filter
{
    public function apply($value, $options = [])
    {
        // if empty string, skip
        if (empty($value)) {
            return $value;
        }

        $value = ucwords($value);
        $cleaned = preg_replace('/[^A-Za-z]/', '', $value);

        // If empty string after cleaning, just return original value with ucwords
        if (empty($cleaned)) {
            return $value;
        }

        $ucCount = strlen(preg_replace('/[^\p{Lu}]/u', '', $cleaned));

        if ($ucCount / strlen($cleaned) > 0.8) {
            return ucwords(strtolower($value));
        }

        return $value;
    }
}
