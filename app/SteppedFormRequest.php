<?php

namespace App;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Contracts\Validation\Validator;

class SteppedFormRequest extends BaseFormRequest
{
    public function partialValidated()
    {
        return $this->validator->partialValidated();
    }

    protected function failedValidation(Validator $validator)
    {
        // ignore failed validation
    }
}
