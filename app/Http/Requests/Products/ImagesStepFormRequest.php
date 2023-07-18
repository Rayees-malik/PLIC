<?php

namespace App\Http\Requests\Products;

use App\SteppedFormRequest;

class ImagesStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'file_label' => 'array',
        ];
    }
}
