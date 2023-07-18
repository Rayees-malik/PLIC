<?php

namespace App\Http\Controllers;

use App\Models\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductCategoryController extends Controller
{
    public function subcategories($id, Request $request)
    {
        $previousCategoryId = Arr::wrap($request->previous_category_id);
        $categories = ProductSubcategory::byCategory($id, $previousCategoryId)->select(['id', 'code', 'name', 'category'])->ordered()->get();

        return $categories->toJson();
    }
}
