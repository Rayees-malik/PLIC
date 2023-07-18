<?php

namespace App\Http\Requests\Products;

use App\SteppedFormRequest;

class ProductSaveRequiredFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'name' => 'bail|required_unless:packaging_language,F|max:35',
            'name_fr' => 'bail|required_if:packaging_language,F|required_if:packaging_language,B|max:35',
            'category_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.*' => 'A valid product name (EN) is required to save your progress.',
            'name_fr.*' => 'A valid product name (FR) is required to save your progress.',
            'category_id.*' => 'Category is required to save your progress.',
        ];
    }
}
