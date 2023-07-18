<?php

namespace App\Exports\Retailers;

use App\Exports\BaseExport;
use App\Helpers\ExcelHelper;
use App\Helpers\PromoHelper;
use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DefaultPromosExport extends BaseExport
{
    const LAST_COLUMN = 115;

    const PER_PERIOD_COUMNS = 7;

    const BRAND_COLUMNS = 3;

    public function export($retailer, Request $request)
    {
        $periodIds = $request->get('periods');
        if (! $periodIds) {
            return redirect()->route('retailers.exports', ['id' => $retailer->id]);
        }

        $year = date('Y');
        $periods = PromoPeriod::with('basePeriod')
            ->byOwner($retailer)
            ->whereIn('id', $periodIds)
            ->orderBy('start_date', 'asc')
            ->get();

        $spreadsheet = $this->loadFile('templates/retailers/default_promos.xlsx');
        $sheet = $spreadsheet->getSheet(0);

        $sheet->getCell('A1')->setValue("{$retailer->name} {$year} Purity Life Ad Plan Grid");

        $periodCount = count($periods);
        for ($i = 0; $i < $periodCount; $i++) {
            $nextCol = ExcelHelper::indexToColumn(static::BRAND_COLUMNS + ($i * static::PER_PERIOD_COUMNS) + 1);
            $sheet->getCell("{$nextCol}1")->setValue($periods[$i]->name);
        }

        for ($i = static::LAST_COLUMN; $i > static::BRAND_COLUMNS + ($periodCount * static::PER_PERIOD_COUMNS); $i--) {
            $sheet->removeColumnByIndex($i);
        }

        $productBrands = Product::forExport()
            ->withPromoPricing($periods)
            ->with([
                'brand' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->select('id', 'name', 'name_fr', 'packaging_language', 'brand_id', 'stock_id')
            ->ordered()->get()->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)->groupBy('brand.name');

        $nextRow = 3;
        foreach ($productBrands as $brandName => $products) {
            $ldData = [];
            $productData = [];

            for ($i = 0; $i < $periodCount; $i++) {
                $period = $periods[$i];
                $periodStartColumn = static::BRAND_COLUMNS + ($i * static::PER_PERIOD_COUMNS) + 1;

                $discountRange = PromoHelper::getDiscountRange($products, $period);
                if (! $discountRange || $discountRange['low'] <= 0) {
                    continue;
                }

                if ($discountRange && $discountRange['line_drive']) {
                    $ldData[$i] = $this->getData($products->first(), $period);
                } else {
                    foreach ($products as $product) {
                        Arr::set($productData, "{$product->stock_id}.name", $product->getName());
                        Arr::set($productData, "{$product->stock_id}.{$i}", $this->getData($product, $period));
                    }
                }
            }

            if (count($ldData)) {
                $sheet->getCell("A{$nextRow}")->setValue($brandName);
                $sheet->getCell("C{$nextRow}")->setValue('Line Drive');

                foreach ($ldData as $i => $data) {
                    $col = ExcelHelper::indexToColumn(static::BRAND_COLUMNS + ($i * static::PER_PERIOD_COUMNS) + 1);
                    $sheet->fromArray($data, null, "{$col}{$nextRow}");
                }

                $nextRow++;
            }

            ksort($productData);
            foreach ($productData as $stockId => $data) {
                $sheet->getCell("A{$nextRow}")->setValue($brandName);
                $sheet->getCell("B{$nextRow}")->setValue($stockId);
                $sheet->getCell("C{$nextRow}")->setValue($data['name']);
                unset($data['name']);

                foreach ($data as $i => $data) {
                    $col = ExcelHelper::indexToColumn(static::BRAND_COLUMNS + ($i * static::PER_PERIOD_COUMNS) + 1);
                    $sheet->fromArray($data, null, "{$col}{$nextRow}");
                }

                $nextRow++;
            }
        }

        return $this->downloadFile($spreadsheet, Str::slug($retailer->name) . '-promo-grid.xlsx');
    }

    public function getData($product, $period)
    {
        $lineItem = $product->getPromoLineItem($period, true);
        if (! $lineItem) {
            return;
        }

        return [
            Arr::get($lineItem->data, 'ad_type'),
            Arr::get($lineItem->data, 'ad_cost'),
            $lineItem->promo->dollar_discount ? null : round($lineItem->brand_discount / 100, 2),
            $lineItem->promo->dollar_discount ? $lineItem->brand_discount : null,
            Arr::get($lineItem->data, 'demo'),
            Arr::get($lineItem->data, 'notes'),
        ];
    }
}
