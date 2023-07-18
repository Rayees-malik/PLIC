<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class OrganicGarageListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/organic_garage.xlsx';

    protected $filename = 'organic_garage_listingform.xlsx';

    protected $startingRow = 10;

    protected $startingColumn = 'A';

    protected $priceCode = 'ORGGAR';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'dimensions',
                'uom',
                'subcategory',
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select(
                'id', 'stock_id', 'upc', 'name', 'uom_id', 'size', 'brand_id', 'category_id', 'subcategory_id',
                'purity_sell_by_unit', 'inner_units', 'inner_upc', 'master_units', 'master_upc'
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $price = $product->getPrice();
            $ogdPrice = $product->getPrice(null, $this->priceCode);
            $ogd = $product->getOGD($this->priceCode)['discount'];
            $discount = $ogd ? $ogd * 100 . '%' : null;

            $data[] = [
                $product->stock_id,
                $product->upc,
                $product->name,
                null,
                $product->caseSize,
                $product->getSize(),
                $price,
                round($price / $product->caseSize, 2),
                $discount,
                null,
                $discount ? $ogdPrice : null,
                $discount ? $ogdPrice : null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                null,
                null,
                $product->brand->name,
                optional($product->subcategory)->category,
                null,
                null,
                null,
                $product->soldByCase ? $product->caseUPC : null,
                round(optional($product->dimensions)->imperialHeight, 3),
                round(optional($product->dimensions)->imperialWidth, 3),
                round(optional($product->dimensions)->imperialDepth, 3),
            ];
        }

        $this->data[] = $data;

        return $data;
    }

    public function onExportComplete($spreadsheet)
    {
        $sheet = $spreadsheet->getSheet(0);
        $data_sheet = $spreadsheet->getSheet(1);

        $sheet_data = $this->data[0];
        $lookup_data = $data_sheet->toArray();

        $index = $this->startingRow;

        foreach ($sheet_data as $data) {
            foreach ($lookup_data as $lookup) {
                if ($lookup[1] === strtoupper($data[32])) {
                    $sheet->getCell('AI' . $index)->setValue($lookup[4]);
                }
            }
            $index++;
        }

        $spreadsheet->removeSheetByIndex(1);
    }
}
