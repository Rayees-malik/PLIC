<?php

namespace App\Http\Requests\Retailers;

use App\SteppedFormRequest;

class RetailerRelationsFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'distributors' => 'nullable|array',
        ];
    }
}
