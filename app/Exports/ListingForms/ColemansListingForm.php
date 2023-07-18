<?php

namespace App\Exports\ListingForms;

use App\Exports\BaseExport;
use App\Models\Product;

class ColemansListingForm extends BaseExport
{
    protected $template = 'templates/listingforms/colemans.xlsx';

    protected $filename = 'colemans_listingform.xlsx';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'dimensions',
                'masterDimensions',
                'uom',
                'brand' => function ($query) {
                    return $query->select('id', 'name');
                },
                'allergens' => function ($query) {
                    $query->wherePivot('contains', -1);
                },
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
            ])
            ->select(
                'id', 'stock_id', 'upc', 'inner_upc', 'master_upc', 'master_units',
                'uom_id', 'size', 'name', 'shelf_life',
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

            $certifications = $product->certifications->pluck('name')->toArray();
            $doesNotContain = $product->allergens->pluck('name')->toArray();

            // Clone template sheet
            $clone = clone $spreadsheet->getActiveSheet();
            $clone->setTitle(strval($product->stock_id));
            $sheet = $spreadsheet->addSheet($clone, $index + 1);

            // Set active sheet so that I can use setCellValue for better performance than getCell() -> setValue()
            $spreadsheet->setActiveSheetIndex($index + 1);

            // Insert sheet data
            $spreadsheet->getActiveSheet()->setCellValue('AE5', $product->stock_id);
            $this->insertUPC($spreadsheet, $product->upc, 13);
            $this->insertUPC($spreadsheet, $product->inner_upc, 14);
            $this->insertUPC($spreadsheet, $product->master_upc, 15);
            $spreadsheet->getActiveSheet()->setCellValue('W15', $product->master_units);
            $spreadsheet->getActiveSheet()->setCellValue('AE15', $product->getSize());
            $spreadsheet->getActiveSheet()->setCellValue('D17', $product->name);
            $spreadsheet->getActiveSheet()->setCellValue('W17', round(optional($product->masterDimensions)->imperialWidth, 3));
            $spreadsheet->getActiveSheet()->setCellValue('AE17', round(optional($product->dimensions)->imperialWidth, 3));
            $spreadsheet->getActiveSheet()->setCellValue('W18', round(optional($product->masterDimensions)->imperialDepth, 3));
            $spreadsheet->getActiveSheet()->setCellValue('AE18', round(optional($product->dimensions)->imperialDepth, 3));
            $spreadsheet->getActiveSheet()->setCellValue('W19', round(optional($product->masterDimensions)->imperialHeight, 3));
            $spreadsheet->getActiveSheet()->setCellValue('AE19', round(optional($product->dimensions)->imperialHeight, 3));
            $spreadsheet->getActiveSheet()->setCellValue('W20', round(optional($product->masterDimensions)->imperialGrossWeight, 3));
            $spreadsheet->getActiveSheet()->setCellValue('AE20', round(optional($product->dimensions)->imperialGrossWeight, 3));
            $spreadsheet->getActiveSheet()->setCellValue('AA22', $product->shelf_life);
            $spreadsheet->getActiveSheet()->setCellValue('AA24', optional($product->as400Pricing)->taxable ? 'Yes' : 'No');
            $spreadsheet->getActiveSheet()->setCellValue('F26', $price);
            $spreadsheet->getActiveSheet()->setCellValue('X28', in_array('Gluten Free', $certifications) ? 'X' : null);
            $spreadsheet->getActiveSheet()->setCellValue('X29', in_array('Peanuts', $doesNotContain) ? 'X' : null);
            $spreadsheet->getActiveSheet()->setCellValue('X30', in_array('Dairy', $doesNotContain) ? 'X' : null);
            $spreadsheet->getActiveSheet()->setCellValue('X31', in_array('Soy', $doesNotContain) ? 'X' : null);
            $spreadsheet->getActiveSheet()->setCellValue('X35', in_array('Vegan', $certifications) ? 'X' : null);
        }

        // If we have exported products
        if ($products->count() > 0) {
            // Remove the template worksheet
            $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('A'));
            $spreadsheet->removeSheetByIndex($sheetIndex);

            // Set the first product worksheet as active sheet
            $spreadsheet->setActiveSheetIndex(0);
        }

        // Download the file
        $this->downloadFile($spreadsheet, $this->filename);
    }

    protected function insertUPC($spreadsheet, $upc, $row)
    {
        $upcArray = array_pad(str_split($upc), -14, null);
        array_splice($upcArray, 1, 0, [null]);
        array_splice($upcArray, 4, 0, [null]);
        array_splice($upcArray, 10, 0, [null]);
        array_splice($upcArray, 16, 0, [null]);

        $spreadsheet->getActiveSheet()->fromArray($upcArray, null, "B{$row}");
    }
}
