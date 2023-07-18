<?php

namespace App\Http\Requests\Vendors;

use App\SteppedFormRequest;

class VendorStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'address2' => 'nullable',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'country_id' => 'required|integer',
        ];
    }
}
