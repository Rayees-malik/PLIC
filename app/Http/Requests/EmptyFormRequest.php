<?php

namespace App\Http\Requests;

use App\SteppedFormRequest;

class EmptyFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [];
    }
}
