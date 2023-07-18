<?php

namespace App\Exports;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class CSVExport extends BaseExport
{
    public function downloadFile($rows, $filename, $delimiter = ',', $skipEnclosures = false, $includeBOM = true)
    {
        $response = new StreamedResponse;
        $response->setCallback(function () use ($rows, $delimiter, $skipEnclosures, $includeBOM) {
            $file = fopen('php://output', 'w');

            if ($includeBOM) {
                fwrite($file, "\xEF\xBB\xBF");
            }

            foreach ($rows as $row) {
                if ($skipEnclosures) {
                    fwrite($file, implode($delimiter, $row) . "\n");
                } else {
                    fputcsv($file, $row, $delimiter);
                }
            }
            fclose($file);
        });

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response->send();
    }
}
