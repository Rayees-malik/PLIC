<?php

namespace App\Http\Requests\Promos\Retailers;

use App\SteppedFormRequest;

class NaturesFarePromoLineItemFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'types.*' => 'nullable|array',
            'stores.*' => 'nullable',
            'notes.*' => 'nullable',
        ];
    }
}
