<?php

namespace App\Http\Requests\Vendors;

use App\SteppedFormRequest;

class PaymentStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'who_to_mcb' => 'required',
            'cheque_payable_to' => 'required',
            'payment_terms' => 'required',
            'special_shipping_requirements' => 'nullable',
            'return_policy' => 'required',
            'fob_purity_distribution_centres' => 'required|in:1,0',
            'consignment' => 'required|in:1,0',
        ];
    }
}
