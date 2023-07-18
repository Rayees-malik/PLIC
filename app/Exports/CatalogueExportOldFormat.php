<?php

namespace App\Exports;

use App\Helpers\PromoHelper;
use App\Models\Brand;
use App\Models\PromoPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CatalogueExportOldFormat
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
    ];

    public static function export(Request $request)
    {
        $period1 = $request->period_id1 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id1) : null;
        $period2 = $request->period_id2 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id2) : null;

        $brandIds = empty($request->brand_id) ? null : Arr::wrap($request->brand_id);
        $english = $request->language !== 'F';
        $groceryOnly = $request->grocery_only ?: false;
        $newOnly = $request->new_only ?: false;
        $excludeDisco = $request->exclude_disco ?: false;
        $excludeSuperseded = $request->exclude_superseded ?: false;

        $startDate1 = $period1->basePeriod && $period1->basePeriod->start_date < $period1->start_date ? $period1->basePeriod->start_date : $period1->start_date;
        $startDate2 = $period2 ? ($period2->basePeriod && $period2->basePeriod->start_date < $period2->start_date ? $period2->basePeriod->start_date : $period2->start_date) : null;
        $startDate = $startDate2 && $startDate2 < $startDate1 ? $startDate2 : $startDate1;

        $cutoffStart = new Carbon($startDate);
        $cutoffStart->subMonths(4);
        $cutoffStart->day = 20;

        $cutoffEnd = new Carbon($startDate);
        $cutoffEnd->subMonths(2);
        $cutoffEnd->day = 20;

        $brands = Brand::with([
            'brokers' => function ($query) {
                $query->select('id', 'name')->ordered();
            },
            'catalogueCategories' => function ($query) {
                $query->ordered()->select('id', 'brand_id', 'sort', 'name', 'name_fr', 'description', 'description_fr');
            },
            'products' => function ($query) use ($period1, $period2, $groceryOnly, $newOnly, $cutoffStart, $cutoffEnd, $excludeDisco, $excludeSuperseded) {
                $query->forExport()
                    ->withPromoPricing([$period1, $period1->basePeriod, $period2, optional($period2)->basePeriod], true, true)
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
                        $query->whereDate('listed_on', '>=', $cutoffStart)
                            ->whereDate('listed_on', '<=', $cutoffEnd);
                    })
                    ->when($excludeDisco, function ($query) {
                        $query->whereDoesntHave('as400StockData', function ($query) {
                            $query->where('status', 'D');
                        });
                    })
                    ->when($excludeSuperseded, function ($query) {
                        $query->whereDoesntHave('as400StockData', function ($query) {
                            $query->where('status', 'S');
                        });
                    })
                    ->ordered();
            },
            'caseStackDeals' => function ($query) use ($period1, $period2) {
                $query->whereIn('period_id', [optional($period1)->id, optional($period2)->id]);
            },
        ])->withCount([
            'products as grocery_count' => function ($query) {
                $query->catalogueActive()->whereHas('subcategory', function ($query) {
                    $query->where('grocery', true);
                });
            },
        ])->whereHas('products', function ($query) use ($groceryOnly, $newOnly, $cutoffStart, $cutoffEnd, $excludeDisco, $excludeSuperseded) {
            $query->catalogueActive()
                ->when($newOnly, function ($query) use ($cutoffStart, $cutoffEnd) {
                    $query->whereDate('listed_on', '>=', $cutoffStart)
                        ->whereDate('listed_on', '<=', $cutoffEnd);
                })
                ->when($groceryOnly, function ($query) {
                    $query->whereHas('subcategory', function ($query) {
                        $query->where('grocery', true);
                    });
                })
                ->when($excludeDisco, function ($query) {
                    $query->whereDoesntHave('as400StockData', function ($query) {
                        $query->where('status', 'D');
                    });
                })
                ->when($excludeSuperseded, function ($query) {
                    $query->whereDoesntHave('as400StockData', function ($query) {
                        $query->where('status', 'S');
                    });
                });
        })->when($brandIds, function ($query) use ($brandIds) {
            $query->whereIn('id', $brandIds);
        })->ordered()->get();

        foreach ($brands as $brand) {
            $range1 = $period1 ? PromoHelper::getDiscountRange($brand->products, $period1) : null;
            $range2 = $period2 ? PromoHelper::getDiscountRange($brand->products, $period2) : null;

            $minRange = array_diff([Arr::get($range1, 'low'), Arr::get($range2, 'low')], [null]);
            $maxRange = array_diff([Arr::get($range1, 'high'), Arr::get($range2, 'high')], [null]);
            $lowDiscount = count($minRange) ? round(min($minRange)) : 0;
            $highDiscount = count($maxRange) ? round(max($maxRange)) : 0;

            $saveMessage = '';
            if ($highDiscount > 0) {
                $saveMessage = "<CharStyle:><ParaStyle:SELECT SKU SALE bar_final><CharStyle:SAVE UP TO>\t" . ($english ? 'SAVE ' : 'ÉCONOMISEZ ');
                if ($lowDiscount !== $highDiscount) {
                    $saveMessage .= $english ? 'UP TO ' : "JUSQU' ";
                }
                $saveMessage .= "{$highDiscount}%<CharStyle:Sale heading bold white>\t";

                if ($range1) {
                    if ($range1['line_drive']) {
                        $saveMessage .= $english ? 'LINE DRIVE ' : 'TOUTE LA GAMME ';
                    } elseif ($range1['all_products']) {
                        $saveMessage .= $english ? 'ALL ITEMS ' : 'TOUS LES PRODUITS ';
                    } else {
                        $saveMessage .= $english ? 'SELECT ITEMS ' : 'PRODUITS SÉLECTIONNÉS ';
                    }

                    $saveMessage .= strtoupper($english ? $period1->start_date->shortMonthName : $period1->start_date->locale('fr')->shortMonthName);
                }
                if ($range2) {
                    $saveMessage .= $range1 ? ' & ' : '';
                    if (! $range1 || ($range1['line_drive'] != $range2['line_drive'] && $range1['all_products'] != $range2['all_products'])) {
                        if ($range2['line_drive']) {
                            $saveMessage .= $english ? 'LINE DRIVE ' : 'TOUTE LA GAMME ';
                        } elseif ($range2['all_products']) {
                            $saveMessage .= $english ? 'ALL ITEMS ' : 'TOUS LES PRODUITS ';
                        } else {
                            $saveMessage .= $english ? 'SELECT ITEMS ' : 'PRODUITS SÉLECTIONNÉS ';
                        }
                    }

                    $saveMessage .= strtoupper($english ? $period2->start_date->shortMonthName : $period2->start_date->locale('fr')->shortMonthName);
                }
            }

            $brand->saveUpTo = $saveMessage;

            foreach ($brand->catalogueCategories as $category) {
                $category->products = $brand->products->where('catalogue_category_id', $category->id);
            }
        }

        $exportData = view('partials.exports.catalogueold.catalogue')->with([
            'brands' => $brand,
            'period1' => $period1,
            'period2' => $period2,
            'startDate' => $startDate,
            'cutoffStart' => $cutoffStart,
            'cutoffEnd' => $cutoffEnd,
            'english' => $english,
            'newOnly' => $newOnly,
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
