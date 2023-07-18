<?php

namespace App\Imports;

use App\Models\CustomerGLAccount;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CustomerGLAccountsImport
{
    public static function import($file)
    {
        $reader = IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);

        CustomerGLAccount::truncate();
        $importData = $spreadsheet->getActiveSheet()->toArray();
        unset($importData[0]);

        $insertData = [];
        foreach ($importData as $row) {
            $custNo = trim($row[0]);
            $glAccount = trim($row[1]);

            if ($custNo && $glAccount) {
                $insertData[] = ['customer_number' => $custNo, 'gl_account' => $glAccount];
            }
        }

        CustomerGLAccount::insert($insertData);
        $records = number_format(count($insertData), 0);

        flash("Successfully imported {$records} customer gl accounts.", 'success');
    }
}
