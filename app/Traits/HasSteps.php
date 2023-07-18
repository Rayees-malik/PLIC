<?php

namespace App\Traits;

use App\SteppedViewErrorBag;
use Illuminate\Support\Arr;

trait HasSteps
{
    public static function stepperUpdate()
    {
        $class = static::class;
        throw new Exception("{$class} is missing stepperUpdate() implementation");
    }

    public function getStepsAttribute()
    {
        return [
            'general' => [
                'display' => 'Contacts',
            ],
        ];
    }

    public function stepErrors()
    {
        $errors = new SteppedViewErrorBag;
        foreach ($this->steps as $key => $step) {
            $request = Arr::get($step, 'formRequest');
            if ($request && class_exists($request)) {
                $errors->put($key, app($request)->partialValidated()->errors);
            }
        }

        return $errors;
    }
}
