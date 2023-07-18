<?php

namespace App\Http\Requests\PricingAdjustments;

use App\SteppedFormRequest;

class PricingAdjustmentLineItemFormRequest extends SteppedFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'lineitem_id.*' => 'nullable',

            'morph_id.*' => 'required',
            'morph_type.*' => 'required',

            'total_discount.*' => 'required|between:0,100',
            'total_mcb.*' => ['required', 'regex:/^(?:[1-9]\d+|\d)(?:\.\d{1,2})?$/i'],
            'who_to_mcb.*' => 'required_unless:total_mcb.*,0',
        ];
    }
}
