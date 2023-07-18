<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class KardishListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/kardish.xlsx';

    protected $filename = 'kardish_listingform.xlsx';

    protected $startingRow = 15;

    protected $startingColumn = 'B';

    protected $upcSheetData = [];

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'dimensions',
                'uom',
                'brand' => function ($query) {
                    return $query->select('id', 'name');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
            ])
            ->select(
                'id', 'brand_id', 'category_id', 'uom_id', 'upc', 'stock_id', 'name', 'shelf_life',
                'shelf_life_units', 'purity_sell_by_unit', 'inner_units', 'master_units', 'size',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];
        $upcData = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $price = $product->getPrice();
            $unitPrice = $product->convertToUnitPrice($price);

            $data[] = [
                $product->upc,
                $product->stock_id,
                $product->brand->name,
                $product->name,
                optional($product->category)->name,
                $product->brand->name,
                'Purity/Carol',
                $product->getShelfLife(),
                optional($product->category)->name == 'Food & Beverage' ? '2 Months' : '6 Months',
                $product->caseSize,
                round($product->size, 2),
                $product->uom->unit,
                optional($product->dimensions)->height,
                optional($product->dimensions)->width,
                optional($product->dimensions)->depth,
                $price,
                null,
                null,
                $price,
                $unitPrice,
                null,
                null,
                optional($product->as400Pricing)->taxable ? 'HST' : 'No Tax',
            ];

            $upcData[] = [
                null,
                $product->upc,
                $product->features_1,
                $product->features_2,
                $product->features_3,
            ];
        }

        $this->finalRow = count($data) + $this->startingRow;
        $this->upcSheetData = $upcData;

        return $data;
    }

    public function onExportComplete($spreadsheet)
    {
        $sheet = $spreadsheet->getSheet(0);
        $sheet->removeRow($this->finalRow, 70 - $this->finalRow);

        $sheet = $spreadsheet->getSheet(2);
        $sheet->fromArray($this->upcSheetData, null, 'A5');
    }
}
