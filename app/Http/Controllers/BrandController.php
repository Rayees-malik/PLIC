<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\BrandsDatatable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Helpers\SignoffStateHelper;
use App\Models\Brand;
use App\Models\CatalogueCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use YlsIdeas\FeatureFlags\Facades\Features;

class BrandController extends Controller
{
    public function index()
    {
        $vendors = Vendor::withPending()->withAccess()->select('id', 'name')->ordered()->get();

        $datatable = new BrandsDatatable;

        return $datatable->render('brands.index', compact('datatable', 'vendors'));
    }

    public function create(Request $request)
    {
        $model = new Brand;
        extract(Brand::loadLookups());

        if ($vendors->count() === 0) {
            flash('You must submit a vendor before you can submit a new brand', 'danger');

            return redirect()->route('brands.index');
        }

        return view('brands.edit', compact('model', Brand::getLookupVariables()));
    }

    public function show($id, Request $request)
    {
        $model = Brand::allStates()
            ->withAccess()
            ->withEagerLoadedRelations()
            ->withLastSignoff()
            ->withApprovedSignoffs()
            ->with(['discoRequests' => function ($query) {
                $query->with(['signoffs' => function ($query) {
                    $query->with(['user', 'responses.user'])->where('state', SignoffStateHelper::APPROVED)->orderBy('id', 'desc');
                }])->whereHas('signoffs', function ($query) {
                    $query->where('state', SignoffStateHelper::APPROVED);
                })->select('id', 'brand_id');
            }])
            ->findOrFail($id);

        return view('brands.show', compact('model'));
    }

    public function edit($id, Request $request)
    {
        $model = Brand::allStates()->withAccess()->withEagerLoadedRelations('history')->findOrFail($id);
        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('brands.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('brands.edit', $model->signoff->initial_id);
        }

        $proposed = $model->getProposedForUser();
        if ($proposed) {
            return redirect()->route('brands.edit', $proposed);
        }

        $model->load('signoff.responses.user');
        extract(Brand::loadLookups());

        return view('brands.edit', compact('model', Brand::getLookupVariables()));
    }

    public function submit(Request $request, SubmitSignoffAction $submitSignoffAction)
    {
        $model = Brand::allStates()->withAccess()->withEagerLoadedRelations('history')->find($request->id);

        // Double check request
        if ($model && ! $model->validRequest($request)) {
            flash(__('messages.invalid_request_error'), 'danger');

            return redirect()->route('brands.index');
        }

        // Double check canUpdate status
        if ($model && ! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('brands.index');
        }

        $result = Brand::stepperUpdate($request, true);

        $signoffSubmitData = SignoffSubmitData::fromRequest($request);
        $signoffSubmitData = $signoffSubmitData->forSignoff($result->model->signoff);

        $submitSignoffAction->execute($signoffSubmitData);

        // DEV: Comment out for easier of testing.
        if (! $result->errors->allBagsEmpty()) {
            $model = $result->model;
            $errors = $result->errors;
            extract(Brand::loadLookups($model));

            return view('brands.edit', compact('model', 'errors', Brand::getLookupVariables()));
        }

        flash('Successfully sent the brand for approval.', 'success');

        return redirect()->route('brands.index');
    }

    public function copy($id)
    {
        $model = Brand::allStates()->withAccess()->withEagerLoadedRelations()->findOrFail($id);

        $clone = $model->createCopy();

        return redirect()->route('brands.edit', $clone->id);
    }

    public function backgroundSave(Request $request)
    {
        $result = Brand::stepperUpdate($request);
        $view = $this->backgroundRenderView($result);

        return response()->json(['view' => $view, 'step' => $result->saved ? null : 0]);
    }

    public function search()
    {
        $brands = Brand::withAccess()
            ->select('id', 'name', 'brand_number')
            ->get();

        return $brands->toJson();
    }

    public function searchCategories($id)
    {
        $categories = CatalogueCategory::where('brand_id', $id)
            ->select('id', 'name')
            ->get();

        return $categories->toJson();
    }

    public function downloadLogo($id)
    {
        $brand = Brand::select('id')->findOrFail($id);
        $media = $brand->getMedia('logo')->first();
        if (! $media) {
            abort(404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }

    protected function backgroundRenderView($result)
    {
        if (Features::accessible('remove-session-dependency')) {
            extract(Brand::loadLookups($result->model));
        } else {
            extract(Session::get($result->model::getSessionRelationsKey()));
        }

        request()->flash();
        $view = view('brands.form')->with([
            'signoffForm' => true, // TODO: Why?
            'model' => $result->model,
            'errors' => $result->errors,
        ])->with(compact(Brand::getLookupVariables()))->render();
        session(['_old_input' => null]);

        return "<div>{$view}</div>";
    }

    // public function productPreviewsByCategory(Request $request)
    // {
    //     $brand = Brand::allStates()
    //         ->withAccess()
    //         ->with(['products' => function ($query) {
    //             return $query->with(['uom',
    //                 'as400Pricing' => function ($query) {
    //                     $query->select('product_id', 'wholesale_price');
    //                 }])
    //                 ->select(['stock_id', 'name', 'name_fr', 'inner_units', 'upc', 'packaging_language']);
    //         }])
    //         ->findOrFail($request->id);

    //     //$products = $brand->products;
    // }

    // public function destroy($id)
    // {
    //     $model = Brand::allStates()->findOrFail($id);

    //     if ($model->{$model->stateField()} == SignoffStateHelper::IN_PROGRESS || auth()->user()->can('delete', Brand::class)) {
    //         $model->delete();
    //         flash('Brand was successfully deleted.', 'success');
    //     } else {
    //         flash('You do not have sufficient permissions to delete this brand.', 'danger');
    //     }

    //     return redirect()->route('brands.index');
    // }
}
