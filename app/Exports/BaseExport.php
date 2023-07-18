<?php

namespace App\Exports;

use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class BaseExport
{
    public function loadFile($filePath)
    {
        $filePath = resource_path($filePath);

        return File::exists($filePath) ? IOFactory::load($filePath) : abort(404);
    }

    public function writeFile($spreadsheet, $outputPath)
    {
        $writer = new Xlsx($spreadsheet);
        $writer->save($outputPath);
    }

    public function downloadFile($spreadsheet, $filename)
    {
        $response = new StreamedResponse;
        $response->setCallback(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response->send();
    }
}
