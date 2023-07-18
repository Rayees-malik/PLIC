<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class WholeFoodsEastListingForm extends SimpleListingForm
{
    const WHOLESALE_BRANDS = [35, 366, 588];

    protected $template = 'templates/listingforms/whole_foods_east.xlsx';

    protected $filename = 'whole_foods_east_listingform.xlsx';

    protected $startingRow = 4;

    protected $startingColumn = 'A';

    protected $worksheetIndex = 1;

    protected $priceCode = 'WFM-COST';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'average_landed_cost', 'taxable');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select(
                'id', 'category_id', 'subcategory_id', 'upc', 'name',
                'stock_id', 'uom_id', 'size', 'brand_id', 'purity_sell_by_unit', 'inner_units', 'master_units',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $data = array_merge($data, $this->getRow($product));
        }

        return $data;
    }

    public function isBar($product)
    {
        if ($product->soldByCase) {
            return false;
        }

        if (optional($product->category)->name == 'Food & Beverage' || optional($product->category)->name = 'Supplements') {
            if (strpos(strtolower(optional($product->subcategory)->name), 'bar') !== false) {
                return true;
            }
        }

        return false;
    }

    public function getRow($product, $isBox = false)
    {
        // TODO: Add discount off Wholesale / Fix pricing
        // Price = Round(cDbl(pDB('WHOLESALEPRICE')) * (1 - (pDB(DOWField) / 100)), 2)'
        // Price = Round(cDbl(pDB('ProductLandedCost')) * 1.113, 2)

        $price = in_array($product->brand_id, $this::WHOLESALE_BRANDS) ? $product->getPrice(null, $this->priceCode) : round(optional($product->as400Pricing)->average_landed_cost * 1.113, 2);
        $certifications = $product->certifications->pluck('name')->toArray();

        $isBar = ! $isBox && $this->isBar($product);
        if ($isBox) {
            $upc = $product->inner_upc ?? $product->master_upc;
        } else {
            $upc = $product->upc;
        }

        if ($isBar) {
            $productName = "{$product->name} Bar";
        } elseif ($isBox) {
            $productName = "{$product->name} Box";
        } else {
            $productName = $product->name;
        }

        $productData = [
            optional($product->category)->name,
            optional($product->subcategory)->name,
            substr($upc, 0, -1),
            substr($upc, -1),
            null,
            $product->brand->name,
            $productName,
            $isBar ? null : $product->stock_id,
            $isBox ? 1 : round($product->size, 2),
            $isBox ? 'CT' : optional($product->uom)->unit,
            $isBox ? 1 : $product->minimumSellBy,
            $price,
            null,
            null,
            null,
            null,
            'Purity Life',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            in_array('Fair Trade', $certifications) ? 'Yes' : 'No',
            in_array('Gluten Free', $certifications) ? 'Yes' : 'No',
        ];

        if ($isBar) {
            return array_merge([$productData], $this->getRow($product, true));
        }

        return [$productData];
    }
}
