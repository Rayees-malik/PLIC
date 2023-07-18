<?php

namespace App\Http\Requests\BrandDiscoRequests;

use App\SteppedFormRequest;

class BrandDiscoRequestFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'reason' => 'required',
            'recoup_plan' => 'nullable',

            'ap_owed' => 'required|numeric|between:-9999999,9999999',

            'ytd_sales' => 'required|numeric|between:0,9999999',
            'ytd_margin' => 'required|numeric|between:0,100',
            'previous_year_sales' => 'required|numeric|between:0,9999999',
            'previous_year_margin' => 'required|numeric|between:0,9999999',
            'inventory_value' => 'required|numeric|between:0,9999999',
        ];
    }
}
