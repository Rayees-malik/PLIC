<?php

namespace App\Http\Requests\Brands;

use App\SteppedFormRequest;

class DistributionStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'contract_exclusive' => 'required|in:0,1',
            'no_other_distributors' => 'required|in:0,1',
            'also_distributed_by' => 'nullable',
            'allows_amazon_resale' => 'required|in:0,1',
            'map_pricing' => 'required|in:0,1',
            'business_partner_program' => 'required|in:0,1',
            'in_house_brand' => 'required|in:0,1',
        ];
    }
}
