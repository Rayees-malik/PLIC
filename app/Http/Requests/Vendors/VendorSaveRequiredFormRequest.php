<?php

namespace App\Http\Requests\Vendors;

use App\SteppedFormRequest;

class VendorSaveRequiredFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required to save your progress.',
        ];
    }
}
