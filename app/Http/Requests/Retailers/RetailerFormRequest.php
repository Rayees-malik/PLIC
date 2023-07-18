<?php

namespace App\Http\Requests\Retailers;

class RetailerFormRequest extends \App\SteppedFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'number_stores' => 'nullable|integer|between:0,10000',
            'fiscal_year_start' => 'nullable|date',

            'account_manager_id' => 'required',

            'distribution_type' => 'nullable',

            'markup' => 'required|numeric|between:0,100',
            'target_margin' => 'required|numeric|between:0,100',
            'as400_pricing_file' => 'required',

            'costing_type' => 'required',
            'warehouse_number' => 'required',

            'allow_promos' => 'required',
            'websites' => 'nullable',
        ];
    }
}
