<?php

namespace App\Http\Requests\PricingAdjustments;

use App\SteppedFormRequest;

class PricingAdjustmentFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'accounts' => 'required|array',

            'uploads' => 'nullable',

            'ongoing' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'dollar_discount' => 'required',
            'dollar_mcb' => 'required',
            'bpp' => 'required',
            'shared_line' => 'required',
            'comment' => 'nullable',
            'notes' => 'nullable',
        ];
    }
}
