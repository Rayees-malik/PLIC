<?php

namespace App\Exports\ListingForms;

use App\Exports\BaseExport;
use App\Models\Product;
use ZipArchive;

class LongosListingForm extends BaseExport
{
    protected $template = 'templates/listingforms/longos.xlsx';

    protected $filename = 'longos_listingforms.zip';

    protected $priceCode = 'LONG';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'dimensions',
                'masterDimensions',
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
                'id', 'stock_id', 'brand_id', 'name', 'size', 'uom_id',
                'shelf_life', 'upc', 'master_upc', 'master_units',
                'cases_per_tie', 'layers_per_skid',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function export($stockIds, $includeNonCatalogue)
    {
        $spreadsheet = property_exists($this, 'template') ? $this->loadFile($this->template) : abort(404);
        $products = $this->query($stockIds, $includeNonCatalogue);
        $files = [];

        $zipfile = tempnam(sys_get_temp_dir(), 'plic_');
        $zip = new ZipArchive;
        $zip->open($zipfile, ZipArchive::OVERWRITE);

        foreach ($products as $index => $product) {
            $price = $product->getPrice(null, $this->priceCode);

            // Set Supplier Item Form as active sheet to insert data
            $spreadsheet->setActiveSheetIndexByName('Supplier Item Form');

            // Insert sheet data
            $spreadsheet->getActiveSheet()->setCellValue('G5', 'Purity Life Health Products LP');
            $spreadsheet->getActiveSheet()->setCellValue('G6', 'Purity Life Health Products LP');
            $spreadsheet->getActiveSheet()->setCellValue('G7', '6 Commerce Crescent, Acton, ON L7J 2X3');
            $spreadsheet->getActiveSheet()->setCellValue('G8', $product->brand->name);
            $spreadsheet->getActiveSheet()->setCellValue('G9', 'Dean Howard');
            $spreadsheet->getActiveSheet()->setCellValue('G10', 'dean.howard@puritylife.com');
            $spreadsheet->getActiveSheet()->setCellValue('J10', '647-309-9349');
            $spreadsheet->getActiveSheet()->setCellValue('D11', $product->name);
            $spreadsheet->getActiveSheet()->setCellValue('D12', $product->brand->name);
            $spreadsheet->getActiveSheet()->setCellValue('H12', $product->name);
            $spreadsheet->getActiveSheet()->setCellValue('D13', round($product->size, 2));
            $spreadsheet->getActiveSheet()->setCellValue('F13', strtoupper(optional($product->uom)->unit));
            $spreadsheet->getActiveSheet()->setCellValue('C14', $product->shelf_life);
            $spreadsheet->getActiveSheet()->setCellValue('D19', $product->upc);
            $spreadsheet->getActiveSheet()->setCellValue('H19', $product->stock_id);
            $spreadsheet->getActiveSheet()->setCellValue('D20', round(optional($product->dimensions)->depth, 3));
            $spreadsheet->getActiveSheet()->setCellValue('F20', round(optional($product->dimensions)->width, 3));
            $spreadsheet->getActiveSheet()->setCellValue('H20', round(optional($product->dimensions)->height, 3));
            $spreadsheet->getActiveSheet()->setCellValue('J20', 'cm');
            $spreadsheet->getActiveSheet()->setCellValue('D21', 'Shelf Stable');
            $spreadsheet->getActiveSheet()->setCellValue('H21', round(optional($product->dimensions)->gross_weight * 1000, 3));
            $spreadsheet->getActiveSheet()->setCellValue('D27', $product->master_upc);
            $spreadsheet->getActiveSheet()->setCellValue('D28', round(optional($product->masterDimensions)->depth, 3));
            $spreadsheet->getActiveSheet()->setCellValue('F28', round(optional($product->masterDimensions)->width, 3));
            $spreadsheet->getActiveSheet()->setCellValue('H28', round(optional($product->masterDimensions)->height, 3));
            $spreadsheet->getActiveSheet()->setCellValue('J28', 'cm');
            $spreadsheet->getActiveSheet()->setCellValue('D29', $product->master_units);
            $spreadsheet->getActiveSheet()->setCellValue('H29', round(optional($product->masterDimensions)->gross_weight, 3));
            $spreadsheet->getActiveSheet()->setCellValue('J29', 'kg');
            $spreadsheet->getActiveSheet()->setCellValue('C32', $product->cases_per_tie);
            $spreadsheet->getActiveSheet()->setCellValue('C32', $product->layers_per_skid);
            $spreadsheet->getActiveSheet()->setCellValue('D36', optional($product->as400Pricing)->taxable ? 0.13 : 'Exempt');
            $spreadsheet->getActiveSheet()->setCellValue('G36', optional($product->as400Pricing)->taxable ? 0.13 : 'Exempt');
            $spreadsheet->getActiveSheet()->setCellValue('C42', $price);

            // save the file
            $filepath = tempnam(sys_get_temp_dir(), 'plic_');
            $this->writeFile($spreadsheet, $filepath);

            // add to zip
            $zip->addFile($filepath, "{$product->stock_id}.xlsx");
        }

        // Download ZIP
        $zip->close();

        return response()->download($zipfile, $this->filename, ['Content-Type: application/octet-stream', 'Content-Length: ' . filesize($zipfile)])->deleteFileAfterSend(true);
    }
}
