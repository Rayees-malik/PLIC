<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class ExcelCSVExport extends BaseExport
{
    public function writeFile($spreadsheet, $outputPath)
    {
        $writer = new Csv($spreadsheet);
        $writer->save($outputPath);
    }

    public function downloadFile($spreadsheet, $filename, $delimiter = ',')
    {
        $response = new StreamedResponse;
        $response->setCallback(function () use ($spreadsheet, $delimiter) {
            $writer = new Csv($spreadsheet);
            $writer->setDelimiter($delimiter);
            $writer->save('php://output');
        });

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response->send();
    }
}
