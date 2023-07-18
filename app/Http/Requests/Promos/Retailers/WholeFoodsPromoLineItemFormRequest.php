<?php

namespace App\Http\Requests\Promos\Retailers;

use App\SteppedFormRequest;

class WholeFoodsPromoLineItemFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'mcb.*' => 'nullable|numeric',
            'scanback_percent.*' => 'nullable|numeric',
            'scanback_dollar.*' => 'nullable|numeric',
            'scanback_period.*' => 'nullable|in:A,B,BOTH,FLEX',
            'flyer.*' => 'nullable',
            'notes.*' => 'nullable',
        ];
    }
}
