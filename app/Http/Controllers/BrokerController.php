<?php

namespace App\Http\Controllers;

use App\Datatables\BrokersDatatable;
use App\Http\Requests\Lookups\BrokerFormRequest;
use App\Models\Broker;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    public function index()
    {
        $datatable = new BrokersDatatable;

        return $datatable->render('brokers.index', compact('datatable'));
    }

    public function create()
    {
        $model = new Broker;
        extract(Broker::loadLookups());

        return view('brokers.edit', compact('model', Broker::getLookupVariables()));
    }

    public function store(BrokerFormRequest $request)
    {
        $validated = $request->validated();
        $model = new Broker;
        $model->fill($validated);
        $model->save();
        $model->extraUpdates($request);

        flash('Successfully created new broker.', 'success');

        return redirect()->route('brokers.index');
    }

    public function show($id, Request $request)
    {
        $model = Broker::with('brands')->findOrFail($id);

        return view('brokers.show', compact('model'));
    }

    public function edit($id, Request $request)
    {
        $model = Broker::with('brands')->findOrFail($id);
        extract(Broker::loadLookups());

        return view('brokers.edit', compact('model', Broker::getLookupVariables()));
    }

    public function update($id, BrokerFormRequest $request)
    {
        $model = Broker::findOrFail($id);

        $validated = $request->validated();
        $model->update($validated);
        $model->extraUpdates($request);

        flash("Successully updated {$model->displayName}", 'success');

        return redirect()->route('brokers.index');
    }

    public function destroy(Request $request)
    {
        Broker::findOrFail($request->id)->delete();

        flash('Successfully deleted broker', 'success');

        return redirect()->route('brokers.index');
    }
}
