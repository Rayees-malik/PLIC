<?php

namespace App\Http\Controllers;

use App\Datatables\CountriesDatatable;
use App\Http\Requests\Lookups\CountryFormRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $datatable = new CountriesDatatable;

        return $datatable->render('countries.index');
    }

    public function create()
    {
        $model = new Country;

        return view('countries.add', compact('model'));
    }

    public function store(CountryFormRequest $request)
    {
        $validated = $request->validated();
        $country = Country::create($validated);

        flash('Successfully added country', 'success');

        return redirect()->route('countries.index');
    }

    public function edit(Request $request)
    {
        $model = Country::findOrFail($request->id);

        return view('countries.edit', compact('model'));
    }

    public function update(CountryFormRequest $request)
    {
        $validated = $request->validated();
        $country = Country::findOrFail($request->id);

        $country->update($validated);

        flash('Successfully updated country: ' . $country->name, 'success');

        return redirect()->route('countries.index');
    }

    public function destroy(Request $request)
    {
        $country = Country::findOrFail($request->id)->delete();

        flash('Successfully deleted country', 'success');

        return redirect()->route('countries.index');
    }
}
