<?php

namespace App\Http\Controllers\QualityControl;

use App\Http\Controllers\Controller;
use App\Models\QualityControlRecord;
use Illuminate\Http\Request;

class ShowLabellingForm extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, QualityControlRecord $record)
    {
        return view('qc.labelling-form', compact('record'));
    }
}
