<?php

namespace App\Http\Requests\ProductDelistRequests;

use App\SteppedFormRequest;

class ProductDelistRequestFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'reason' => 'required',
        ];
    }
}
