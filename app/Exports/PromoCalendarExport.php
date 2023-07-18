<?php

namespace App\Exports;

use App\Helpers\PromoHelper;
use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;

class PromoCalendarExport extends CSVExport
{
    public function export(Request $request)
    {
        $year = $request->year;
        if (! $year) {
            return redirect()->route('exports.index');
        }

        $includeMargin = $request->include_margin;
        $includeCSD = $request->include_csd;
        $includeFrenchCSD = $request->include_french_csd;
        $includeUnpublished = $request->include_unpublished;

        $periods = PromoPeriod::byOwner()->catalogue()->whereYear('start_date', $year)->orderBy('start_date', 'asc')->get();
        $brands = Product::withPromoPricing($periods, true)
            ->active()
            ->forExport()
            ->with([
                'brand' => function ($query) use ($periods) {
                    $query->with(
                        [
                            'brokers',
                            'as400Margin',
                            'currency' => function ($query) {
                                $query->select('id', 'exchange_rate');
                            },
                            'caseStackDeals' => function ($query) use ($periods) {
                                $query->whereIn('period_id', $periods->pluck('id')->toArray());
                            },
                        ])
                        ->select('id', 'name', 'currency_id', 'brand_number', 'unpublished_new_listing_deal', 'business_partner_program')
                        ->withCount(['products' => function ($query) {
                            $query->catalogueActive()
                                ->where('not_for_resale', false)
                                ->whereHas('as400Pricing', function ($query) {
                                    $query->where('wholesale_price', '>', 0);
                                });
                        },
                            'products as grocery_products_count' => function ($query) {
                                $query->catalogueActive()
                                    ->whereHas('subcategory', function ($query) {
                                        $query->where('grocery', true);
                                    });
                            },
                        ]);
                },
            ])
            ->ordered()
            ->get()
            ->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)
            ->groupBy('brand.name');

        $header = ['Brand Number', 'Brand Name', 'BPP', 'Grocery', 'Broker'];

        if ($includeMargin) {
            $header[] = 'Brand Margin';
        }
        if ($includeUnpublished) {
            $header[] = 'Unpublished New Listing Deal';
        }

        foreach ($periods as $period) {
            $header[] = $period->name;

            if ($includeCSD) {
                $header[] = "{$period->name} Case Stack Deals";
            }
            if ($includeFrenchCSD) {
                $header[] = "{$period->name} Case Stack Deals (FR)";
            }
        }

        $data = [$header];
        foreach ($brands as $brandName => $products) {
            $brand = $products->first()->brand;

            $rowData = [$brand->brand_number,
                $brand->name,
                $brand->business_partner_program ? 'Yes' : 'No',
                $brand->grocery_products_count > 0 ? 'Yes' : 'No',
                implode(',', $brand->brokers->pluck('name')->toArray()),
            ];

            if ($includeMargin) {
                $margin = optional($brand->as400Margin)->margin;
                $rowData[] = $margin ? "{$margin}%" : null;
            }
            if ($includeUnpublished) {
                $rowData[] = $brand->unpublished_new_listing_deal;
            }

            foreach ($periods as $period) {
                $discountRange = PromoHelper::getDiscountRange($products, $period);
                $dealSummary = null;
                if ($discountRange && $discountRange['low'] > 0) {
                    $dealSummary = $period->start_date->shortMonthName . ' ';
                    $dealSummary .= ($discountRange['line_drive'] ? 'Line Drive' : ($discountRange['all_products'] ? 'All Products' : 'Selected SKUs')) . ' ';
                    $dealSummary .= $discountRange['low'] == $discountRange['high'] ? "{$discountRange['high']}%" : "{$discountRange['low']}%-{$discountRange['high']}%";
                }

                $rowData[] = $dealSummary;

                if ($includeCSD) {
                    $rowData[] = optional($brand->caseStackDeals->where('period_id', $period->id)->first())->deal;
                }
                if ($includeFrenchCSD) {
                    $rowData[] = optional($brand->caseStackDeals->where('period_id', $period->id)->first())->deal_fr;
                }
            }

            $data[] = $rowData;
        }

        return $this->downloadFile($data, "promo_calendar_{$year}.csv");
    }
}
