<?php

namespace App\Contracts\Exports;

interface Exportable
{
    public function __construct(?string $filename = null);

    public function export();

    public function getFilename(): string;
}
