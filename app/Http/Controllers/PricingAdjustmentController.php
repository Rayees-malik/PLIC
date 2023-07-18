<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\PricingAdjustmentsDatatable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Helpers\SignoffStateHelper;
use App\Http\Requests\PricingAdjustments\PricingAdjustmentFormRequest;
use App\Models\AS400\AS400Customer;
use App\Models\Brand;
use App\Models\PricingAdjustment;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PricingAdjustmentController extends Controller
{
    public function index(PricingAdjustmentsDatatable $datatable)
    {
        $accounts = AS400Customer::select('id', 'name', 'customer_number', 'price_code')->ordered()->get();
        $users = User::withTrashed()->whereHas('pricingAdjustments')->select('id', 'name')->ordered()->get();
        $brands = Brand::withAccess()->active()->select('id', 'name')->ordered()->get();

        return $datatable->render('pricingadjustments.index', compact('datatable', 'accounts', 'users', 'brands'));
    }

    public function create()
    {
        $model = new PricingAdjustment;
        extract(PricingAdjustment::loadLookups($model));

        return view('pricingadjustments.add', compact('model', PricingAdjustment::getLookupVariables()));
    }

    public function store(PricingAdjustmentFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $formData = PricingAdjustment::modifyFormData($request->partialValidated()->validated);

        $paf = PricingAdjustment::startSignoff($formData);
        $paf->extraUpdates($request);
        $paf->uploadFiles($formData);

        if (! $paf->formErrors->allBagsEmpty()) {
            return redirect()->route('pricingadjustments.edit', $paf->id)->withInput()->with(['errors' => $paf->formErrors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Pricing adjustment request has been saved.', 'success');

            return redirect()->route('pricingadjustments.edit', $paf->id);
        } else {
            $paf = $paf->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($paf->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted pricing adjustment request for approval.', 'success');

            return redirect()->route('pricingadjustments.index');
        }
    }

    public function show($id)
    {
        $model = PricingAdjustment::allStates()->withLastSignoff()->with(['user', 'lineItems', 'media'])->findOrFail($id);
        $customers = AS400Customer::whereIn('customer_number', $model->accounts)->pluck('name', 'customer_number');

        $accounts = [];
        foreach ($customers as $number => $name) {
            $accounts[] = "{$name} ({$number})";
        }

        return view('pricingadjustments.show', compact('model', 'accounts'));
    }

    public function edit($id, Request $request)
    {
        extract(PricingAdjustment::getPreloadedModel($id));

        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('pricingadjustments.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('pricingadjustments.edit', $model->signoff->initial_id);
        }

        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Pricing adjustment requests cannot be edited once approved.', 'danger');

            return redirect()->route('pricingadjustments.index');
        }

        $proposed = $model->getProposedForUser();
        if ($proposed) {
            return redirect()->route('pricingadjustments.edit', $proposed);
        }

        return view('pricingadjustments.edit', compact('model', PricingAdjustment::getLookupVariables()));
    }

    public function update($id, PricingAdjustmentFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $paf = PricingAdjustment::allStates()->findOrFail($id);

        if (! $paf->validRequest($request)) {
            flash($paf->INVALID_REQUEST_ERROR, 'danger');

            return redirect()->route('pricingadjustments.index');
        }

        if (! $paf->canUpdate) {
            flash($paf->UPDATE_PENDING_ERROR, 'danger');

            return redirect()->route('pricingadjustments.index');
        }

        if ($paf->{$paf->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Pricing adjustment requests cannot be edited once approved.', 'danger');

            return redirect()->route('pricingadjustments.index');
        }

        $formData = PricingAdjustment::modifyFormData($request->partialValidated()->validated, $paf);

        $paf->update($formData);
        if ($paf->{$paf->stateField()} == SignoffStateHelper::INITIAL) {
            $paf = $paf->getLastProposed();
        }
        $paf->extraUpdates($request);
        $paf->uploadFiles($formData);

        if (! $paf->formErrors->allBagsEmpty()) {
            return redirect()->route('pricingadjustments.edit', $paf->id)->withInput()->with(['errors' => $paf->formErrors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Pricing adjustment request has been saved.', 'success');

            return redirect()->route('pricingadjustments.edit', $paf->id);
        } else {
            $paf = $paf->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($paf->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted pricing adjustment request for approval.', 'success');

            return redirect()->route('pricingadjustments.index');
        }
    }

    public function productSearch(Request $request)
    {
        $type = $request->type;
        $ids = Arr::wrap($request->ids);

        if (empty($type) || empty($ids)) {
            return;
        }

        $results = [];
        $models = [];
        if ($type == 'product' || $type == 'category') {
            $models = Product::forPriceAdjustment()
                ->catalogueActive()
                ->whereIn($type == 'product' ? 'id' : 'catalogue_category_id', $ids)
                ->get();
        } elseif ($type == 'brand') {
            $models = Brand::forPriceAdjustment()
                ->whereIn('id', $ids)
                ->get();
        }

        foreach ($models as $model) {
            $results[] = $model->getPriceAdjustmentData();
        }

        return response()->json($results);
    }
}
