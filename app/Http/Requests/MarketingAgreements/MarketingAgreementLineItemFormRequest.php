<?php

namespace App\Http\Requests\MarketingAgreements;

use App\SteppedFormRequest;

class MarketingAgreementLineItemFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'lineitem_id.*' => 'nullable',
            'brand_id.*' => 'required',

            'activity.*' => 'required_with:brand_id',
            'promo_dates.*' => 'required_with:brand_id',
            'cost.*' => 'required_with:brand_id|between:0,10000',
            'mcb_amount.*' => 'nullable|numeric|between:0,10000',
        ];
    }
}
