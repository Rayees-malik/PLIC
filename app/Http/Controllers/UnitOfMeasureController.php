<?php

namespace App\Http\Controllers;

use App\Datatables\UnitOfMeasureDatatable;
use App\Http\Requests\Lookups\UnitOfMeasureFormRequest;
use App\Models\UnitOfMeasure;

class UnitOfMeasureController extends Controller
{
    public function index()
    {
        $datatable = new UnitOfMeasureDatatable;

        return $datatable->render('uom.index', compact('datatable'));
    }

    public function create()
    {
        $model = new UnitOfMeasure;

        return view('uom.add', compact('model'));
    }

    public function store(UnitOfMeasureFormRequest $request)
    {
        $validated = $request->validated();
        $model = UnitOfMeasure::create($validated);

        flash('Successfully added UOM', 'success');

        return redirect()->route('uom.index');
    }

    public function edit($id)
    {
        $model = UnitOfMeasure::findOrFail($id);

        return view('uom.edit', compact('model'));
    }

    public function update(UnitOfMeasureFormRequest $request)
    {
        $model = UnitOfMeasure::findOrFail($request->id);
        $validated = $request->validated();
        $model->update($validated);

        flash("Successfully updated {$model->description}", 'success');

        return redirect()->route('uom.index');
    }

    public function destroy($id)
    {
        $model = UnitOfMeasure::findOrFail($id)->delete();

        flash('Successfully deleted UOM', 'success');

        return redirect()->route('uom.index');
    }
}
