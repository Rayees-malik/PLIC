<?php

namespace App\Http\Requests\Signoffs;

use Illuminate\Foundation\Http\FormRequest;

class FinanceBulkSignoffFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'signoff_comment' => 'nullable',
            'signoff_id' => 'array',
            'selected' => 'array',
        ];
    }
}
