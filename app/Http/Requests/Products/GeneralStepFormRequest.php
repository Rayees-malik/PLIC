<?php

namespace App\Http\Requests\Products;

use App\SteppedFormRequest;
use Illuminate\Validation\Rule;

class GeneralStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
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
            'packaging_language' => 'required',
            'tariff_code' => 'required_unless:country_shipped,40',
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'catalogue_category_id' => 'required|integer',
            'catalogue_category_proposal' => 'required_if:catalogue_category_id,==,0',
            'catalogue_category_proposal_fr' => 'nullable',
        ];
    }
}
