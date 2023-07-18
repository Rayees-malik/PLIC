<?php

namespace App\Http\Requests\Promos;

use App\SteppedFormRequest;

class PromoFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'brand_id' => 'sometimes|required',
            'period_id' => 'sometimes|required',
            'dollar_discount' => 'required',
            'oi' => 'nullable',
            'oi_period_dates' => 'sometimes|in:0,1',
        ];
    }
}
