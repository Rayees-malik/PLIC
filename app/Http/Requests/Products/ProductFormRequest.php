<?php

namespace App\Http\Requests\Products;

use App\Rules\ValidUPC;
use App\SteppedFormRequest;
use Arr;
use Illuminate\Validation\Rule;

class ProductFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            // General
            'brand_id' => 'required|integer',
            'name' => 'bail|required_unless:packaging_language,F|max:35',
            'name_fr' => 'bail|required_if:packaging_language,F|required_if:packaging_language,B|max:35',
            'stock_id' => [
                'bail',
                'sometimes',
                'required',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('id', '<>', request()->input('id'))
                        ->where('stock_id', request()->input('stock_id'))
                        ->where('status', '<>', \App\Helpers\StatusHelper::LEGACY);
                }),
            ],
            'is_display' => 'nullable',
            'supersedes_id' => 'nullable|integer',
            'country_origin' => 'required',
            'country_shipped' => 'required',
            'tariff_code' => 'required_unless:country_shipped,40',
            'packaging_language' => 'required',
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'catalogue_category_id' => 'required|integer',
            'catalogue_category_proposal' => 'required_if:catalogue_category_id,==,0',
            'catalogue_category_proposal_fr' => 'nullable',

            // Pricing
            'not_for_resale' => 'nullable',
            'unit_cost' => [
                'nullable',
                Rule::requiredIf(function () {
                    return ! Arr::has(request()->input(), 'price_change_reason') || request()->input('price_change_reason');
                }),
                'numeric',
                'between:0,99999',
            ],
            'landed_cost' => 'sometimes|required_with:unit_cost|numeric|between:0,99999',
            'wholesale_price' => 'sometimes|required_with:unit_cost|nullable|numeric|between:0,99999',
            'suggested_retail_price' => 'sometimes|required_with:unit_cost|nullable|numeric|between:0,99999',
            'price_change_reason' => 'sometimes|required_with:unit_cost',
            'price_change_date' => 'sometimes|required_with:unit_cost|required_with:landed_cost',
            'extra_addon_percent' => 'nullable|numeric|between:0,99999',
            'temp_edlp' => 'nullable|numeric|between:0,99999',
            'temp_duty' => 'nullable|numeric|between:0,99999',
            'available_ship_date' => 'nullable|date',
            'minimum_order_units' => 'nullable|numeric|between:0,99999',

            // Packaging
            'purity_sell_by_unit' => 'required|integer',
            'retailer_sell_by_unit' => 'required',
            'upc' => ['bail', 'sometimes', 'required_unless:not_for_resale,1', 'nullable', new ValidUPC],
            'size' => 'required|numeric|between:0,10000',
            'uom_id' => 'required|integer',
            'inner_upc' => [
                'bail',
                'sometimes',
                Rule::requiredIf(function () {
                    return request()->input('purity_sell_by_unit') == 2 && request()->input('not_for_resale') != 1;
                }),
                'nullable',
                new ValidUPC,
            ],
            'inner_units' => 'bail|required_if:purity_sell_by_unit,2|required_with:inner_upc|nullable|numeric|between:0,10000',
            'master_upc' => [
                'bail',
                'sometimes',
                Rule::requiredIf(function () {
                    return request()->input('purity_sell_by_unit') == 4 && request()->input('not_for_resale') != 1;
                }),
                'nullable',
                new ValidUPC,
            ],
            'master_units' => 'required|numeric|between:0,10000',
            'cases_per_tie' => 'required|numeric|between:0,10000',
            'layers_per_skid' => 'required|numeric|between:0,10000',

            // Details
            'tester_available' => 'nullable',
            'tester_brand_stock_id' => 'nullable',
            'brand_stock_id' => ['required', 'string', 'max:15'],
            'description' => 'bail|required_unless:packaging_language,F',
            'description_fr' => 'bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'features_1' => 'sometimes|bail|required_unless:packaging_language,F',
            'features_2' => 'sometimes|bail|required_unless:packaging_language,F',
            'features_3' => 'sometimes|bail|required_unless:packaging_language,F',
            'features_4' => 'nullable',
            'features_5' => 'nullable',
            'features_fr_1' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'features_fr_2' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'features_fr_3' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'features_fr_4' => 'nullable',
            'features_fr_5' => 'nullable',
            'ingredients' => 'required',
            'ingredients_fr' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'recommended_use' => 'sometimes|required',
            'recommended_use_fr' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'recommended_dosage' => 'sometimes|required',
            'recommended_dosage_fr' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'warnings' => 'sometimes|required',
            'warnings_fr' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'contraindications' => 'sometimes|required',
            'contraindications_fr' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'benefits' => 'required',
            'benefits_fr' => 'sometimes|bail|required_if:packaging_language,F|required_if:packaging_language,B',
            'shelf_life' => 'required|numeric|between:0,10000',
            'shelf_life_units' => 'required',

            // Review
            'submission_notes' => 'nullable',

            // Administrative
            'hide_flyer' => 'sometimes',
            'hide_export' => 'sometimes',
        ];
    }

    public function filters()
    {
        return [
            // General
            'name' => 'trim',
            'name_fr' => 'trim',
            'catalogue_category_id' => 'create_catalogue_category',

            // Packaging
            'retailer_sell_by_unit' => 'sum_array',
        ];
    }
}
