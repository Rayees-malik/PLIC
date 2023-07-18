<?php

namespace App\Http\Livewire\Exports;

use App\Contracts\Exports\Exportable;
use App\Jobs\RunExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Livewire\Component;

abstract class AbstractExportComponent extends Component
{
    public function run(): mixed
    {
        $this->prepareForRun();

        return $this->processExport();
    }

    abstract public function prepareForRun(): void;

    protected ?Exportable $export;

    protected function processExport()
    {
        if ($this->export instanceof ShouldQueue) {
            dispatch(new RunExport($this->export, auth()->user()));
            $this->dispatchBrowserEvent('notify', ['content' => 'Export has been queued. You should receive an email shortly with your download!', 'type' => 'success']);

            return null;
        }

        $this->dispatchBrowserEvent('notify', ['content' => 'Export Downloaded!', 'type' => 'success']);

        return response()->download($this->export->export(), $this->export->getFilename());
    }
}
