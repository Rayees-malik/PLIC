<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\RetailersDatatable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Exports\Retailers\WFPromosExport;
use App\Http\Requests\Retailers\RetailerFormRequest;
use App\Imports\RetailerListingsImport;
use App\Models\PromoPeriod;
use App\Models\Retailer;
use App\Traits\PromoOwnerController;
use Illuminate\Http\Request;

class RetailerController extends Controller
{
    use PromoOwnerController;

    public function index(RetailersDatatable $datatable)
    {
        // Render datatable
        return $datatable->render('retailers.index');
    }

    public function create()
    {
        $model = new Retailer;
        extract(Retailer::loadLookups($model));

        return view('retailers.add', compact('model', Retailer::getLookupVariables()));
    }

    public function show($id, Request $request)
    {
        $model = Retailer::withAccess()->allStates()->withEagerLoadedRelations()->findOrFail($id);

        return view('retailers.show', [
            'model' => $model,
        ]);
    }

    public function edit($id, Request $request)
    {
        $model = Retailer::withAccess()->allStates()->withEagerLoadedRelations('history')->findOrFail($id);
        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('retailers.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('retailers.edit', $model->signoff->initial_id);
        }

        $proposed = $model->getProposedForUser();
        if ($proposed) {
            return redirect()->route('retailers.edit', $proposed);
        }

        $model->load('signoff.responses.user');
        extract(Retailer::loadLookups($model));

        return view('retailers.edit', compact('model', Retailer::getLookupVariables()));
    }

    public function update($id, RetailerFormRequest $request)
    {
        $retailer = Retailer::allStates()->findOrFail($id);

        $validated = $request->validated();
        $retailer->update($validated);

        flash("Successfully updated {$retailer->name}.", 'success');

        return redirect()->route('retailers.index');
    }

    public function getModel($id)
    {
        return Retailer::allStates()->select('id', 'name')->findOrFail($id);
    }

    public function submit(Request $request, SubmitSignoffAction $submitSignoffAction)
    {
        $model = Retailer::withAccess()->allStates()->withEagerLoadedRelations('history')->findOrFail($request->id);

        if ($model && ! $model->validRequest($request)) {
            flash(__('messages.invalid_request_error'), 'danger');

            return redirect()->route('retailers.index');
        }

        // Double check canUpdate status
        if ($model && ! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('retailers.index');
        }

        $result = Retailer::stepperUpdate($request, true);

        $signoffSubmitData = SignoffSubmitData::fromRequest($request);
        $signoffSubmitData = $signoffSubmitData->forSignoff($result->model->signoff);

        $submitSignoffAction->execute($signoffSubmitData);

        // DEV: Comment out for easier of testing.
        if (! $result->errors->allBagsEmpty()) {
            $model = $result->model;
            $errors = $result->errors;
            extract(Retailer::loadLookups($model));

            return view('retailers.edit', compact('model', 'errors', Retailer::getLookupVariables()));
        }

        flash('Successfully saved retailer.', 'success');

        return redirect()->route('retailers.index');
    }

    public function backgroundSave(Request $request)
    {
        $result = Retailer::stepperUpdate($request);
        $view = $this->backgroundRenderView($result);

        return response()->json(['view' => $view, 'step' => $result->saved ? null : 0]);
    }

    // public function destroy($id)
    // {
    //     $model = Retailer::allStates()->findOrFail($id);

    //     if ($model->{$model->stateField()} == SignoffStateHelper::IN_PROGRESS || auth()->user()->can('delete', Retailer::class)) {
    //         $model->delete();
    //         flash('Retailer was successfully deleted.', 'success');
    //     } else {
    //         flash('You do not have sufficient permissions to delete this retailer.', 'danger');
    //     }

    //     return redirect()->route('retailer.index');
    // }

    public function imports($id)
    {
        $model = Retailer::withAccess()->select('id', 'name')->findOrFail($id);

        return view('retailers.imports', [
            'model' => $model,
        ]);
    }

    public function importListings($id, Request $request)
    {
        $model = Retailer::withAccess()->select('id')->findOrFail($id);

        $file = $request->file('listings');
        if (! $file || ! $file->isValid() || ! in_array($file->extension(), ['xls', 'csv', 'xlsx'])) {
            flash('Import file was invalid, please try again.', 'danger');

            return redirect()->route('retailers.imports', ['id' => $id]);
        }

        RetailerListingsImport::import($id, $file);

        return redirect()->route('retailers.imports', ['id' => $id]);
    }

    public function exports($id)
    {
        $model = Retailer::withAccess()->select('id', 'name')->findOrFail($id);
        $promoPeriods = PromoPeriod::byOwner($model)
            ->sinceMonthsAgo(3)
            ->select('id', 'name', 'start_date', 'end_date')
            ->ordered()
            ->get();

        return view('retailers.exports', [
            'model' => $model,
            'promoPeriods' => $promoPeriods,
        ]);
    }

    public function wfPromoExport($id, Request $request)
    {
        $model = Retailer::withAccess()->select('id', 'name')->findOrFail($id);

        $export = new WFPromosExport;

        return $export->export($model, $request);
    }

    public function wfCanadaPromoExport($id, Request $request)
    {
        $model = Retailer::withAccess()->select('id', 'name')->findOrFail($id);

        $export = new \App\Exports\Retailers\WFCanadaPromosExport;

        return $export->export($model, $request);
    }

    public function nfPromoExport($id, Request $request)
    {
        $model = Retailer::withAccess()->select('id', 'name')->findOrFail($id);

        $export = new \App\Exports\Retailers\NFPromosExport;

        return $export->export($model, $request);
    }

    public function defaultPromoExport($id, Request $request)
    {
        $model = Retailer::withAccess()->select('id', 'name')->findOrFail($id);

        $export = new \App\Exports\Retailers\DefaultPromosExport;

        return $export->export($model, $request);
    }

    protected function backgroundRenderView($result)
    {
        extract(Retailer::loadLookups($result->model));
        // extract(\Session::get($result->model->getSessionRelationsKey()));

        request()->flash();
        $view = view('retailers.form', [
            'signoffForm' => true, // TODO: Why?
            'model' => $result->model,
            'errors' => $result->errors,
        ])->with(compact(Retailer::getLookupVariables()))->render();
        session(['_old_input' => null]);

        return "<div>{$view}</div>";
    }
}
