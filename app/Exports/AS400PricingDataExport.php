<?php

namespace App\Exports;

use App\Helpers\PromoHelper;
use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AS400PricingDataExport extends CSVExport
{
    private const REPLACE_PAIRS = [
        '’' => '',
        "\n" => '',
        '²' => '2',
        '®' => '',
        '™' => '',
        '—' => '',
        '–' => '',
        ' ' => ' ',
        '“' => '',
        '”' => '',
        '½' => '',
        '°' => '',
        '‘' => '',
        '•' => '',
        '…' => '',
        'è' => '',
        ' ' => '',
    ];

    private const MONTH_MAP = [
        1 => 'JA',
        2 => 'FE',
        3 => 'MR',
        4 => 'AP',
        5 => 'MA',
        6 => 'JN',
        7 => 'JL',
        8 => 'AU',
        9 => 'SE',
        10 => 'OC',
        11 => 'NO',
        12 => 'DE',
    ];

    public function export(Request $request)
    {
        $periodId = $request->get('period_id');
        if (! $periodId) {
            return redirect()->route('exports.index');
        }

        $includeDisco = $request->boolean('include_disco', false);
        $includeDealSummaryDetail = $request->boolean('include_deal_summary_detail', false);

        $period = PromoPeriod::with('basePeriod')->findOrFail($periodId);
        $basePeriod = $period->basePeriod;

        $startDate = $basePeriod && $basePeriod->start_date < $period->start_date ? $basePeriod->start_date : $period->start_date;
        $withinOneMonth = $period->start_date->format('Y-m') == $period->end_date->format('Y-m');

        $brandIds = Arr::wrap($request->get('brand_id'));

        $brands = Product::query()
            ->when($brandIds, fn ($query) => $query->whereIn('brand_id', $brandIds))
            ->withPromoPricing([$period, $basePeriod], false, $includeDisco, true)
            ->with('discoPromo', 'as400StockData', 'subcategory')
            ->ordered()
            ->get()
            ->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)
            ->groupBy('brand.name');

        $data = [];

        foreach ($brands as $brand => $products) {
            $range = PromoHelper::getDiscountRange($products, $period);

            foreach ($products as $product) {
                if (! $includeDisco && $product->discoPromo) {
                    continue;
                }

                if ($product->getPrice() == 0) {
                    continue;
                }

                $discount = round($product->calculatePromoDiscount($period->id, $startDate, false, true), 2);
                if ($basePeriod && ($discount > 0 || ! $withinOneMonth)) {
                    $discount += round($product->calculatePromoDiscount($basePeriod->id, $startDate, false, false) ?? 0, 2);
                }

                if ($discount > 0) {
                    $data[] = [
                        $basePeriod ? 'CHFA' : 'NEWSLTR',
                        $product->stock_id,
                        $period->start_date->format('m'),
                        '0',
                        number_format($discount),
                        $period->start_date->format('n/j/Y'),
                        $period->end_date->format('n/j/Y'),
                        $includeDealSummaryDetail ? Str::of($product->brand->name)
                            ->upper()
                            ->replaceMatches('/[^A-Z0-9]++/', '')
                            ->substr(0, 10) . static::MONTH_MAP[$period->start_date->format('n')] : '',
                        $includeDealSummaryDetail && $range ? ($range['low'] == $range['high'] ? $range['low'] . '%' : $range['low'] . '% - ' . $range['high'] . '%') : '',
                        $includeDealSummaryDetail && $range ? ($range['line_drive'] ? 'L' : ($range['all_products'] ? 'A' : 'S')) : '',
                        $includeDealSummaryDetail ? ($product->subcategory?->grocery ? 'G' : '') : '',
                    ];
                }
            }
        }

        return $this->downloadFile($data, 'as400pricing.csv');
    }
}
