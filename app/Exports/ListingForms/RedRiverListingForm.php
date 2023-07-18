<?php

namespace App\Exports\ListingForms;

use App\Exports\BaseExport;
use App\Models\Product;

class RedRiverListingForm extends BaseExport
{
    protected $template = 'templates/listingforms/red_river.xlsx';

    protected $filename = 'red_river_listingform.xlsx';

    protected $priceCode = 'CALCOOP';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'dimensions',
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name');
                },
                'regulatoryInfo' => function ($query) {
                    $query->select('product_id', 'npn');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select(
                'id', 'stock_id', 'name', 'upc', 'uom_id',
                'size', 'brand_id', 'purity_sell_by_unit', 'inner_units', 'master_units',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function export($stockIds, $includeNonCatalogue)
    {
        $spreadsheet = property_exists($this, 'template') ? $this->loadFile($this->template) : abort(404);

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $index => $product) {
            $price = $product->getPrice();
            $ogdPrice = $product->getPrice(null, $this->priceCode);

            // Clone template sheet
            $clone = clone $spreadsheet->getActiveSheet();
            $clone->setTitle(strval($product->stock_id));
            $sheet = $spreadsheet->addSheet($clone, $index + 1);

            // Set active sheet so that I can use setCellValue for better performance than getCell() -> setValue()
            $spreadsheet->setActiveSheetIndex($index + 1);

            // Insert sheet data
            $spreadsheet->getActiveSheet()->setCellValue('B5', $product->name);
            $spreadsheet->getActiveSheet()->setCellValue('B6', $product->upc);
            $spreadsheet->getActiveSheet()->setCellValue('B7', $product->caseSize);
            $spreadsheet->getActiveSheet()->setCellValue('E7', $product->getSize());
            $spreadsheet->getActiveSheet()->setCellValue('C9', optional($product->as400Pricing)->taxable ? 'a' : 'z');
            $spreadsheet->getActiveSheet()->setCellValue('B11', $price);
            $spreadsheet->getActiveSheet()->setCellValue('B11', $price == $ogdPrice ? null : $ogdPrice);
            $spreadsheet->getActiveSheet()->setCellValue('B20', optional($product->regulatoryInfo)->npn);
            $spreadsheet->getActiveSheet()->setCellValue('B22', round(optional($product->dimensions)->height, 3));
            $spreadsheet->getActiveSheet()->setCellValue('D22', round(optional($product->dimensions)->depth, 3));
            $spreadsheet->getActiveSheet()->setCellValue('F22', round(optional($product->dimensions)->width, 3));
            $spreadsheet->getActiveSheet()->setCellValue('B24', $product->stock_id);
        }

        // Remove the template worksheet
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('New Items Form'));
        $spreadsheet->removeSheetByIndex($sheetIndex);

        // Set the first product worksheet as active sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Download the file
        $this->downloadFile($spreadsheet, $this->filename);
    }
}
