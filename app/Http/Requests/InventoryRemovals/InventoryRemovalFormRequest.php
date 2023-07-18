<?php

namespace App\Http\Requests\InventoryRemovals;

use App\SteppedFormRequest;

class InventoryRemovalFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'comment' => 'nullable',
        ];
    }
}
