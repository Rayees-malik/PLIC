<?php

namespace App\Exports\Retailers;

use App\Exports\BaseExport;
use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Support\Str;

class WFCanadaPromosExport extends BaseExport
{
    const TEMPLATE = 'templates/retailers/wf_canada_promos.xlsx';

    const WHOLESALE_BRANDS = [35, 366, 588];

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

        $spreadsheet = $this->loadFile(static::TEMPLATE);
        $spreadsheet->setActiveSheetIndex(1);

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

            $authorizedBy = array_key_exists('authorized_by', $lineItem->promo->data) ? $lineItem->promo->data['authorized_by'] : 'Purity Life Health Products LP';
            $authorizedEmail = array_key_exists('email', $lineItem->promo->data) ? $lineItem->promo->data['email'] : 'mchapman@puritylife.com';
            $authorizedPhone = array_key_exists('phone', $lineItem->promo->data) ? $lineItem->promo->data['phone'] : '519-853-3511';

            $sbType = array_key_exists('flyer', $lineItem->data) && $lineItem->data['flyer'] ? 'FLYER' : 'TPR/SHELF';
            $sbDollar = array_key_exists('scanback_dollar', $lineItem->data) ? $lineItem->data['scanback_dollar'] : 0;
            $sbPercent = floatval(array_key_exists('scanback_percent', $lineItem->data) ? $lineItem->data['scanback_percent'] : 0);

            $mcbAmount = floatval(array_key_exists('mcb', $lineItem->data) ? $lineItem->data['mcb'] * 1 : 0);
            $wfCost = in_array($product->brand_id, static::WHOLESALE_BRANDS) ? $product->getPrice($startDate, static::PRICE_CODE) : round(optional($product->as400Pricing)->average_landed_cost * 1.113, 2);
            $promoCost = null;
            if ($mcbAmount > 0) {
                $promoCost = round($wfCost * (1 - ($mcbAmount / 100)), 2);
            }

            $data[] = $this->getData($product, $lineItem, $brokers, $wfPeriod, $authorizedBy, $authorizedEmail, $authorizedPhone, $mcbAmount, $sbType, $sbDollar, $sbPercent, $wfCost, $promoCost, $isBar, false);
            if ($isBar) {
                $data[] = $this->getData($product, $lineItem, $brokers, $wfPeriod, $authorizedBy, $authorizedEmail, $authorizedPhone, $mcbAmount, $sbType, $sbDollar, $sbPercent, $wfCost, $promoCost, false, true);
            }
        }

        $spreadsheet->getActiveSheet()->fromArray($data, null, 'A4');

        return $this->downloadFile($spreadsheet, 'wf-promos-' . Str::slug($period->name) . '.xlsx');
    }

    public function getData($product, $lineItem, $brokers, $wfPeriod, $authorizedBy, $authorizedEmail, $authorizedPhone, $mcbAmount, $sbType, $sbDollar, $sbPercent, $wfCost, $promoCost, $barRow, $boxRow)
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
            null,
            null, // Calculated
            null,
            // $sbType,
            $lineItem->data['notes'] ?? $lineItem->promo->data['header_notes'],
            // $product->1retailerListing ? $product->retailerListing->data['dept'] : null,
            substr($product->upc, 0, -1),
            $product->brand->name,
            $productName,
            $product->stock_id,
            $boxRow ? 1 : $product->caseSize,
            $boxRow ? 1 : round($product->size, 2),
            $boxRow ? 'CT' : strtoupper(optional($product->uom)->unit ?? 'CT'),
            null, // Skip: OGD Discount
            null, // Skip: Promo OI
            round($mcbAmount / 100, 2),
            $sbDollar,
            round($sbPercent / 100, 2),
            $wfCost,
            $promoCost,
            $authorizedBy,
            $authorizedEmail,
            $authorizedPhone,
        ];
    }
}
