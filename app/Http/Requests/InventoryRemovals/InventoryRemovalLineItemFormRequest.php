<?php

namespace App\Http\Requests\InventoryRemovals;

use App\SteppedFormRequest;

class InventoryRemovalLineItemFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'lineitem_id.*' => 'nullable',
            'product_id.*' => 'required',

            'quantity.*' => 'required|numeric|between:0,10000',
            'full_mcb.*' => 'required',
            'reserve.*' => 'required',
            'vendor_pickup.*' => 'required',
            'cost.*' => 'required|numeric|between:0,10000',
            'average_landed_cost.*' => 'required|numeric|between:0,10000',
            'expiry.*' => 'required',
            'warehouse.*' => 'required',
            'reason.*' => 'required',
            'notes.*' => 'required_if:reason.*,other|nullable',
        ];
    }
}
