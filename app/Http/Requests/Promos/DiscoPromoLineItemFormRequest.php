<?php

namespace App\Http\Requests\Promos;

use App\SteppedFormRequest;

class DiscoPromoLineItemFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'products' => 'required|array',
            'brand_discount' => 'nullable|array',
            'pl_discount' => 'nullable|array',

            'brand_discount.*' => 'nullable|numeric',
            'pl_discount.*' => 'nullable|numeric|between:0,100',
        ];
    }
}
