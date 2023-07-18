<?php

namespace App\Http\Livewire;

use App\Media;
use App\Models\Product;
use App\Models\QualityControlRecord;
use App\Models\Warehouse;
use App\Rules\NpnValid;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Browsershot\Browsershot;

class QualityControlForm extends Component
{
    use WithFileUploads;

    public QualityControlRecord $record;

    public $vendorName = '';

    public $newFiles = [];

    public $warehouses = [];

    public $showPreReleaseModal = false;

    protected $listeners = ['productSelected'];

    protected function getRules()
    {
        return [
            'record.po_number' => ['required'],
            'record.vendor_id' => ['required'],
            'record.warehouse_id' => ['required'],
            'record.received_date' => ['date_format:"Y-m-d", "Y/m/d"', 'required'],
            'record.product_id' => ['required'],
            'record.quantity_received' => ['numeric', 'required', 'min:1'],
            'record.lot_number' => ['string', 'required'],
            'record.expiry_date' => ['date_format:"Y-m-d","Y/m/d"', 'required'],
            'record.bin_number' => ['required'],
            'record.din_npn_number' => ['required', new NpnValid],
            'record.seals_intact' => ['required'],
            'record.din_npn_on_label' => ['required'],
            'record.importer_address' => ['required'],
            'record.bilingual_label' => ['required'],
            'newFiles.*' => ['file', 'nullable'],
            'record.receiving_comment' => ['required', 'string'],

            // damage sampling
            'record.number_damaged_cartons' => [
                'required', 'numeric', 'min:0', 'integer',
            ],
            'record.number_damaged_units' => [
                'required', 'numeric', 'min:0', 'integer',
            ],
            'record.number_to_reject_destroy' => [
                'required', 'numeric', 'min:0', 'integer',
            ],
            'record.nature_of_damage' => [
                'required',
            ],

            // sampling
            'record.number_units_sent_for_testing' => [
                'required', 'numeric', 'min:0', 'integer',
                'required_with:record.record.number_units_for_stability,record.number_units_retained,record.units_taken,record.regulatory_compliance_comment',
            ],
            'record.number_units_for_stability' => [
                'required', 'numeric', 'min:0', 'integer',
                'required_with:record.number_units_sent_for_testing,record.number_units_retained,record.units_taken,record.regulatory_compliance_comment',
            ],
            'record.number_units_retained' => [
                'required', 'numeric', 'min:0', 'integer',
                'required_with:record.number_units_sent_for_testing,record.number_units_for_stability,record.units_taken,record.regulatory_compliance_comment',
            ],
            'record.regulatory_compliance_comment' => ['required', 'string'],

            // identity testing
            'record.identity_description' => ['required'],
            'record.matches_written_specification' => ['boolean', 'required'],
            'record.out_of_specifications_comment' => ['required_if:record.matches_written_specification,false', 'nullable'],

            'record.pre_release_reason' => ['required_if:record.generated_tag,pre-released', 'nullable'],
            'record.pre_release_requested_by' => ['required_if:record.generated_tag,pre-released', 'nullable'],
        ];
    }

    public function mount(QualityControlRecord $record = null)
    {
        $this->record = $record ?? new QualityControlRecord;
        $this->vendorName = $record->product?->brand->vendor?->name;

        $user = auth()->user();

        $this->warehouses = Warehouse::query()
            ->select('id', 'name', 'number')
            ->where('name', 'not like', 'QC%')
            ->where(function ($query) use ($user) {
                $query = $query->when($user->can('qc-technician-01'), function ($query) {
                    $query->where('number', '01');
                });
                $query = $query->when($user->can('qc-technician-04'), function ($query) {
                    $query->orWhere('number', '04');
                });

                $query = $query->when($user->can('qc-technician-08'), function ($query) {
                    $query->orWhere('number', '08');
                });

                $query = $query->when($user->can('qc-technician-09'), function ($query) {
                    $query->orWhere('number', '09');
                });
            })
            ->get()
            ->mapWithKeys(function ($warehouse) {
                return [$warehouse->id => $warehouse->number . ' - ' . $warehouse->name];
            })
            ->toArray();
    }

    public function productSelected($name, $productId)
    {
        if (is_null($name) || $name == '') {
            return;
        }

        $product = Product::find($productId);
        $this->record->product_id = $productId;

        $validator = Validator::make(['npn' => $product->regulatoryInfo->npn], ['npn' => new NpnValid]);

        if ($validator->fails()) {
            $this->record->din_npn_number = '';
        } else {
            $this->record->din_npn_number = $product->regulatoryInfo->npn ?? '';
        }

        $this->record->vendor_id = $product->brand->vendor->id;
        $this->vendorName = $product->brand->vendor->name;
        $this->record->identity_description = $product->identity_description ?? '';
    }

    public function submit()
    {
        $this->validate();

        foreach ($this->newFiles as $file) {
            $this->record->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection('qc-files');
        }

        $this->record->user()->associate(auth()->user());

        $this->record->save();

        $product = Product::findOrFail($this->record->product_id);
        $product->identity_description = $this->record->identity_description;
        $product->save();

        if ($this->record->wasRecentlyCreated) {
            $this->dispatchBrowserEvent('notify', ['content' => 'QC Record Created!', 'type' => 'success']);
        } else {
            $this->dispatchBrowserEvent('notify', ['content' => 'QC Record Updated!', 'type' => 'success']);
        }
    }

    public function removeFile(Media $file)
    {
        $file->delete();
        $this->record->refresh();
    }

    public function generateTag(string $tag)
    {
        $this->record->generated_tag = $tag;
        $this->record->save();
        // $this->record->refresh();

        if ($tag == 'pre-released' && ! $this->showPreReleaseModal) {
            $this->showPreReleaseModal = true;

            return;
        }

        if ($this->showPreReleaseModal) {
            $this->validate();
        }

        $this->showPreReleaseModal = false;

        return $this->generateTagDocument($tag);
    }

    public function generateTagDocument(string $tag)
    {
        $this->record->completed_at = now();
        $this->record->completed_by = auth()->user()->id;
        $this->record->generated_tag = $tag;
        $this->record->save();

        $content = view("qc.{$tag}-tag", ['record' => $this->record])->render();

        $pdf = Browsershot::html($content)
            ->format('Letter')
            ->margins(18, 18, 24, 18)
            ->showBackground()
            ->pdf();

        $filename = "{$tag}_{$this->record->id}_{$this->record->product->stock_id}.pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, $filename);
    }

    public function render()
    {
        return view('livewire.quality-control-form');
    }
}
