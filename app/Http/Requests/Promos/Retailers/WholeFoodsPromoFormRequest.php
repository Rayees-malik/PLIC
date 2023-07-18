<?php

namespace App\Http\Requests\Promos\Retailers;

use App\SteppedFormRequest;

class WholeFoodsPromoFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'authorized_by' => 'required',
            'phone' => 'required',
            'email' => 'required|email:filter',
            'promo_period' => 'required|in:A,B,BOTH,FLEX',
            'header_notes' => 'nullable',
        ];
    }

    public function filters()
    {
        return [
            'authorized_by' => 'trim|capitalize',
            'email' => 'trim|lowercase',
        ];
    }
}
