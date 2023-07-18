<?php

namespace App\Http\Livewire\Exports;

use App\Contracts\Http\Livewire\ExportComponent;
use App\Exports\CustomerLinkImagesExport;
use Carbon\Carbon;
use Illuminate\Validation\Validator;
use YlsIdeas\FeatureFlags\Facades\Features;

class CustomerLinkImagesExportComponent extends AbstractExportComponent implements ExportComponent
{
    public $sinceDate;
    public $stockIds;
    public bool $onlyActive = false;
    public bool $includeOriginalImage = false;
    public bool $includeSmallImage = true;
    public bool $includeLargeImage = true;

    public function rules(): array
    {
        return [
            'onlyActive' => ['required', 'bool'],
            'includeLargeImage' => ['required', 'bool'],
            'includeSmallImage' => ['required', 'bool'],
            'includeOriginalImage' => ['required', 'bool'],
            'stockIds' => ['nullable'],
            'sinceDate' => ['nullable', 'date'],
        ];
    }

    public function prepareForRun(): void
    {
        $this->withValidator(function (Validator $validator) {
                $validator->after(function (Validator $validator) {
                    if (Features::accessible('customer-link-images-export-tweaks')) {
                        if (! $this->stockIds && ! $this->sinceDate && ! $this->onlyActive) {
                            $validator->errors()->add('stockIds', 'You must select a date to export from, provide a list of stock IDs or only select active products');
                            $validator->errors()->add('sinceDate', 'You must select a date to export from, provide a list of stock IDs or only select active products');
                        }

                        if (! $this->stockIds && ! $this->onlyActive && Carbon::now()->subMonths(2)->gt($this->sinceDate)) {
                            $validator->errors()->add('sinceDate', 'You must select a date within the last 2 months.');
                        }

                        if (! ($this->includeOriginalImage || $this->includeLargeImage || $this->includeSmallImage)) {
                            $validator->errors()->add('includeOriginalImage', 'You must select at least one image size to export.');
                            $validator->errors()->add('includeLargeImage', 'You must select at least one image size to export.');
                            $validator->errors()->add('includeSmallImage', 'You must select at least one image size to export.');
                        }

                    } else {
                        if (! $this->stockIds && ! $this->sinceDate) {
                            $validator->errors()->add('stockIds', 'You must select a date to export from or provide a list of stock IDs.');
                            $validator->errors()->add('sinceDate', 'You must select a date to export from or provide a list of stock IDs.');
                        }
                        if (! $this->stockIds && Carbon::now()->subMonths(2)->gt($this->sinceDate)) {
                            $validator->errors()->add('sinceDate', 'You must select a date within the last 2 months.');
                        }
                    }

                    if ($validator->errors()->any()) {
                        $this->dispatchBrowserEvent('notify', ['content' => 'Invalid export criteria!', 'type' => 'error']);
                    }
                });
        })->validate();

        $filename = 'customer-link-images-' . Carbon::now()->format('Y-m-d') . '.zip';

        $this->export = (new CustomerLinkImagesExport($filename))->setCriteria(
            sinceDate: $this->sinceDate,
            stockIds: $this->stockIds,
            onlyActive: $this->onlyActive,
            includeLargeImage: $this->includeLargeImage,
            includeOriginalImage: $this->includeOriginalImage,
            includeSmallImage: $this->includeSmallImage,
        );
    }

    public function render()
    {
        return view('livewire.exports.customer-link-images-export-component');
    }
}
