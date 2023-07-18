<?php

namespace App\Http\Requests\InventoryRemovals;

use App\SteppedFormRequest;

class InventoryRemovalLineItemWarehouseFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'lineitem_id.*' => 'nullable',
            'product_id.*' => 'required',

            'quantity.*' => 'required|numeric|between:0,10000',
        ];
    }
}
