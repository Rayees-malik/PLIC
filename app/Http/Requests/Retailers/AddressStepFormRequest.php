<?php

namespace App\Http\Requests\Retailers;

use App\SteppedFormRequest;

class AddressStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'address' => 'nullable',
            'address2' => 'nullable',
            'city' => 'nullable',
            'province' => 'nullable',
            'postal_code' => 'nullable',
            'country' => 'nullable',
        ];
    }
}
