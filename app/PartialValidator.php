<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use stdClass;

class PartialValidator extends Validator
{
    public function partialValidated()
    {
        $retValue = new stdClass;
        $retValue->validated = [];

        $missingValue = Str::random(10);
        foreach (array_keys($this->getRules()) as $key) {
            // Don't return invalid data
            if ($this->errors()->has($key)) {
                continue;
            }

            $value = data_get($this->getData(), $key, $missingValue);
            if ($value !== $missingValue) {
                Arr::set($retValue->validated, $key, $value);
            }
        }

        $retValue->errors = $this->invalid() ? $this->errors() : new MessageBag;

        return $retValue;
    }
}
