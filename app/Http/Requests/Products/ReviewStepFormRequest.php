<?php

namespace App\Http\Requests\Products;

use App\SteppedFormRequest;

class ReviewStepFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'submission_notes' => 'nullable',
        ];
    }
}
