<?php

namespace App\Http\Requests\Brands;

use App\SteppedFormRequest;

class PurchasingStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'purchasing_specialist_id' => 'sometimes|integer',
            'vendor_relations_specialist_id' => 'sometimes|integer',
            'minimum_order_type' => 'in:$,#',
            'minimum_order_quantity' => 'numeric',
            'shipping_lead_time' => 'nullable',
            'product_availability' => 'required|date',
        ];
    }
}
