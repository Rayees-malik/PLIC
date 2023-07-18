<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressFormRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function create()
    {
        return view('address.add');
    }

    public function store(AddressFormRequest $request)
    {
        $validated = $request->validated();
        $address = Address::create($validated);

        flash('Successfully created address', 'success');

        return redirect()->route('home');
    }

    public function update(AddressFormRequest $request)
    {
        $address = Address::findOrFail($request->id);

        $validated = $request->validated();
        $address->update($validated);

        flash('Successfully updated address: ' . $address->address, 'success');

        return redirect()->route('home');
    }

    public function destroy(Request $request)
    {
        $address = Address::findOrFail($request->id)->delete();

        flash('Successfully deleted address', 'success');

        return redirect()->route('home');
    }
}
