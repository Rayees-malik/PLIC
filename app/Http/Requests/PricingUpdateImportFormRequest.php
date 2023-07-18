<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PricingUpdateImportFormRequest extends FormRequest
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
            'vrs' => ['required', 'exists:users,id'],
            'data' => ['file', 'required'],
        ];
    }

    public function messages()
    {
        return [
            'vrs.required' => 'You must select a VRS',
            'vrs.exists' => 'The VRS you selected does not exist',
            'data.required' => 'You must select a file',
        ];
    }
}
