<?php

namespace App\Http\Requests\Products;

use App\Rules\ProductCategoryHas;
use App\Rules\ValidUPC;
use App\SteppedFormRequest;
use Illuminate\Validation\Rule;

class PackagingStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'purity_sell_by_unit' => 'required|integer',
            'retailer_sell_by_unit' => 'required',

            // Single
            'upc' => ['bail', 'sometimes', 'required_unless:not_for_resale,1', 'nullable', new ValidUPC],
            'size' => 'required|numeric|between:0,10000',
            'uom_id' => 'required|integer|between:0,10000',
            'unit_width' => 'required|numeric|between:0,10000',
            'unit_depth' => 'required|numeric|between:0,10000',
            'unit_height' => 'required|numeric|between:0,10000',
            'unit_gross_weight' => 'required|numeric|between:0,10000',
            'unit_net_weight' => ['sometimes', new ProductCategoryHas('HAS_NET_WEIGHT')],

            // Inner
            'inner_upc' => [
                'bail',
                'sometimes',
                Rule::requiredIf(function () {
                    return request()->input('purity_sell_by_unit') == 2 && request()->input('not_for_resale') != 1;
                }),
                'nullable',
                new ValidUPC,
            ],
            'inner_width' => 'bail|required_if:purity_sell_by_unit,2|required_with:inner_upc|nullable|numeric|between:0,10000',
            'inner_depth' => 'bail|required_if:purity_sell_by_unit,2|required_with:inner_upc|nullable|numeric|between:0,10000',
            'inner_height' => 'bail|required_if:purity_sell_by_unit,2|required_with:inner_upc|nullable|numeric|between:0,10000',
            'inner_gross_weight' => 'bail|required_if:purity_sell_by_unit,2|required_with:inner_upc|nullable|numeric|between:0,10000',
            'inner_units' => 'bail|required_if:purity_sell_by_unit,2|required_with:inner_upc|nullable|numeric|between:0,10000',

            // Master
            'master_upc' => [
                'bail',
                'sometimes',
                Rule::requiredIf(function () {
                    return request()->input('purity_sell_by_unit') == 4 && request()->input('not_for_resale') != 1;
                }),
                'nullable',
                new ValidUPC,
            ],
            'master_width' => 'required|numeric|between:0,10000',
            'master_depth' => 'required|numeric|between:0,10000',
            'master_height' => 'required|numeric|between:0,10000',
            'master_gross_weight' => 'required|numeric|between:0,10000',
            'master_units' => 'required|numeric|between:0,10000',
            'cases_per_tie' => 'required|numeric|between:0,10000',
            'layers_per_skid' => 'required|numeric|between:0,10000',

            // Packaging
            'packaging_materials' => 'nullable|array',
        ];
    }
}
