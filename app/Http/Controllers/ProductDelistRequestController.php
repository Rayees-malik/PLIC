<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\ProductDelistRequestsDatatable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Helpers\SignoffStateHelper;
use App\Http\Requests\ProductDelistRequests\ProductDelistRequestFormRequest;
use App\Models\Product;
use App\Models\ProductDelistRequest;
use App\Models\Signoff;
use Illuminate\Http\Request;

class ProductDelistRequestController extends Controller
{
    public function index(ProductDelistRequestsDatatable $datatable)
    {
        return $datatable->render('productdelists.index', compact('datatable'));
    }

    public function create($productId)
    {
        // Check to see if a delist request already exists
        $signoffs = Signoff::whereHasMorph('initial', ProductDelistRequest::class, function ($query) use ($productId) {
            $query
                ->withPending()
                ->where('product_id', $productId);
        })->where('state', SignoffStateHelper::PENDING)->count();

        if ($signoffs > 0) {
            flash('Selected product already has a pending delist request.', 'danger');

            return redirect()->back();
        }

        $product = Product::withAccess()->select('id', 'name', 'name_fr', 'packaging_language', 'stock_id')->findOrFail($productId);
        $model = new ProductDelistRequest;
        $model->product_id = $product->id;
        $model->product = $product;

        return view('productdelists.add', [
            'model' => $model,
        ]);
    }

    public function store($productId, ProductDelistRequestFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $product = Product::withAccess()->select('id', 'name', 'name_fr', 'packaging_language', 'stock_id')->findOrFail($productId);

        $formData = $request->partialValidated()->validated;
        $formData['name'] = "{$product->getName()} ({$product->stock_id})";
        $formData['submitted_by'] = auth()->id();
        $formData['product_id'] = $productId;

        $signoffs = Signoff::whereHasMorph('initial', ProductDelistRequest::class, function ($query) use ($product) {
            $query
                ->withPending()
                ->where('product_id', $product->id);
        })->where('state', SignoffStateHelper::PENDING)->count();

        if ($signoffs > 0) {
            flash('Selected product already has a pending delist request.', 'danger');

            return redirect()->back();
        }

        $delistRequest = ProductDelistRequest::startSignoff($formData);

        $errors = $request->partialValidated()->errors;
        if ($errors->isNotEmpty()) {
            return redirect()->route('productdelists.edit', $delistRequest->id)->withInput()->with(['errors' => $errors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Product delist request has been saved.', 'success');

            return redirect()->route('productdelists.edit', $delistRequest->id);
        } else {
            $delistRequest = $delistRequest->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($delistRequest->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted product delist request for approval.', 'success');

            return redirect()->route('productdelists.index');
        }
    }

    public function show($id)
    {
        $model = ProductDelistRequest::withAccess()->allStates()->withLastSignoff()->with(['product' => function ($query) {
            $query->select('id', 'name', 'name_fr', 'packaging_language', 'stock_id');
        }])->findOrFail($id);

        return view('productdelists.show', [
            'model' => $model,
        ]);
    }

    public function edit($id, Request $request)
    {
        $model = ProductDelistRequest::withAccess()->allStates()->with(['product' => function ($query) {
            $query->withAccess()->select('id', 'name', 'name_fr', 'packaging_language', 'stock_id');
        }])->findOrFail($id);

        if (! $model->product) {
            flash('You do not have permission to delist the selected product.', 'danger');

            return redirect()->back();
        }

        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('productdelists.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('productdelists.edit', $model->signoff->initial_id);
        }

        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Product delist requests cannot be edited once approved.', 'danger');

            return redirect()->route('productdelists.index');
        }

        return view('productdelists.edit', [
            'model' => $model,
        ]);
    }

    public function update($id, ProductDelistRequestFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $model = ProductDelistRequest::withAccess()->allStates()->with(['product' => function ($query) {
            $query->withAccess()->select('id', 'name', 'name_fr', 'packaging_language', 'stock_id');
        }])->findOrFail($id);

        if (! $model->product) {
            flash('You do not have permission to delist the selected product.', 'danger');

            return redirect()->back();
        }

        if (! $model->validRequest($request)) {
            flash(__('messages.invalid_request_error'), 'danger');

            return redirect()->route('productdelists.index');
        }

        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('productdelists.index');
        }

        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Product delist requests cannot be edited once approved.', 'danger');

            return redirect()->route('productdelists.index');
        }

        $model->update($request->partialValidated()->validated);
        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            $model = $model->getLastProposed();
        }

        $errors = $request->partialValidated()->errors;
        if ($errors->isNotEmpty()) {
            return redirect()->route('productdelists.edit', $model->id)->withInput()->with(['errors' => $errors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Product delist request has been saved.', 'success');

            return redirect()->route('productdelists.edit', $model->id);
        } else {
            $model = $model->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($model->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted product delist request for approval.', 'success');

            return redirect()->route('productdelists.index');
        }
    }
}
