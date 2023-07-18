<?php

namespace App\Http\Requests\Signoffs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSignoffFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'in:approve,reject,save'],
            'signoff_step' => ['required', 'integer', 'min:1'],
            'signoff_comment' => ['nullable', 'string'],
        ];
    }
}
