<?php

namespace App\Exports\Retailers;

use App\Exports\BaseExport;
use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Support\Str;

class WFPromosExport extends BaseExport
{
    const EXPORT_TEMPLATE = [
        18 => 'templates/retailers/wf_east_promos.xlsx',
        19 => 'templates/retailers/wf_west_promos.xlsx',
    ];

    const WHOLESALE_BRANDS = [
        // Antipodes
        35 => 0.84,
        // Kyolic
        366 => 0.75,
        // Pacifica
        549 => 1,
        // Quantum
        588 => 0.85,
    ];

    const PRICE_CODE = 'WFM-COST';

    public function export($retailer, $request)
    {
        $periodId = $request->get('period_id');
        if (! $periodId) {
            return redirect()->route('retailers.exports', ['id' => $retailer->id]);
        }

        $period = PromoPeriod::with('basePeriod')->findOrFail($periodId);
        $basePeriod = $period->basePeriod;

        $startDate = $basePeriod && $basePeriod->start_date < $period->start_date ? $basePeriod->start_date : $period->start_date;

        $products = Product::forExport()
            ->withPromoPricing([$period, $basePeriod])
            ->with([
                'uom',
                'category',
                'subcategory',
                'brand' => function ($query) {
                    $query->with(['brokers', 'currency' => function ($query) {
                        $query->select('id', 'exchange_rate');
                    }])->select('id', 'name', 'currency_id');
                },
                'retailerListings' => function ($query) use ($retailer) {
                    $query->where('retailer_id', $retailer->id);
                },
            ])->ordered()->get()->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE);

        $spreadsheet = $this->loadFile(static::EXPORT_TEMPLATE[$retailer->id]);
        $spreadsheet->setActiveSheetIndex(2);

        $data = [];
        foreach ($products as $product) {
            $lineItem = $product->getPromoLineItem($period, true);
            if (! $lineItem) {
                continue;
            }

            $isBar = str_contains(strtolower(optional($product->subcategory)->name), 'bar')
                && (str_contains(strtolower(optional($product->category)->name), 'food') || str_contains(strtolower(optional($product->category)->name), 'supplement'));

            $brokers = implode(', ', $product->brand->brokers->pluck('name')->toArray());
            $wfPeriod = array_key_exists('promo_period', $lineItem->promo->data) ? $lineItem->promo->data['promo_period'] : 'BOTH';

            $sbType = array_key_exists('flyer', $lineItem->data) && $lineItem->data['flyer'] ? 'FLYER' : 'TPR/SHELF';
            $sbDollar = array_key_exists('scanback_dollar', $lineItem->data) ? $lineItem->data['scanback_dollar'] : 0;
            $sbPercent = floatval(array_key_exists('scanback_percent', $lineItem->data) ? $lineItem->data['scanback_percent'] : 0);

            $mcbAmount = floatval(array_key_exists('mcb', $lineItem->data) ? $lineItem->data['mcb'] * 1 : 0);
            $wfCost = array_key_exists($product->brand_id, static::WHOLESALE_BRANDS) ? round($product->getPrice($startDate, static::PRICE_CODE) * static::WHOLESALE_BRANDS[$product->brand_id], 2) : round($product->landed_cost * 1.113, 2);
            $promoCost = null;
            if ($mcbAmount > 0) {
                $promoCost = round($wfCost * (1 - ($mcbAmount / 100)), 2);
            }

            $data[] = $this->getData($product, $lineItem, $brokers, $wfPeriod, $mcbAmount, $sbType, $sbDollar, $sbPercent, $wfCost, $promoCost, $isBar, false);
            if ($isBar) {
                $data[] = $this->getData($product, $lineItem, $brokers, $wfPeriod, $mcbAmount, $sbType, $sbDollar, $sbPercent, $wfCost, $promoCost, false, true);
            }
        }

        $spreadsheet->getActiveSheet()->fromArray($data, null, 'A4');

        return $this->downloadFile($spreadsheet, 'wf-promos-' . Str::slug($period->name) . '.xlsx');
    }

    public function getData($product, $lineItem, $brokers, $wfPeriod, $mcbAmount, $sbType, $sbDollar, $sbPercent, $wfCost, $promoCost, $barRow, $boxRow)
    {
        $productName = $product->getName();
        $productName .= $barRow ? ' Bar' : '';
        $productName .= $boxRow ? ' Box' : '';

        return [
            $brokers,
            strtoupper($lineItem->promo->period->start_date->format('M')),
            $wfPeriod,
            $lineItem->promo->period->start_date->format('d-m-Y'),
            $lineItem->promo->period->end_date->format('d-m-Y'),
            null, null, // Calculated
            null, null, null, null, null, // Hidden
            $sbType,
            null, null, // Hidden
            $product->retailerListing ? $product->retailerListing->data['dept'] : null,
            null, // Hidden
            $product->brand->name,
            null, null, null, // Hidden
            substr($product->soldByCase && ! $barRow ? $product->caseUPC : $product->upc, 0, -1),
            null, // Hidden
            $productName,
            $product->stock_id,
            1,
            $boxRow ? 1 : $product->caseSize,
            $boxRow ? 1 : round($product->size, 2),
            $boxRow ? 'CT' : strtoupper(optional($product->uom)->unit ?? 'CT'),
            null, null, null, // Hidden
            null, // Skip: OGD Discount
            null, null, null, null, null, null, null, null, // Hidden
            null, // Skip: Promo OI
            round($mcbAmount / 100, 2),
            $sbDollar,
            round($sbPercent / 100, 2),
            null, null, null, null, null, null, null, null, null, // Hidden
            $wfCost,
            $promoCost,
            null, null, null, null, null, null, null, null, null, null, // Hidden
            null, null, null, null, null, null, null, null, null, null, // Hidden
            null, null, null, null, null, null, null, null, null, null, // Hidden
            null, null, null, null, null, null, null, null, // Hidden
            'Purity Life Health Products LP',
            'mchapman@puritylife.com',
            '519-853-3511',
        ];
    }
}
