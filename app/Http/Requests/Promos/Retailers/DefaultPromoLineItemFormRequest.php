<?php

namespace App\Http\Requests\Promos\Retailers;

use App\SteppedFormRequest;

class DefaultPromoLineItemFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'ad_type.*' => 'nullable',
            'ad_cost.*' => 'nullable|numeric',
            'demo.*' => 'nullable',
            'notes.*' => 'nullable',
        ];
    }
}
