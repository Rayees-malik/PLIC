<?php

namespace App\Exports\ListingForms;

use App\Exports\BaseExport;

abstract class SimpleListingForm extends BaseExport
{
    abstract public function data($stockIds, $includeNonCatalogue);

    public function export($stockIds, $includeNonCatalogue = false)
    {
        $spreadsheet = property_exists($this, 'template') ? $this->loadFile($this->template) : abort(404);
        $row = property_exists($this, 'startingRow') ? $this->startingRow : '2';
        $column = property_exists($this, 'startingColumn') ? $this->startingColumn : 'A';
        $wsIndex = property_exists($this, 'worksheetIndex') ? $this->worksheetIndex : 0;

        $filename = property_exists($this, 'filename') ? $this->filename : 'listingform.xlsx';

        $data = $this->data($stockIds, $includeNonCatalogue);
        $sheet = $spreadsheet->getSheet($wsIndex);
        $sheet->fromArray($data, null, "{$column}{$row}");

        if (method_exists($this, 'onExportComplete')) {
            $this->onExportComplete($spreadsheet);
        }

        return $this->downloadFile($spreadsheet, $filename);
    }
}
