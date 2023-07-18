<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\BrandDiscoRequestsDatatable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Helpers\SignoffStateHelper;
use App\Http\Requests\BrandDiscoRequests\BrandDiscoRequestFormRequest;
use App\Models\Brand;
use App\Models\BrandDiscoRequest;
use App\User;

class BrandDiscoRequestController extends Controller
{
    public function index(BrandDiscoRequestsDatatable $datatable)
    {
        $users = User::withTrashed()->whereHas('brandDiscoRequests')->select('id', 'name')->ordered()->get();

        return $datatable->render('branddiscos.index', compact('datatable', 'users'));
    }

    public function create($brandId)
    {
        // Check to see if a disco request already exists
        $requests = BrandDiscoRequest::withPending()->where('brand_id', $brandId)->count();
        if ($requests) {
            flash('Selected brand already has a pending disco request.', 'danger');

            return redirect()->back();
        }

        $brand = Brand::withAccess()->select('id', 'name')->findOrFail($brandId);
        $model = new BrandDiscoRequest;
        $model->brand_id = $brand->id;
        $model->brand = $brand;

        return view('branddiscos.add', compact('model'));
    }

    public function store($brandId, BrandDiscoRequestFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $brand = Brand::withAccess()->select('id', 'name', 'vendor_relations_specialist_id')->findOrFail($brandId);

        if ($brand->vendor_relations_specialist_id !== auth()->id() && ! auth()->user()->can('admin')) {
            flash('Only the Vendor Relations Specialist can request to disco a brand.', 'danger');

            return redirect()->back();
        }

        $formData = $request->partialValidated()->validated;
        $formData['name'] = $brand->name;
        $formData['submitted_by'] = auth()->id();
        $formData['brand_id'] = $brandId;

        $discoRequest = BrandDiscoRequest::startSignoff($formData);

        $errors = $request->partialValidated()->errors;
        if ($errors->isNotEmpty()) {
            return redirect()->route('branddiscos.edit', $discoRequest->id)->withInput()->with(['errors' => $errors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Brand disco request has been saved.', 'success');

            return redirect()->route('branddiscos.edit', $discoRequest->id);
        } else {
            $discoRequest = $discoRequest->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($discoRequest->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted brand disco request for approval.', 'success');

            return redirect()->route('branddiscos.index');
        }
    }

    public function show($id)
    {
        $model = BrandDiscoRequest::allStates()->withLastSignoff()->with(['user', 'brand' => function ($query) {
            $query->select('id', 'name');
        }])->findOrFail($id);

        return view('branddiscos.show', compact('model'));
    }

    public function edit($id)
    {
        $model = BrandDiscoRequest::allStates()->with(['brand' => function ($query) {
            $query->select('id', 'name', 'vendor_relations_specialist_id');
        }])->findOrFail($id);

        if ($model->brand->vendor_relations_specialist_id !== auth()->id() && ! auth()->user()->can('admin')) {
            flash('Only the Vendor Relations Specialist can request to disco a brand.', 'danger');

            return redirect()->back();
        }

        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('branddiscos.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('branddiscos.edit', $model->signoff->initial_id);
        }

        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Brand disco requests cannot be edited once approved.', 'danger');

            return redirect()->route('branddiscos.index');
        }

        return view('branddiscos.edit', compact('model'));
    }

    public function update($id, BrandDiscoRequestFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $model = BrandDiscoRequest::allStates()->with(['brand' => function ($query) {
            $query->select('id', 'name', 'vendor_relations_specialist_id');
        }])->findOrFail($id);

        if ($model->brand->vendor_relations_specialist_id !== auth()->id() && ! auth()->user()->can('admin')) {
            flash('Only the Vendor Relations Specialist can request to disco a brand.', 'danger');

            return redirect()->back();
        }

        if (! $model->validRequest($request)) {
            flash(__('messages.invalid_request_error'), 'danger');

            return redirect()->route('branddiscos.index');
        }

        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('branddiscos.index');
        }

        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Brand disco requests cannot be edited once approved.', 'danger');

            return redirect()->route('branddiscos.index');
        }

        $model->update($request->partialValidated()->validated);
        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            $model = $model->getLastProposed();
        }

        $errors = $request->partialValidated()->errors;
        if ($errors->isNotEmpty()) {
            return redirect()->route('branddiscos.edit', $model->id)->withInput()->with(['errors' => $errors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Brand disco request has been saved.', 'success');

            return redirect()->route('branddiscos.edit', $model->id);
        } else {
            $model = $model->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($model->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted brand disco request for approval.', 'success');

            return redirect()->route('branddiscos.index');
        }
    }
}
