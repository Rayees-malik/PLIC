<?php

namespace App\Http\Requests\Brands;

use App\SteppedFormRequest;

class BrandStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'vendor_id' => ['required', 'integer', 'min:1', 'exists:vendors,id'],
            'name' => 'required',
            'name_fr' => 'nullable',
            'made_in_canada' => 'nullable',
            'brand_number' => 'sometimes|required_with:signoff_form',
            'broker_proposal' => 'nullable',
            'currency_id' => ['required', 'integer', 'min:1', 'exists:currencies,id'],
            'website' => 'nullable',
            'phone' => 'nullable|phone:AUTO,CA,US',
            'description' => 'required',
            'description_fr' => 'required',
            'unpublished_new_listing_deal' => 'nullable',
            'unpublished_new_listing_deal_fr' => 'nullable',
        ];
    }
}
