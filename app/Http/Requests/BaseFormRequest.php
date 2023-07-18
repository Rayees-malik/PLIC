<?php

namespace App\Http\Requests;

use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    use SanitizesInput;

    public function validateResolved()
    {
        $this->sanitize();
        parent::validateResolved();
    }

    public function authorize()
    {
        return true;
    }
}
