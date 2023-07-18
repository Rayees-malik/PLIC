<?php

namespace App\Http\Requests\MarketingAgreements;

use App\SteppedFormRequest;

class MarketingAgreementFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'send_to' => 'required',
            'account' => 'required',
            'account_other' => 'required_if:account,==,Other',

            'uploads' => 'nullable',

            'ship_to_number' => 'nullable',
            'retailer_invoice' => 'nullable',
            'comment' => 'nullable',
            'approval_email' => 'required',

            'tax_rate' => 'required|between:0,100',
        ];
    }
}
