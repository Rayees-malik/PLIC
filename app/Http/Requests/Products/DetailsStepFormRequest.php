<?php

namespace App\Http\Requests\Products;

use App\Rules\CertificationUpload;
use App\SteppedFormRequest;

class DetailsStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'tester_available' => 'nullable',
            'tester_brand_stock_id' => 'nullable',
            'brand_stock_id' => ['required', 'string', 'max:15'],
            'flags' => 'nullable',

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
            'allergens' => 'required|array',
            'certifications.*' => new CertificationUpload,
        ];
    }
}
