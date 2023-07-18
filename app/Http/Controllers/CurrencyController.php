<?php

namespace App\Http\Controllers;

use App\Datatables\CurrenciesDatatable;
use App\Http\Requests\Lookups\CurrencyFormRequest;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $datatable = new CurrenciesDatatable;

        return $datatable->render('currencies.index', compact('datatable'));
    }

    public function create()
    {
        $model = new Currency;

        return view('currencies.add', [
            'model' => $model,
        ]);
    }

    public function store(CurrencyFormRequest $request)
    {
        $validated = $request->validated();
        Currency::create($validated);

        flash('Successfully added currency', 'success');

        return redirect()->route('currencies.index');
    }

    public function edit(Request $request)
    {
        $model = Currency::findOrFail($request->id);

        return view('currencies.edit', [
            'model' => $model,
        ]);
    }

    public function update(CurrencyFormRequest $request)
    {
        $currency = Currency::findOrFail($request->id);

        $validated = $request->validated();
        $currency->update($validated);

        flash("Successfully updated {$currency->name}", 'success');

        return redirect()->route('currencies.index');
    }

    public function destroy(Request $request)
    {
        Currency::findOrFail($request->id)->delete();

        flash('Successfully deleted currency', 'success');

        return redirect()->route('currencies.index');
    }
}
