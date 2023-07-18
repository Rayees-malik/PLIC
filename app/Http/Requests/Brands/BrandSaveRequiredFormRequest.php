<?php

namespace App\Http\Requests\Brands;

use App\SteppedFormRequest;

class BrandSaveRequiredFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'vendor_id' => ['required', 'integer', 'min:1', 'exists:vendors,id'],
            'name' => 'required',
            'description' => 'required',
            'description_fr' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'vendor_id.required' => 'A parent vendor is required to save your progress.',
            'name.required' => 'Name is required to save your progress.',
            'description.required' => 'Description is required to save your progress.',
            'description_fr.required' => 'French description is required to save your progress.',
        ];
    }
}
