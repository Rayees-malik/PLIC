<?php

namespace App\Http\Requests\Promos;

class CaseStackDealFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        return [
            'brand_id' => 'required',
            'deal' => 'required|array',
            'deal_fr' => 'required|array',

            'deal.*' => 'nullable',
            'deal_fr.*' => 'nullable',
        ];
    }
}
