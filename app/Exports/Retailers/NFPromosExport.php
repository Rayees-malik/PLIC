<?php

namespace App\Exports\Retailers;

use App\Exports\BaseExport;
use App\Models\Product;
use App\Models\PromoPeriod;
use App\Models\RetailerListing;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\Color;

class NFPromosExport extends BaseExport
{
    protected $priceCode = 'FARE';

    public function export($retailer, Request $request)
    {
        $periodId = $request->get('period_id');
        if (! $periodId) {
            return redirect()->route('retailers.exports', ['id' => $retailer->id]);
        }

        $period = PromoPeriod::with('basePeriod')->findOrFail($periodId);
        $basePeriod = $period->basePeriod;

        $startDate = $basePeriod && $basePeriod->start_date < $period->start_date ? $basePeriod->start_date : $period->start_date;

        $productBrands = Product::forExport()
            ->withPromoPricing([$period, $basePeriod])
            ->with([
                'uom',
                'category',
                'subcategory',
                'brand' => function ($query) use ($startDate) {
                    $query->with([
                        'currency' => function ($query) {
                            $query->select('id', 'exchange_rate');
                        },
                        'as400SpecialPricing' => function ($query) use ($startDate) {
                            $query->byCode($this->priceCode)->forDate($startDate);
                        },
                    ])->select('id', 'name', 'currency_id');
                },
                'retailerListings' => function ($query) use ($retailer) {
                    $query->where('retailer_id', $retailer->id);
                },
                'as400SpecialPricing' => function ($query) use ($startDate) {
                    $query->byCode($this->priceCode)->forDate($startDate);
                },
            ])->ordered()->get()->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)->groupBy('brand.name');

        $spreadsheet = $this->loadFile('templates/retailers/nf_promos.xlsx');
        $spreadsheet->setActiveSheetIndex(0);

        $listedSheet = $spreadsheet->getSheet(0);
        $unlistedSheet = $spreadsheet->getSheet(1);

        $listedSheet->getCell('A7')->setValue($period->name);
        $unlistedSheet->getCell('A7')->setValue($period->name);

        $listedReds = [];
        $mixMatchPromoCount = [];

        $listedData = [];
        $unlistedData = [];
        foreach ($productBrands as $products) {
            $products = $products->sortBy('stock_id');

            foreach ($products as $product) {
                $lineItem = $product->getPromoLineItem($period, true);

                if (! $lineItem) {
                    continue;
                }

                $lineItem = $product->getPromoLineItem($period);
                $types = Arr::get($lineItem, 'data.types') ?? [];

                $ogd = $product->getOGD($this->priceCode, $startDate);
                $retailerListing = $product->retailerListings->first() ?? [];

                $wholesale = $product->getPrice($startDate);
                $ogdDiscount = $ogd['discount'] > 0 ? $ogd['discount'] : 0;
                $ogdPrice = $ogd['price'] > 0 ? $ogd['price'] : $wholesale;
                if ($ogdDiscount) {
                    $ogdPrice = round(($ogdPrice > 0 ? $ogdPrice : $wholesale) * (1 - $ogdDiscount), 2);
                }

                $mcb = $lineItem->brand_discount / 100;
                $promoPrice = round(($ogdPrice > 0 ? $ogdPrice : $wholesale) * (1 - $mcb), 2);

                $data = [
                    'PUR001',
                    $lineItem->promo->period->start_date->toDateString(),
                    $lineItem->promo->period->end_date->toDateString(),
                    $product->stock_id,
                    $retailerListing ? Arr::get($retailerListing, 'data.mixmatch') : null,
                    substr($product->upc, 0, -1),
                    $retailerListing ? Arr::get($retailerListing, 'data.description', $product->name) : $product->name,
                    $product->size,
                    $product->uom->unit,
                    $wholesale,
                    $promoPrice,
                    $ogdDiscount,
                    $ogdPrice,
                    $mcb,
                    in_array('Flyer Ad', $types) ? 'Y' : null,
                    in_array('In-store Feature', $types) ? 'Y' : null,
                    in_array('In-store Demo', $types) ? 'Y' : null,
                    Arr::get($lineItem, 'data.notes'),
                ];

                if ($retailerListing) {
                    $listedData[] = $data;

                    if ($ogdPrice && Arr::get($retailerListing, 'data.net-unit-cost') != $ogdPrice) {
                        $listedReds[] = count($listedData);
                    }

                    $mixMatch = Arr::get($retailerListing, 'data.mixmatch');
                    if ($mixMatch && $mixMatch != '0') {
                        if (! array_key_exists($mixMatch, $mixMatchPromoCount)) {
                            $mixMatchPromoCount[$mixMatch] = 0;
                        }
                        $mixMatchPromoCount[$mixMatch] += 1;
                    }
                } else {
                    $unlistedData[] = $data;
                }
            }
        }

        // Price Differences
        foreach ($listedReds as $row) {
            $listedSheet->getStyle('M' . strval($row + 9))->getFont()->getColor()->setARGB(Color::COLOR_RED);
        }

        // Mix/Match
        $mixMatchCount = [];
        $retailerListings = RetailerListing::where('retailer_id', $retailer->id)->where('data->mixmatch', '<>', 0)->pluck('data');
        foreach ($retailerListings as $data) {
            $mixMatch = $data['mixmatch'];

            if (! array_key_exists($mixMatch, $mixMatchCount)) {
                $mixMatchCount[$mixMatch] = 0;
            }
            $mixMatchCount[$mixMatch] += 1;
        }

        foreach ($listedData as $index => $row) {
            $mixMatch = $row[1];

            $promoCount = $mixMatch ? Arr::get($mixMatchPromoCount, $mixMatch) : null;
            $totalCount = $mixMatch ? Arr::get($mixMatchCount, $mixMatch) : null;

            $col = $mixMatch && $promoCount >= $totalCount ? 'E' : 'F';
            $listedSheet->getStyle($col . strval($index + 10))->getFont()->getColor()->setARGB(Color::COLOR_GREEN);
        }

        $listedSheet->fromArray($listedData, null, 'A10', true);
        $unlistedSheet->fromArray($unlistedData, null, 'A10', true);

        return $this->downloadFile($spreadsheet, 'nf-promos-' . Str::slug($period->name) . '.xlsx');
    }
}
