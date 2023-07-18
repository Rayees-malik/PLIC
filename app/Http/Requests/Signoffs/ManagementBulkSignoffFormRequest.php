<?php

namespace App\Http\Requests\Signoffs;

use App\Http\Requests\BaseFormRequest;

class ManagementBulkSignoffFormRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'signoff_comment' => 'nullable',
            'signoff_id' => 'array',
            'selected' => 'array',
        ];
    }
}
