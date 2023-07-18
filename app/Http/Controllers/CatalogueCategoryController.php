<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brands\CatalogueCategoryFormRequest;
use App\Models\Brand;
use App\Models\CatalogueCategory;
use App\Models\Product;
use Illuminate\Support\Arr;

class CatalogueCategoryController extends Controller
{
    public function index($brandId)
    {
        $model = Brand::withPending()->withAccess()->with([
            'catalogueCategories' => function ($query) {
                $query->ordered();
            },
        ])->select('id', 'name')->findOrFail($brandId);

        return view('brands.categories.index', compact('model'));
    }

    public function update($brandId, CatalogueCategoryFormRequest $request)
    {
        $model = Brand::withPending()->withAccess()->with([
            'catalogueCategories' => function ($query) {
                $query->ordered();
            },
        ])->select('id', 'name')->findOrFail($brandId);

        $categoryData = $request->partialValidated();

        $sortCounter = 0;
        foreach (Arr::get($categoryData->validated, 'category_id', []) as $index => $id) {
            $name = Arr::get($categoryData->validated, "name.{$index}", null);
            $nameFR = Arr::get($categoryData->validated, "name_fr.{$index}", null);

            if (! $id && ! $name && ! $nameFR) {
                continue;
            }

            $data = [
                'sort' => $sortCounter,
                'name' => $name,
                'name_fr' => $nameFR,
            ];
            $sortCounter++;

            $category = $model->catalogueCategories->where('id', $id)->first();

            if ($category) {
                $category->update($data);
            } else {
                $category = new CatalogueCategory;
                $category->brand_id = $brandId;
                $category->fill($data);
                $category->save();
            }
        }

        $failedDelete = false;
        $deletedIds = array_filter(explode(',', $request->deleted_items));
        foreach ($deletedIds as $deletedId) {
            $category = $model->catalogueCategories->where('id', $deletedId)->first();
            if ($category) {
                if ($category->products()->count()) {
                    $failedDelete = true;
                } else {
                    $category->delete();
                }
            }
        }

        if ($categoryData->errors->isNotEmpty()) {
            return redirect()->route('brands.categories', $brandId)->withInput()->with(['errors' => $categoryData->errors]);
        }

        $request->input('action');
        if ($failedDelete) {
            flash('Only categories without products may be deleted.', 'danger');
        } else {
            flash('Catalogue categories have been saved.', 'success');
        }

        return redirect()->route('brands.categories', $brandId);
    }

    public function jsonShow($brandId)
    {
        $brand = Brand::allStates()->withAccess()->select('id')->findOrFail($brandId);
        $categories = Product::loadCatalogueCategories($brandId, collect([$brand]));

        return json_encode(array_values($categories->toArray()));
    }
}
