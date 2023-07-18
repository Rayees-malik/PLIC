<?php

namespace App\Http\Controllers\QualityControl;

use App\Http\Controllers\Controller;
use App\Models\QualityControlRecord;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class DownloadPrintableForm extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, QualityControlRecord $record)
    {
        $content = view('qc.quality-control-record-print', compact('record'))->render();

        $pdf = Browsershot::html($content)
            ->format('Letter')
            ->margins(18, 18, 24, 18)
            ->showBackground()
            ->pdf();

        $filename = "quality-control_{$record->id}_{$record->product->stock_id}.pdf";

        return response()->stream(function () use ($pdf) {
            echo $pdf;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
