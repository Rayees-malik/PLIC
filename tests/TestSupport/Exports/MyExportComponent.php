<?php

namespace Tests\TestSupport;

use App\Http\Livewire\Exports\AbstractExportComponent;

class MyExportComponent extends AbstractExportComponent
{
    public function prepareForRun(): void
    {
    }

    protected function getFilename(): string
    {
    }
}
