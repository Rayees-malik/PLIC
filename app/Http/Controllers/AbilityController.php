<?php

namespace App\Http\Controllers;

use App\Datatables\AbilitiesDatatable;
use App\Http\Requests\Users\AbilityFormRequest;
use Illuminate\Support\Str;
use Silber\Bouncer\BouncerFacade as Bouncer;

class AbilityController extends Controller
{
    public function index()
    {
        $datatable = new AbilitiesDatatable;

        return $datatable->render('abilities.index', compact('datatable'));
    }

    public function create()
    {
        $model = new \App\Models\Ability;

        return view('abilities.add', compact('model'));
    }

    public function store(AbilityFormRequest $request)
    {
        $validated = $request->validated();

        Bouncer::ability()->create([
            'name' => Str::slug($validated['title']),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
        ]);

        flash('Successfully added ability', 'success');

        return redirect()->route('abilities.index');
    }

    public function show($name)
    {
        $model = Bouncer::ability()->where('name', $name)->firstOrFail();

        return view('abilities.show', compact('model'));
    }

    public function edit($name)
    {
        $model = Bouncer::ability()->where('name', $name)->firstOrFail();

        return view('abilities.edit', compact('model'));
    }

    public function update($name, AbilityFormRequest $request)
    {
        $ability = Bouncer::ability()->where('name', $name)->firstOrFail();
        $validated = $request->validated();
        $ability->name = Str::slug($validated['title']);
        $ability->update($validated);

        flash("Successfully updated {$ability->name}", 'success');

        return redirect()->route('abilities.index');
    }

    public function destroy($name)
    {
        Bouncer::ability()->where('name', $name)->first()->delete();

        flash('Successfully deleted ability', 'success');

        return redirect()->route('abilities.index');
    }
}
