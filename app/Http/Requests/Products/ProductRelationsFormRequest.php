<?php

namespace App\Http\Requests\Products;

use App\Rules\ProductCategoryHas;
use App\SteppedFormRequest;

class ProductRelationsFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            // Unit Dimensions
            'unit_width' => 'required|numeric',
            'unit_depth' => 'required|numeric',
            'unit_height' => 'required|numeric',
            'unit_gross_weight' => 'required|numeric',
            'unit_net_weight' => ['sometimes', new ProductCategoryHas('HAS_NET_WEIGHT')],

            // Inner Dimensions
            'inner_width' => 'required|numeric',
            'inner_depth' => 'required|numeric',
            'inner_height' => 'required|numeric',
            'inner_gross_weight' => 'required|numeric',

            // Master Dimensions
            'master_width' => 'nullable|numeric',
            'master_depth' => 'nullable|numeric',
            'master_height' => 'nullable|numeric',
            'master_gross_weight' => 'nullable|numeric',

            // Packaging Materials
            'packaging_materials' => 'nullable|array',

            // Flags
            'flags' => 'nullable|array',

            // Certifications
            'certifications' => 'array',

            // allergens
            'allergens' => 'array',
        ];
    }
}
