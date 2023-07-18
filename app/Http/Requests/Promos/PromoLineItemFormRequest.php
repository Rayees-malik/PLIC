<?php

namespace App\Http\Requests\Promos;

use App\SteppedFormRequest;
use Illuminate\Validation\Rule;

class PromoLineItemFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'products' => 'required|array',
            'lineitem_oi' => 'nullable|array',
            'brand_discount' => 'nullable|array',
            'pl_discount' => 'nullable|array',

            'lineitem_oi.*' => 'nullable|in:0,1',
            'brand_discount.*' => [
                'nullable',
                Rule::when(
                    function () {
                        return request()->exists('dollar_discount') && request()->input('dollar_discount') == 1;
                    },
                    'numeric',
                    'numeric|between:0,100',
                ),
            ],
            'pl_discount.*' => 'nullable|numeric|between:0,100',
        ];
    }
}
