<?php

namespace App\Exports;

use App\Models\Allergen;
use App\Models\Certification;
use App\Models\Product;
use App\Models\PromoPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CustomerLinkExport extends CSVExport
{
    const REPLACE_PAIRS = [
        "\r\n" => ' ',
        "\n\r" => ' ',
        "\r" => ' ',
        "\n" => ' ',
        "\t" => ' ',
    ];

    public $english = true;

    public $allergens = [];

    public $certifications = [];

    public function export(Request $request)
    {
        $productStatus = $request->get('product_status');
        $modularFormat = $request->get('export_type') != 'E';

        $english = $request->get('language') != 'F';
        $this->english = $english;

        $periodId1 = $request->get('period_id1');
        $period1 = $periodId1 ? PromoPeriod::with(['basePeriod', 'caseStackDeals'])->find($periodId1) : null;
        $basePeriod1 = $period1 ? $period1->basePeriod : null;

        $periodId2 = $request->get('period_id2');
        $period2 = $periodId2 ? PromoPeriod::with('basePeriod', 'caseStackDeals')->find($periodId2) : null;
        $basePeriod2 = $period2 ? $period2->basePeriod : null;

        if ($period2 && ! $period1) {
            $period1 = $period2;
            $basePeriod1 = $basePeriod2;

            $period2 = null;
            $basePeriod2 = null;
        }
        if (! $period1) {
            return redirect()->route('exports.index');
        }

        $startDate1 = $basePeriod1 && $basePeriod1->start_date < $period1->start_date ? $basePeriod1->start_date : $period1->start_date;
        $startDate2 = $period2 ? ($basePeriod2 && $basePeriod2->start_date < $period2->start_date ? $basePeriod2->start_date : $period2->start_date) : null;

        $productsByBrand = Product::ordered()
            ->withPromoPricing([$period1, $basePeriod1, $period2, $basePeriod2], true)
            ->forExport()
            ->where(function ($query) {
                $query->whereDate('listed_on', '<', Carbon::parse('2 months ago'))
                    ->orWhereNull('listed_on')
                    ->orWhereHas('as400WarehouseStock')
                    ->orWhereHas('as400StockData', function ($query) {
                        $query->whereDate('expected', '<=', Carbon::parse('2 weeks'));
                    });
            })
            ->with([
                'uom',
                'flags',
                'allergens' => function ($query) {
                    $query->select('id', 'name', 'contains');
                },
                'certifications' => function ($query) {
                    $query->select('id', 'name');
                },
                'brand' => function ($query) {
                    $query->with([
                        'brokers' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'currency' => function ($query) {
                            $query->select('id', 'exchange_rate');
                        },
                    ])->select('id', 'name', 'name_fr', 'description_fr', 'website', 'phone', 'category_code', 'currency_id', 'education_portal', 'map_pricing', 'made_in_canada');
                },
                'regulatoryInfo' => function ($query) {
                    $query->select('serving_size', 'calories', 'total_fat', 'saturated_fat', 'trans_fat', 'cholesterol', 'sodium', 'carbohydrates', 'fiber', 'sugar', 'protein');
                },
                'catalogueCategory' => function ($query) {
                    $query->select('id', 'name', 'name_fr');
                },
                'category' => function ($query) {
                    $query->select('id', 'name');
                },
                'subcategory' => function ($query) {
                    $query->select('id', 'name');
                },
            ]);

        if ($productStatus == 'A') {
            $productsByBrand = $productsByBrand->active();
        } elseif ($productStatus == 'D') {
            $productsByBrand = $productsByBrand->discontinued();
        }
        $productsByBrand = $productsByBrand->get()->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)->groupBy('brand.id');

        $headings = [
            'Brand Name', 'Brand Description', 'Brand Website', 'Brand Phone', 'Category Name', 'Stock ID',
            'Product Name', 'Product Description', 'Ingredients', 'Deal', 'Product Size', 'Case Size', 'UPC Code',
            'Reg. Price', 'Promo Discount', 'Promo Month', 'Promo Price', 'Bilingual', 'Dosage', 'ServingSize',
            'Calories', 'TotalFat', 'SaturatedFat', 'TransFat', 'Cholesterol', 'Sodium', 'TotalCarbohydrates',
            'Fiber', 'Sugar', 'Protein', 'BrandVideo', 'BrandID', 'Brand/Category', 'Line Type', 'Sub Line Type',
        ];

        $allCertifications = Certification::select('id', 'name')->orderBy('name')->get();
        foreach ($allCertifications as $certification) {
            $this->certifications[] = $certification->name;
            $headings[] = $certification->name;
        }

        $allAllergens = Allergen::select('id', 'name')->orderBy('name')->get();
        foreach ($allAllergens as $allergen) {
            $this->allergens[] = $allergen->name;
            $headings[] = $allergen->name;
        }

        array_push($headings, 'Flags', 'Broker Name', 'Education Portal', 'MAP Pricing', 'Canadian');
        $data = [$headings];

        foreach ($productsByBrand as $brandId => $products) {
            $deal1 = $period1 ? $period1->getCaseStackDeal($brandId) : null;
            $deal2 = $period2 ? $period2->getCaseStackDeal($brandId) : null;

            $deal1 = $deal1 ? ($english ? $deal1->deal : $deal1->deal_fr) : null;
            $deal2 = $deal2 ? ($english ? $deal2->deal : $deal2->deal_fr) : null;

            $caseStackDeal = null;
            if ($deal1 && (! $deal2 || $deal1 == $deal2)) {
                $caseStackDeal = $deal1;
            } else {
                if ($deal1) {
                    $caseStackDeal = "{$startDate1->month}: {$deal1}. ";
                }

                if ($deal2) {
                    $caseStackDeal .= "{$startDate2->month}: {$deal2}.";
                }
            }

            foreach ($products as $product) {
                $promoDiscount1 = null;
                $promoPrice1 = $product->calculateCombinedPromoPrice($period1, $basePeriod1, $startDate1, false, false, $promoDiscount1);
                $promoDiscount2 = null;
                $promoPrice2 = $product->calculateCombinedPromoPrice($period2, $basePeriod2, $startDate2, false, false, $promoDiscount2);

                if ($promoPrice1 || $promoPrice2) {
                    if ($promoPrice1 == $promoPrice2) {
                        $data[] = $this->getValues($product, $promoPrice1, $promoDiscount1, 'B', $caseStackDeal);
                    } else {
                        if ($promoPrice1) {
                            $data[] = $this->getValues($product, $promoPrice1, $promoDiscount1, substr(optional($startDate1)->monthName, 0, 1), $deal1);
                        }
                        if ($promoPrice2) {
                            $data[] = $this->getValues($product, $promoPrice2, $promoDiscount2, substr(optional($startDate2)->monthName, 0, 1), $deal2);
                        }
                    }
                } else {
                    $data[] = $this->getValues($product, '', '', '', $caseStackDeal);
                }
            }
        }

        if ($modularFormat) {
            $cleanedData = [];
            foreach ($data as $row) {
                $cleanedData[] = array_map(function ($col) {
                    $cleanCol = strtr($col, static::REPLACE_PAIRS);
                    if (substr($cleanCol, 0, 1) == '"') {
                        $cleanCol = substr($cleanCol, 1);
                    }

                    return $cleanCol;
                }, $row);
            }

            $data = $cleanedData;
        }

        $fileSuffix = ($productStatus == 'D' ? '_disc' : '') . ($english ? '' : '_fr');

        return $this->downloadFile($data, "ccupld{$fileSuffix}.csv", $modularFormat ? "\t" : ',', $modularFormat);
    }

    private function getValues($product, $promoPrice, $promoDiscount, $promoMonth, $caseStackDeal)
    {
        $english = $this->english;

        $productData = [
            $english ? $product->brand->name : $product->brand->getNameFr(),
            $english ? $product->brand->description : $product->brand->description_fr,
            $product->brand->website,
            $product->brand->phone,
            $english ? optional($product->catalogueCategory)->name : optional($product->catalogueCategory)->getNameFr(),
            $product->stock_id,
            $english ? $product->name : $product->getNameFr(),
            $english ? $product->description : $product->description_fr,
            $english ? $product->ingredients : $product->ingredients_fr,
            $caseStackDeal,
            $english ? $product->getSizeWithUnits() : $product->getSizeWithUnitsFR(),
            $product->catalogueCaseSize,
            $product->upc,
            optional($product->as400Pricing)->wholesale_price,
            $promoDiscount,
            $promoMonth,
            $promoPrice,
            $product->packaging_language,
            $english ? $product->recommended_dosage : $product->recommended_dosage_fr,
            optional($product->regulatoryInfo)->serving_size,
            optional($product->regulatoryInfo)->calories,
            optional($product->regulatoryInfo)->total_fat,
            optional($product->regulatoryInfo)->saturated_fat,
            optional($product->regulatoryInfo)->trans_fat,
            optional($product->regulatoryInfo)->cholesterol,
            optional($product->regulatoryInfo)->sodium,
            optional($product->regulatoryInfo)->carbohydrates,
            optional($product->regulatoryInfo)->fiber,
            optional($product->regulatoryInfo)->sugar,
            optional($product->regulatoryInfo)->protein,
            '', // TODO: Youtube?
            $product->brand->id,
            optional($product->as400StockData)->category_code ?? $product->brand->category_code,
            optional($product->category)->name,
            optional($product->subcategory)->name,
        ];

        $productCertifications = $product->certifications->pluck('name')->toArray();
        foreach ($this->certifications as $certification) {
            $productData[] = in_array($certification, $productCertifications) ? 'Y' : 'N';
        }

        $productAllergens = array_column($product->allergens->toArray(), 'contains', 'name');
        foreach ($this->allergens as $allergen) {
            $productData[] = Arr::get($productAllergens, $allergen) == 1 ? 'Y' : (Arr::get($productAllergens, $allergen) == -1 ? 'N' : 'M');
        }

        array_push(
            $productData,
            implode('|', $product->flags->pluck('name')->toArray()),
            implode(', ', $product->brand->brokers->pluck('name')->toArray()),
            $product->brand->education_portal ? 'Y' : 'N',
            $product->brand->map_pricing ? 'Y' : 'N',
            $product->brand->made_in_canada ? 'Y' : 'N',
        );

        return $productData;
    }
}
