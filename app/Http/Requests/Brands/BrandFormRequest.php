<?php

namespace App\Http\Requests\Brands;

use App\SteppedFormRequest;

class BrandFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            // Brand
            'vendor_id' => ['required', 'integer', 'min:1', 'exists:vendors,id'],
            'name' => 'required',
            'name_fr' => 'nullable',
            'made_in_canada' => 'nullable',
            'brand_number' => 'sometimes|required_with:signoff_form',
            'finance_brand_number' => 'nullable',
            'broker_proposal' => 'nullable',
            'currency_id' => ['required', 'integer', 'min:1', 'exists:currencies,id'],
            'website' => 'nullable',
            'phone' => 'nullable|phone:AUTO,CA,US',
            'description' => 'required',
            'description_fr' => 'required',
            'unpublished_new_listing_deal' => 'nullable',
            'unpublished_new_listing_deal_fr' => 'nullable',

            // Distribution
            'contract_exclusive' => 'required|in:0,1',
            'no_other_distributors' => 'required|in:0,1',
            'also_distributed_by' => 'nullable',
            'allows_amazon_resale' => 'required|in:0,1',
            'map_pricing' => 'required|in:0,1',
            'business_partner_program' => 'required|in:0,1',
            'in_house_brand' => 'required|in:0,1',

            // MCB
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

            // Purchasing
            'purchasing_specialist_id' => 'sometimes|integer',
            'vendor_relations_specialist_id' => 'sometimes|integer',
            'minimum_order_type' => 'in:$,#',
            'minimum_order_quantity' => 'numeric',
            'shipping_lead_time' => 'nullable',
            'product_availability' => 'required|date',

            // Admin
            'status' => 'required',
            'hide_from_exports' => 'nullable',
            'education_portal' => 'nullable',
            'catalogue_notice' => 'nullable',
            'catalogue_notice_fr' => 'nullable',
            'category_code' => 'nullable',
            'as400_category' => 'nullable',
        ];
    }

    public function filters()
    {
        return [
            // Brand
            'name' => 'trim',
            'name_fr' => 'trim',
            'description' => 'trim',
            'description_fr' => 'trim',
            'broker_proposal' => 'trim|name_case',
            'website' => 'trim|lowercase',

            // Distribution
            'also_distributed_by' => 'trim|name_case',
        ];
    }
}
