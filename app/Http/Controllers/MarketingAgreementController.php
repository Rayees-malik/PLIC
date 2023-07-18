<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\MarketingAgreementsDatatable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Helpers\SignoffStateHelper;
use App\Http\Requests\MarketingAgreements\MarketingAgreementFormRequest;
use App\Models\AS400\AS400Customer;
use App\Models\Brand;
use App\Models\MarketingAgreement;
use App\User;

class MarketingAgreementController extends Controller
{
    public function index(MarketingAgreementsDatatable $datatable)
    {
        $users = User::withTrashed()->whereHas('marketingAgreements')->select('id', 'name')->ordered()->get();
        $brands = Brand::withAccess()->active()->select('id', 'name')->ordered()->get();

        return $datatable->render('marketingagreements.index', compact('datatable', 'users', 'brands'));
    }

    public function create()
    {
        $model = new MarketingAgreement;
        extract(MarketingAgreement::loadLookups($model));

        return view('marketingagreements.add', compact('model', MarketingAgreement::getLookupVariables()));
    }

    public function store(MarketingAgreementFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $formData = MarketingAgreement::modifyFormData($request->partialValidated()->validated);

        $maf = MarketingAgreement::startSignoff($formData);
        $maf->extraUpdates($request);
        $maf->uploadFiles($formData);

        if (! $maf->formErrors->allBagsEmpty()) {
            return redirect()->route('marketingagreements.edit', $maf->id)->withInput()->with(['errors' => $maf->formErrors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Marketing agreement form has been saved.', 'success');

            return redirect()->route('marketingagreements.edit', $maf->id);
        } else {
            $proposedMaf = $maf->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($proposedMaf->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted marketing agreement form for approval.', 'success');

            return redirect()->route('marketingagreements.index');
        }
    }

    public function show($id)
    {
        $model = MarketingAgreement::allStates()->withLastSignoff()->with([
            'user',
            'media',
            'lineItems' => function ($query) {
                $query->with(['brand' => function ($query) {
                    $query->select('id', 'name');
                }]);
            },
        ])->findOrFail($id);

        $account = $model->account_other;
        if ($model->account != 'Other') {
            $customer = AS400Customer::where('customer_number', $model->account)->select('name', 'customer_number')->first();
            $account = $customer ? "{$customer->name} (#{$customer->customer_number})" : "#{$model->account}";
        }

        $lineItemBrands = [];
        if ($model) {
            foreach ($model->lineItems as $lineItem) {
                if (! array_key_exists($lineItem->brand_id, $lineItemBrands)) {
                    $lineItemBrands[$lineItem->brand_id] = $lineItem->brand->name;
                }
            }
            asort($lineItemBrands);
        }

        return view('marketingagreements.show', compact('model', 'account', 'lineItemBrands'));
    }

    public function edit($id)
    {
        extract(MarketingAgreement::getPreloadedModel($id));
        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('marketingagreements.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('marketingagreements.edit', $model->signoff->initial_id);
        }

        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Marketing agreement requests cannot be edited once approved.', 'danger');

            return redirect()->route('marketingagreements.index');
        }

        $proposed = $model->getProposedForUser();
        if ($proposed) {
            return redirect()->route('marketingagreements.edit', $proposed);
        }

        return view('marketingagreements.edit', compact('model', MarketingAgreement::getLookupVariables()));
    }

    public function update($id, MarketingAgreementFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $maf = MarketingAgreement::allStates()->findOrFail($id);

        if (! $maf->validRequest($request)) {
            flash($maf->INVALID_REQUEST_ERROR, 'danger');

            return redirect()->route('marketingagreements.index');
        }

        if (! $maf->canUpdate) {
            flash($maf->UPDATE_PENDING_ERROR, 'danger');

            return redirect()->route('marketingagreements.index');
        }

        if ($maf->{$maf->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Marketing agreement requests cannot be edited once approved.', 'danger');

            return redirect()->route('marketingagreements.index');
        }

        $formData = MarketingAgreement::modifyFormData($request->partialValidated()->validated, $maf);

        $maf->update($formData);
        if ($maf->{$maf->stateField()} == SignoffStateHelper::INITIAL) {
            $maf = $maf->getLastProposed();
        }
        $maf->extraUpdates($request);
        $maf->uploadFiles($formData);

        if (! $maf->formErrors->allBagsEmpty()) {
            return redirect()->route('marketingagreements.edit', $maf->id)->withInput()->with(['errors' => $maf->formErrors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Marketing agreement form has been saved.', 'success');

            return redirect()->route('marketingagreements.edit', $maf->id);
        } else {
            $maf = $maf->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($maf->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted marketing agreement form for approval.', 'success');

            return redirect()->route('marketingagreements.index');
        }
    }
}
