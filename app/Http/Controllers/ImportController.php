<?php

namespace App\Http\Controllers;

use App\Http\Requests\PricingUpdateImportFormRequest;
use App\Imports\CustomerGLAccountsImport;
use App\Imports\PricingUpdateImport;
use App\User;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    // Common Exports

    const IMPORTS = [
        'glaccounts',
        'pricing-update',
    ];

    public function index()
    {
        $imports = static::IMPORTS;
        $users = User::query()
            ->select('id', 'name')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['vendor-relations-specialist']);
            })
            ->orderBy('name')
            ->get();

        return view('imports.index', compact('imports', 'users'));
    }

    public function customerGLAccounts(Request $request)
    {
        $file = $request->file('accounts');
        if (! $file || ! $file->isValid() || ! in_array($file->extension(), ['xls', 'csv', 'xlsx'])) {
            flash('Import file was invalid, please try again.', 'danger');

            return redirect()->route('imports.index');
        }

        CustomerGLAccountsImport::import($file);

        return redirect()->route('imports.index');
    }

    public function pricingUpdate(PricingUpdateImportFormRequest $request)
    {
        $file = $request->file('data');
        $submitter = User::find($request->vrs);

        PricingUpdateImport::import($file, $submitter);

        return redirect()->route('imports.index');
    }
}
