<?php

namespace App\Http\Controllers;

use App\Actions\Models\SplitInventoryRemovalForSignoffAction;
use App\Datatables\InventoryRemovalsDatatable;
use App\Helpers\SignoffStateHelper;
use App\Http\Requests\InventoryRemovals\InventoryRemovalFormRequest;
use App\Models\Brand;
use App\Models\InventoryRemoval;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InventoryRemovalController extends Controller
{
    public function index(InventoryRemovalsDatatable $datatable)
    {
        $users = User::withTrashed()->whereHas('inventoryRemovals')->select('id', 'name')->ordered()->get();
        $brands = Brand::active()->select('id', 'name')->ordered()->get();

        return $datatable->render('inventoryremovals.index', compact('datatable', 'users', 'brands'));
    }

    public function create()
    {
        $model = new InventoryRemoval;

        return view('inventoryremovals.add', [
            'model' => $model,
        ]);
    }

    public function store(
        InventoryRemovalFormRequest $request,
        SplitInventoryRemovalForSignoffAction $splitInventoryRemovalForSignoffAction
    ) {
        $formData = InventoryRemoval::modifyFormData($request->partialValidated()->validated);
        $model = InventoryRemoval::startSignoff($formData);
        $model->extraUpdates($request);

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Inventory removal has been saved.', 'success');

            return redirect()->route('inventoryremovals.edit', $model->id);
        } else {
            if (! $model->formErrors->allBagsEmpty()) {
                return redirect()->route('inventoryremovals.edit', $model->id)->withInput()->with(['errors' => $model->formErrors]);
            }

            $splitInventoryRemovalForSignoffAction->execute($model);
            flash('Successfully submitted inventory removal for approval.', 'success');

            return redirect()->route('inventoryremovals.index');
        }
    }

    public function show($id)
    {
        $model = InventoryRemoval::withAccess()->allStates()->withEagerLoadedRelations()->withLastSignoff()->findOrFail($id);

        return view('inventoryremovals.show', compact('model'));
    }

    public function edit($id)
    {
        extract(InventoryRemoval::getPreloadedModel($id));
        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('inventoryremovals.index');
        }

        if ($model->isCompletedProposed) {
            return redirect()->route('inventoryremovals.edit', $model->signoff->initial_id);
        }

        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Inventory removals cannot be edited once approved.', 'danger');

            return redirect()->route('inventoryremovals.index');
        }

        return view('inventoryremovals.edit', compact('model', InventoryRemoval::getLookupVariables()));
    }

    public function update(
        $id,
        InventoryRemovalFormRequest $request,
        SplitInventoryRemovalForSignoffAction $splitInventoryRemovalForSignoffAction
    ) {
        $model = InventoryRemoval::withAccess()->allStates()->findOrFail($id);

        if (! $model->validRequest($request)) {
            flash(__('messages.invalid_request_error'), 'danger');

            return redirect()->route('inventoryremovals.index');
        }

        if (! $model->canUpdate) {
            flash(__('messages.update_pending_error'), 'danger');

            return redirect()->route('inventoryremovals.index');
        }

        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            flash('Inventory removals cannot be edited once approved.', 'danger');

            return redirect()->route('inventoryremovals.index');
        }

        $formData = InventoryRemoval::modifyFormData($request->partialValidated()->validated, $model);

        $model->update($formData);
        if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            $model = $model->getLastProposed();
        }
        $model->extraUpdates($request);

        if (! $model->formErrors->allBagsEmpty()) {
            return redirect()->route('inventoryremovals.edit', $model->id)->withInput()->with(['errors' => $model->formErrors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Inventory removal has been saved.', 'success');

            return redirect()->route('inventoryremovals.edit', $model->id);
        } else {
            $splitInventoryRemovalForSignoffAction->execute($model);
            flash('Successfully submitted inventory removal for approval.', 'success');

            return redirect()->route('inventoryremovals.index');
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
        $models = Product::whereIn($type == 'product' ? 'id' : 'catalogue_category_id', $ids)
            ->with([
                'as400WarehouseStock',
                'brand' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->select('id', 'stock_id', 'name', 'name_fr', 'packaging_language', 'brand_id')
            ->get();

        foreach ($models as $model) {
            $stockData = [];
            $warehouses = $model->as400WarehouseStock->sortBy('warehouse')->groupBy('warehouse');
            foreach ($warehouses as $warehouse => $items) {
                $stockData[$warehouse] = [];
                foreach ($items as $item) {
                    $stockData[$warehouse][] = [
                        'unit_cost' => $item->unit_cost,
                        'average_landed_cost' => $item->average_landed_cost,
                        'quantity' => $item->quantity,
                        'expiry' => $item->expiry,
                    ];
                }
            }

            $results[] = [
                'id' => $model->id,
                'stock_id' => $model->stock_id,
                'description' => $model->getName(),
                'brand' => $model->brand->name,
                'stock_data' => empty($stockData) ? null : $stockData,
            ];
        }

        return response()->json($results);
    }
}
