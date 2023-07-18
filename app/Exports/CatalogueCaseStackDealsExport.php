<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CatalogueCaseStackDealsExport
{
    const REPLACE_PAIRS = [
        '’' => "'",
        "\n" => "\r\n",
        '²' => '2',
        '®' => '',
        '™' => '',
        '—' => '-',
        '–' => '-',
        ' ' => ' ',
        '“' => '',
        '”' => '',
        '½' => '',
        '°' => '',
        '‘' => '',
        '•' => '',
        '…' => '.',
        'è' => '.',
    ];

    public function export(Request $request)
    {
        $period1 = $request->period_id1 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id1) : null;
        $period2 = $request->period_id2 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id2) : null;

        $groceryOnly = $request->grocery_only ?: false;

        $brands = Brand::with([
            'caseStackDeals' => function ($query) use ($period1, $period2) {
                $query->whereIn('period_id', [optional($period1)->id, optional($period2)->id]);
            },
        ])->whereHas('caseStackDeals', function ($query) use ($period1, $period2) {
            $query->whereIn('period_id', [optional($period1)->id, optional($period2)->id]);
        })->whereHas('products', function ($query) use ($groceryOnly) {
            $query->catalogueActive()
                ->when($groceryOnly, function ($query) {
                    $query->whereHas('subcategory', function ($query) {
                        $query->where('grocery', true);
                    });
                });
        })->ordered()->get();

        $exportData = view('partials.exports.cataloguecasestackdeals.casestackdeals')->with([
            'brands' => $brands,
            'period1' => $period1,
            'period2' => $period2,
        ])->render();

        $response = new StreamedResponse;
        $response->setCallback(function () use ($exportData) {
            $file = fopen('php://output', 'w');
            fwrite($file, strtr($exportData, static::REPLACE_PAIRS));
            fclose($file);
        });

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Disposition', 'attachment; filename="casestackdeals_export.txt"');

        return $response->send();
    }
}
