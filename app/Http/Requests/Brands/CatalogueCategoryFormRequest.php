<?php

namespace App\Http\Requests\Brands;

use App\SteppedFormRequest;

class CatalogueCategoryFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'category_id.*' => 'nullable',
            'name.*' => 'required',
            'name_fr.*' => 'nullable',
        ];
    }
}
