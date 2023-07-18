<?php

namespace App\Http\Requests\Products;

use App\Rules\NpnValid;
use App\SteppedFormRequest;
use Illuminate\Validation\Rule;

class RegulatoryStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'npn' => [
                'bail',
                'sometimes',
                'required_if:category_id,7',
                new NpnValid,
            ],
            'npn_issued' => 'nullable', 'date',

            'importer_is_purity' => 'sometimes|required',
            'importer_name' => [
                'sometimes',
                Rule::requiredIf(function () {
                    return ! request()->input('importer_is_purity') && request()->input('country_shipped') != 40;
                }),
            ],
            'importer_phone' => [
                'sometimes',
                Rule::requiredIf(function () {
                    return ! request()->input('importer_is_purity') && request()->input('country_shipped') != 40;
                }),
            ],
            'importer_email' => [
                'sometimes',
                Rule::requiredIf(function () {
                    return ! request()->input('importer_is_purity') && request()->input('country_shipped') != 40;
                }),
            ],

            'cosmetic_notification_number' => 'nullable',

            'medical_class' => 'sometimes|required',
            'medical_device_establishment_id' => 'sometimes|required_if:medical_class,==,2',
            'medical_device_establishment_license_id' => 'sometimes|required_if:medical_class,==,2',

            'serving_size' => 'sometimes|required',
            'calories' => 'sometimes|required|numeric|between:0,99999',
            'total_fat' => 'sometimes|required|numeric|between:0,99999',
            'trans_fat' => 'sometimes|required|numeric|between:0,99999',
            'saturated_fat' => 'sometimes|required|numeric|between:0,99999',
            'cholesterol' => 'sometimes|required|numeric|between:0,99999',
            'sodium' => 'sometimes|required|numeric|between:0,99999',
            'carbohydrates' => 'sometimes|required|numeric|between:0,99999',
            'fiber' => 'sometimes|required|numeric|between:0,99999',
            'sugar' => 'sometimes|required|numeric|between:0,99999',
            'protein' => 'sometimes|required|numeric|between:0,99999',

            'pesticide_class' => 'sometimes|required',
            'pca_number' => 'sometimes|required',
        ];
    }

    public function filters()
    {
        return [
            'importer_name' => 'trim|capitalize',
            'importer_email' => 'trim|lowercase',
        ];
    }
}
