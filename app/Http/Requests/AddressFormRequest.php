<?php

namespace App\Http\Requests;

use App\SteppedFormRequest;

class AddressFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'address' => 'required',
            'address2' => 'nullable',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'country_id' => 'required|integer',
        ];
    }

    public function filters()
    {
        return [
            'address' => 'capitalize',
            'address2' => 'capitalize',
            'city' => 'capitalize',
            'province' => 'capitalize',
            'postal_code' => 'uppercase',
        ];
    }
}
