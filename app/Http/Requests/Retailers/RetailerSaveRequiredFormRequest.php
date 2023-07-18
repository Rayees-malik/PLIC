<?php

namespace App\Http\Requests\Retailers;

use App\SteppedFormRequest;

class RetailerSaveRequiredFormRequest extends SteppedFormRequest
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
            'name.*' => 'A valid name is required to save your progress.',
        ];
    }
}
