<?php

namespace App\Http\Requests\Products;

use App\SteppedFormRequest;
use Arr;
use Illuminate\Validation\Rule;

class PricingStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'not_for_resale' => 'nullable',
            'unit_cost' => [
                'nullable',
                Rule::requiredIf(function () {
                    return ! Arr::has(request()->input(), 'price_change_reason') || request()->input('price_change_reason');
                }),
                'numeric',
                'between:0,99999',
            ],
            'landed_cost' => 'sometimes|required_with:unit_cost|numeric|between:0,99999',
            'wholesale_price' => 'sometimes|required_with:unit_cost|nullable|numeric|between:0,99999',
            'suggested_retail_price' => 'sometimes|required_with:unit_cost|nullable|numeric|between:0,99999',
            'price_change_reason' => 'sometimes|required_with:unit_cost',
            'price_change_date' => 'sometimes|required_with:unit_cost',
            'extra_addon_percent' => 'nullable|numeric|between:0,99999',
            'temp_edlp' => 'nullable|numeric|between:0,99999',
            'temp_duty' => 'nullable|numeric|between:0,99999',
            'available_ship_date' => 'nullable|date',
            'minimum_order_units' => 'nullable|numeric|between:0,99999',
        ];
    }
}
