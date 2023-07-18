<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\ProductsDatatable;
use App\Datatables\SubmissionsDataTable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Models\Brand;
use App\Models\Certification;
use App\Models\Product;
use App\Models\Signoff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use YlsIdeas\FeatureFlags\Facades\Features;

class ProductController extends Controller
{
    const START_WITH_FIELDS = ['stock_id'];

    const LIKE_FIELDS = ['name', 'name_fr'];

    public function index()
    {
        $brands = Brand::withAccess()->active()->select('id', 'name')->ordered()->get();

        $datatable = new ProductsDatatable;

        return $datatable->render('products.index', compact('datatable', 'brands'));
    }

    public function create(Request $request)
    {
        $model = new Product;
        extract(Product::loadLookups($model));

        if ($brands->count() === 0) {
            flash('You must submit a brand before you can submit a new product', 'danger');

            return redirect()->route('products.index');
        }

        return view('products.add', compact('model', Product::getLookupVariables()));
    }

    public function show($id, Request $request)
    {
        $certifications = Certification::select('name', 'id')->orderBy('name', 'asc')->get()->pluck('name', 'id');

        $model = Product::allStates()
            ->withEagerLoadedRelations()
            ->with([
                'as400WarehouseStock',
                'category',
                'subcategory',
                'as400Supersedes',
                'as400SupersededBy',
            ])
            ->withLastSignoff()
            ->withApprovedSignoffs()
            ->findOrFail($id);

        return view('products.show')->with(['model' => $model, 'certifications' => $certifications]);
    }

    public function edit($id, Request $request)
    {
        $model = Product::allStates()->withAccess()->withEagerLoadedRelations('history')->findOrFail($id);
        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('products.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('products.edit', $model->signoff->initial_id);
        }

        $proposed = $model->getProposedForUser();
        if ($proposed) {
            return redirect()->route('products.edit', $proposed);
        }

        $model->load('signoff.responses.user');
        extract(Product::loadLookups($model));

        return view('products.edit', compact('model', Product::getLookupVariables()));
    }

    public function indexSubmissions($type = 'pending')
    {
        $submissions = Signoff::with([
            'user',
            'proposed' => function ($query) {
                $query->morphWith([
                    \App\Models\Product::class => [
                        'brand' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ],
                ]);
            },
        ])->whereHasMorph('proposed', \App\Models\Product::class, function ($query) {
            $query->allStates()->withAccess();
        });

        switch ($type) {
            case 'rejected':
                $submissions = $submissions->rejected();
                break;
            case 'drafts':
                $submissions = $submissions->inProgress();
                break;
            default:
                $submissions = $submissions->pending();
        }

        $datatable = new SubmissionsDataTable($submissions);

        return $datatable->render('products.index-submissions', compact('datatable', 'type'));
    }

    public function submit(Request $request, SubmitSignoffAction $submitSignoffAction)
    {
        $model = Product::allStates()->withAccess()->withEagerLoadedRelations('history')->findOrFail($request->id);

        // Double check request
        if (! $model->validRequest($request)) {
            flash(__('messages.invalid_request_error'), 'danger');

            return redirect()->route('products.index');
        }

        // Double check canUpdate status
        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('products.index');
        }

        $result = Product::stepperUpdate($request, true);

        $signoffSubmitData = SignoffSubmitData::fromRequest($request);
        $signoffSubmitData = $signoffSubmitData->forSignoff($result->model->signoff()->with('proposed')->first());

        $submitSignoffAction->execute($signoffSubmitData);

        // DEV: Comment out for easier of testing.
        if (! $result->errors->allBagsEmpty()) {
            $model = $result->model;
            $errors = $result->errors;
            extract(Product::loadLookups($model));

            return view('products.edit', compact('model', 'errors', Product::getLookupVariables()));
        }

        flash('Successfully sent the product for approval.', 'success');

        return redirect()->route('products.index');
    }

    public function copy($id)
    {
        $model = Product::allStates()->withAccess()->withEagerLoadedRelations()->findOrFail($id);

        $clone = $model->createCopy();

        return redirect()->route('products.edit', $clone->id);
    }

    public function backgroundSave(Request $request)
    {
        $result = Product::stepperUpdate($request);
        $view = $this->backgroundRenderView($result);

        return response()->json(['view' => $view, 'step' => $result->saved ? null : 0]);
    }

    public function search(Request $request)
    {
        $terms = $request->except('ignore_status');
        $products = Product::withAccess();

        if (! $request->ignore_status) {
            $products->catalogueActive();
        }

        $allTerm = null;
        foreach ($terms as $term => $value) {
            if ($term == 'all') {
                $allTerm = $value;
            } else {
                if (in_array($term, ProductController::START_WITH_FIELDS)) {
                    $products->whereRaw("lower({$term}) like lower(?)", ["{$value}%"]);
                } elseif (in_array($term, ProductController::LIKE_FIELDS)) {
                    $products->whereRaw("lower({$term}) like lower(?)", ["%{$value}%"]);
                } else {
                    $products->where($term, $value);
                }
            }
        }

        if ($allTerm) {
            $products->where(function ($query) use ($allTerm) {
                $query->whereRaw('lower(?) like lower(?)', ['brand_stock_id', "%{$allTerm}%"]);
                foreach (ProductController::START_WITH_FIELDS as $field) {
                    $query->orWhereRaw("lower({$field}) like lower(?)", ["%{$allTerm}%"]);
                }
                foreach (ProductController::LIKE_FIELDS as $field) {
                    $query->orWhereRaw("lower({$field}) like lower(?)", ["%{$allTerm}%"]);
                }
            });
        }

        return $products->with(['uom' => function ($query) {
            $query->select('id', 'unit');
        }])
            ->ordered()
            ->select('id', 'stock_id', 'name', 'name_fr', 'uom_id', 'size', 'brand_stock_id')
            ->get()
            ->toJson();
    }

    public function downloadImage($stockId)
    {
        $product = Product::where('stock_id', $stockId)->select('id')->firstOrFail();
        $media = $product->getMedia('product')->first();
        if (! $media) {
            abort(404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }

    public function downloadLabelFlat($stockId)
    {
        $product = Product::where('stock_id', $stockId)->select('id')->firstOrFail();
        $media = $product->getMedia('label_flat')->first();
        if (! $media) {
            abort(404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }

    protected function backgroundRenderView($result)
    {
        if (Features::accessible('remove-session-dependency')) {
            extract(Product::loadLookups($result->model));
        } else {
            extract(Session::get($result->model->getSessionRelationsKey()));
        }

        $subcategories = Product::loadSubcategories($result->model->category_id, $result->model);
        $catalogueCategories = Product::loadCatalogueCategories($result->model->brand_id, $brands);
        $brand = Product::loadSelectedBrand($result->model, $brands);

        // This is to overwrite the existing input value as we may
        // be saving it to the database in the request and need to
        // set it to the newly generated id so it defaults correctly
        if ($result->saved) {
            request()->merge($result->model->getFilteredAttributes());
        }

        request()->flash();
        $view = view('products.form')->with([
            'signoffForm' => true, // TODO: Why?
            'model' => $result->model,
            'errors' => $result->errors,
        ])->with(compact(Product::getLookupVariables()))->render();
        session(['_old_input' => null]);

        return "<div>{$view}</div>";
    }

    // public function destroy($id)
    // {
    //     $model = Product::allStates()->with('brand')->findOrFail($id);

    //     if ($model->{$model->stateField()} == SignoffStateHelper::IN_PROGRESS || auth()->user()->can('delete', Product::class)) {
    //         $model->delete();
    //         flash('Product was successfully deleted.', 'success');
    //     } else {
    //         flash('You do not have sufficient permissions to delete this product.', 'danger');
    //     }

    //     return redirect()->route('products.index');
    // }
}
