<?php

namespace App\Http\Requests\Brands;

use App\SteppedFormRequest;

class AdministrativeStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'status' => 'required',
            'hide_from_exports' => 'nullable',
            'education_portal' => 'nullable',
            'catalogue_notice' => 'nullable',
            'catalogue_notice_fr' => 'nullable',
            'category_code' => 'nullable',
            'as400_category' => 'nullable',
            'finance_brand_number' => 'nullable',

            // mcb
            'nutrition_house' => 'required|in:0,1',
            'nutrition_house_payment_type' => 'required',
            'nutrition_house_payment' => 'bail|required|numeric|between:0,100',
            'nutrition_house_percentage' => 'bail|required_if:nutrition_house_payment_type,==,purity|nullable|numeric|between:0,100',
            'nutrition_house_purity_percentage' => 'bail|required_if:nutrition_house_payment_type,==,purity|nullable|numeric|between:0,100',
            'health_first' => 'required|in:0,1',
            'health_first_payment_type' => 'required',
            'health_first_payment' => 'bail|required|numeric|between:0,100',
            'health_first_percentage' => 'bail|required_if:health_first_payment_type,==,purity|nullable|numeric|between:0,100',
            'health_first_purity_percentage' => 'bail|required_if:health_first_payment_type,==,purity|nullable|numeric|between:0,100',
            'default_pl_discount' => 'nullable|numeric|between:0,100',
            'allow_oi' => 'nullable',
        ];
    }
}
