<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CatalogueExport
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
        'è' => 'e',
        'é' => 'e',
        '†' => '',
        '√' => '',
    ];

    public static function export(Request $request)
    {
        $period1 = $request->period_id1 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id1) : null;
        $period2 = $request->period_id2 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id2) : null;
        $period3 = $request->period_id3 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id3) : null;

        $brandIds = empty($request->brand_id) ? null : Arr::wrap($request->brand_id);
        $english = $request->language !== 'F';
        $groceryOnly = $request->grocery_only ?: false;

        $startDate1 = $period1->basePeriod && $period1->basePeriod->start_date < $period1->start_date ? $period1->basePeriod->start_date : $period1->start_date;
        $startDate2 = $period2 ? ($period2->basePeriod && $period2->basePeriod->start_date < $period2->start_date ? $period2->basePeriod->start_date : $period2->start_date) : null;
        $startDate = $startDate2 && $startDate2 < $startDate1 ? $startDate2 : $startDate1;

        $cutoffStart = $request->date_start;
        $cutoffEnd = $request->date_end;

        $newOnly = $cutoffStart || $cutoffEnd;

        $brands = Brand::with([
            'brokers' => function ($query) {
                $query->select('id', 'name')->ordered();
            },
            'catalogueCategories' => function ($query) {
                $query->ordered()->select('id', 'brand_id', 'sort', 'name', 'name_fr', 'description', 'description_fr');
            },
            'products' => function ($query) use ($period1, $period2, $period3, $groceryOnly, $newOnly, $cutoffStart, $cutoffEnd) {
                $query->forExport()
                    ->withPromoPricing([$period1, $period1->basePeriod, $period2, optional($period2)->basePeriod, $period3, optional($period3)->basePeriod], true, true)
                    ->with([
                        'uom',
                        'allergens' => function ($query) {
                            $query->wherePivot('contains', '>', '-1');
                        },
                        'certifications',
                        'catalogueCategory' => function ($query) {
                            $query->select('id', 'sort');
                        },
                        'subcategory' => function ($query) {
                            $query->select('id', 'name', 'grocery');
                        },
                    ])
                    ->when($groceryOnly, function ($query) {
                        $query->whereHas('subcategory', function ($query) {
                            $query->where('grocery', true);
                        });
                    })
                    ->when($newOnly, function ($query) use ($cutoffStart, $cutoffEnd) {
                        if ($cutoffStart && ! $cutoffEnd) {
                            return $query->whereDate('listed_on', '>=', $cutoffStart);
                        }

                        if (! $cutoffStart && $cutoffEnd) {
                            return $query->whereDate('listed_on', '<=', $cutoffEnd);
                        }

                        if ($cutoffStart && $cutoffEnd) {
                            return $query->whereDate('listed_on', '>=', $cutoffStart)
                                ->whereDate('listed_on', '<=', $cutoffEnd);
                        }

                        return $query;
                    })
                    ->ordered();
            },
        ])->withCount([
            'products as grocery_count' => function ($query) {
                $query->catalogueActive()->whereHas('subcategory', function ($query) {
                    $query->where('grocery', true);
                });
            },
        ])->whereHas('products', function ($query) use ($groceryOnly, $newOnly, $cutoffStart, $cutoffEnd) {
            $query->catalogueActive()
                ->when($newOnly, function ($query) use ($cutoffStart, $cutoffEnd) {
                    if ($cutoffStart && ! $cutoffEnd) {
                        return $query->whereDate('listed_on', '>=', $cutoffStart);
                    }

                    if (! $cutoffStart && $cutoffEnd) {
                        return $query->whereDate('listed_on', '<=', $cutoffEnd);
                    }

                    if ($cutoffStart && $cutoffEnd) {
                        return $query->whereDate('listed_on', '>=', $cutoffStart)
                            ->whereDate('listed_on', '<=', $cutoffEnd);
                    }

                    return $query;
                })
                ->when($groceryOnly, function ($query) {
                    $query->whereHas('subcategory', function ($query) {
                        $query->where('grocery', true);
                    });
                });
        })->when($brandIds, function ($query) use ($brandIds) {
            $query->whereIn('id', $brandIds);
        })->ordered()->get();

        foreach ($brands as $brand) {
            foreach ($brand->catalogueCategories as $category) {
                $category->products = $brand->products->where('catalogue_category_id', $category->id);
            }
        }

        $exportData = view('partials.exports.catalogue.catalogue')->with([
            'brands' => $brands,
            'period1' => $period1,
            'period2' => $period2,
            'period3' => $period3,
            'startDate' => $startDate,
            'cutoffStart' => $cutoffStart,
            'cutoffEnd' => $cutoffEnd,
            'english' => $english,
        ])->render();

        $response = new StreamedResponse;
        $response->setCallback(function () use ($exportData) {
            $file = fopen('php://output', 'w');
            fwrite($file, strtr($exportData, static::REPLACE_PAIRS));
            fclose($file);
        });

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Disposition', 'attachment; filename="catalogue_export.txt"');

        return $response->send();
    }
}
