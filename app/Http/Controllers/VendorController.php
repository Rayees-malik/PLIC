<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\VendorsDatatable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use YlsIdeas\FeatureFlags\Facades\Features;

class VendorController extends Controller
{
    public function index()
    {
        $datatable = new VendorsDatatable;

        return $datatable->render('vendors.index', compact('datatable'));
    }

    public function create()
    {
        if (auth()->user()->vendor_id && auth()->user()->cannot('admin') && auth()->user()->can('user.assign.vendor')) {
            return redirect()->route('vendors.show', auth()->user()->vendor_id);
        }

        $model = new Vendor;

        extract(Vendor::loadLookups());

        return view('vendors.add', compact('model', Vendor::getLookupVariables()));
    }

    public function show($id, Request $request)
    {
        $model = Vendor::allStates()
            ->withAccess()
            ->withEagerLoadedRelations()
            ->with(['brands' => function ($query) {
                $query->initial()->select('id', 'vendor_id', 'name')->with(['media' => function ($query) {
                    $query->where('collection_name', 'logo');
                }]);
            }])
            ->withLastSignoff()
            ->withApprovedSignoffs()
            ->findOrFail($id);

        return view('vendors.show', compact('model'));
    }

    public function edit($id, Request $request)
    {
        $model = Vendor::allStates()->withAccess()->withEagerLoadedRelations('history')->findOrFail($id);
        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('vendors.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('vendors.edit', $model->signoff->initial_id);
        }

        $proposed = $model->getProposedForUser();
        if ($proposed) {
            return redirect()->route('vendors.edit', $proposed);
        }

        $model->load('signoff.responses.user');
        extract(Vendor::loadLookups($model));

        return view('vendors.edit', compact('model', Vendor::getLookupVariables()));
    }

    public function submit(Request $request, SubmitSignoffAction $submitSignoffAction)
    {
        $model = Vendor::withAccess()->withEagerLoadedRelations('history')->find($request->id);

        if ($model && ! $model->validRequest($request)) {
            flash(__('messages.invalid_request_error'), 'danger');

            return redirect()->route('retailers.index');
        }

        // Double check canUpdate status
        if ($model && ! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('vendors.index');
        }

        $result = Vendor::stepperUpdate($request, true);

        $signoffSubmitData = SignoffSubmitData::fromRequest($request);
        $signoffSubmitData = $signoffSubmitData->forSignoff($result->model->signoff);

        $submitSignoffAction->execute($signoffSubmitData);

        // DEV: Comment out for easier of testing.
        if (! $result->errors->allBagsEmpty()) {
            $model = $result->model;
            $errors = $result->errors;
            extract(Vendor::loadLookups($model));

            return view('vendors.edit', compact('model', 'errors', Vendor::getLookupVariables()));
        }

        // TODO: I don't believe this check is functioning properly
        if (auth()->user()->isVendor && $result->model->brands()->count() == 0) {
            $url = route('brands.create');
            flash("Successfully sent the vendor for approval.<br>You may now <a href=\"{$url}\">add your first brand</a>.", 'success');
        } else {
            flash('Successfully sent the vendor for approval.', 'success');
        }

        return redirect()->route('vendors.index');
    }

    public function backgroundSave(Request $request)
    {
        $result = Vendor::stepperUpdate($request);
        $view = $this->backgroundRenderView($result);

        return response()->json(['view' => $view, 'step' => $result->saved ? null : 0]);
    }

    protected function backgroundRenderView($result)
    {
        if (Features::accessible('remove-session-dependency')) {
            extract(Vendor::loadLookups($result->model));
        } else {
            extract(Session::get($result->model::getSessionRelationsKey()));
        }

        request()->flash();
        $view = view('vendors.form', [
            'model' => $result->model,
            'errors' => $result->errors,
        ])
            ->with(compact(Vendor::getLookupVariables()))
            ->render();
        session(['_old_input' => null]);

        return "<div>{$view}</div>";
    }
}
