<?php

namespace App\Http\Controllers;

use App\Datatables\QualityControlRecordsDatatable;
use App\Models\QualityControlRecord;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class QualityControlController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::select('id', 'name', 'number')
            ->where('name', 'not like', 'QC%')
            ->get();

        $datatable = new QualityControlRecordsDatatable;

        return $datatable->render('qc.index', compact('datatable', 'warehouses'));
    }

    public function create(Request $request)
    {
        return view('qc.create');
    }

    public function edit(Request $request, QualityControlRecord $record)
    {
        return view('qc.edit', [
            'record' => $record,
        ]);
    }
}
