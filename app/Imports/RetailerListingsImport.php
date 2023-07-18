<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\RetailerListing;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RetailerListingsImport
{
    public static function import($retailerId, $file)
    {
        $reader = IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);

        RetailerListing::deleteByRetailer($retailerId);
        $importData = $spreadsheet->getActiveSheet()->toArray();
        $headers = $importData[0];
        unset($importData[0]);

        $products = Product::select('id', 'stock_id')->get()->toArray();
        $products = array_column($products, 'id', 'stock_id');

        $insertData = [];
        foreach ($importData as $row) {
            $stockId = $row[0];
            if (! array_key_exists($stockId, $products)) {
                continue;
            }

            $data = [];
            foreach ($headers as $index => $header) {
                if ($index == 0 || ! trim($header)) {
                    continue;
                }

                $data[Str::slug($header)] = trim($row[$index]);
            }

            $insertData[] = [
                'retailer_id' => $retailerId,
                'product_id' => $products[$stockId],
                'data' => json_encode($data),
            ];
        }

        RetailerListing::insert($insertData);
        $records = number_format(count($insertData), 0);

        flash("Successfully imported {$records} product listings.", 'success');
    }
}
