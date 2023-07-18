<?php

namespace App\Filters;

use App\Models\CatalogueCategory;
use Elegant\Sanitizer\Contracts\Filter;
use Illuminate\Support\Facades\Config;

class CreateCatalogueCategoryFilter implements Filter
{
    public function apply($value, $options = [])
    {
        if ($value >= 0) {
            return $value ? $value : null;
        }

        $brandId = request()->get('brand_id');
        if (! $brandId) {
            return;
        }

        $categories = Config::get('categories')['catalogueCategories'];
        if ($categories[$value]) {
            // Ensure we don't create duplicate categories
            $category = CatalogueCategory::where([
                'name' => $categories[$value]['name'],
                'brand_id' => $brandId,
            ])->first();
            if ($category) {
                return $category->id;
            }

            // Create the new category
            $maxSort = CatalogueCategory::where('brand_id', $brandId)->max('sort');

            $category = new \App\Models\CatalogueCategory;
            $category->fill($categories[$value]);
            $category->brand_id = $brandId;
            $category->sort = $maxSort + 1;
            $category->save();

            return $category->id;
        }
    }
}
