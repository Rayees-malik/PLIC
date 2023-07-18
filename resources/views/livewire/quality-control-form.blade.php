<form wire:submit.prevent="submit">
namespace App\Http\Livewire;
    <h1>Quality Control Record @if ($record->id)
            [# {{ $record->id }}]
        @endif
    </h1>

    {{-- Receiving --}}
    <section class="tw-py-4">
        <div class="tw-mb-3 tw-border-b tw-border-gray-400">
            <h3 class="tw-text-lg tw-font-medium tw-leading-6 tw-text-gray-900">Receiving</h3>
        </div>
        <div class="tw-grid tw-w-full tw-grid-cols-2 tw-gap-4 sm:tw-grid-cols-12 sm:tw-gap-6">

            <div class="tw-col-span-2 tw-mt-5 lg:tw-col-span-4 lg:tw-mt-0">
                <x-input.label >Product</x-input.label >
                <livewire:input.product-select model="record.product_id" :initialValue="$record->product_id" />
                @error('record.product_id')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-2 tw-mt-5 lg:tw-col-span-4 lg:tw-mt-0">
                <x-input.label >Vendor</x-input.label >
                <x-input.text-static wire:model="record.vendor_id">{{ $vendorName }}</x-forms.static-input>
            </div>

            <div class="tw-mt-5 lg:tw-col-span-4 lg:tw-mt-0">
                <x-input.label >Warehouse</x-input.label >
                <div class="dropdown-wrap">
                    <div class="dropdown-icon">
                        <select wire:model="record.warehouse_id"
                            class="tw-focus:border-sky-800 tw-m-0 tw-block tw-h-10 tw-w-full tw-cursor-pointer tw-appearance-none tw-whitespace-pre tw-rounded-sm tw-border tw-border-solid tw-border-zinc-300 tw-py-0 tw-px-4 tw-text-base tw-font-medium tw-normal-case tw-leading-9">
                            <option value=""
                                class="tw-cursor-pointer tw-whitespace-nowrap tw-leading-9 tw-text-gray-800">
                                Select an option...
                            </option>
                            @foreach ($warehouses as $key => $value)
                                <option value="{{ $key }}"
                                    class="tw-cursor-pointer tw-whitespace-nowrap tw-leading-9 tw-text-gray-800">
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('record.warehouse_id')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-4 tw-mt-5 lg:tw-mt-0">
                <x-input.label >PO Number</x-input.label >
                <x-input.text wire:model="record.po_number" />
                @error('record.po_number')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-4 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Received At</x-input.label >
                <x-input.datepicker wire:model="record.received_date" />
                @error('record.received_date')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-4 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Quantity Received</x-input.label >
                <x-input.text wire:model="record.quantity_received" />
                @error('record.quantity_received')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Lot Number</x-input.label >
                <x-input.text wire:model="record.lot_number" />
                @error('record.lot_number')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Expiry Date</x-input.label >
                <x-input.datepicker wire:model="record.expiry_date" />
                @error('record.expiry_date')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Bin Number</x-input.label >
                <x-input.text wire:model="record.bin_number" />
                @error('record.bin_number')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
                <x-input.label >DIN/NPN</x-input.label >
                <x-input.text wire:model="record.din_npn_number" />
                @error('record.din_npn_number')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
              <x-input.label class="tw-text-base tw-font-medium tw-text-gray-900">Seals Intact</x-input.label >
                <fieldset>
                    <legend class="tw-sr-only">Seals Intact</legend>
                    <div class="tw-flex tw-space-x-4">
                        <x-input.radio wire:model="record.seals_intact" label="Y" value="1" />
                        <x-input.radio wire:model="record.seals_intact" label="N" value="0" />
                    </div>
                    @error('record.seals_intact')<x-input.error :message="$message"/>@enderror
                </fieldset>
            </div>

            <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
                <x-input.label class="tw-text-base tw-font-medium tw-text-gray-900">DIN/NPN on Label</x-input.label >
                <fieldset>
                    <legend class="tw-sr-only">DIN/NPN on Label</legend>
                    <div class="tw-flex tw-space-x-4">
                        <x-input.radio wire:model="record.din_npn_on_label" label="Y" value="1" />
                        <x-input.radio wire:model="record.din_npn_on_label" label="N" value="0" />
                    </div>
                    @error('record.din_npn_on_label')<x-input.error :message="$message"/>@enderror
                </fieldset>
            </div>

            <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
              <x-input.label class="tw-text-base tw-font-medium tw-text-gray-900">Bilingual Label</x-input.label >
                <fieldset>
                    <legend class="tw-sr-only">Bilingual Label</legend>
                    <div class="tw-flex tw-space-x-4">
                        <x-input.radio wire:model="record.bilingual_label" label="Y" value="1" />
                        <x-input.radio wire:model="record.bilingual_label" label="N" value="0" />
                    </div>
                    @error('record.bilingual_label')<x-input.error :message="$message"/>@enderror
                </fieldset>
            </div>

            <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
              <x-input.label class="tw-text-base tw-font-medium tw-text-gray-900">Importer Address</x-input.label >
                <fieldset>
                    <legend class="tw-sr-only">Importer Address</legend>
                    <div class="tw-flex tw-space-x-4">
                        <x-input.radio wire:model="record.importer_address" label="Y" value="1" />
                        <x-input.radio wire:model="record.importer_address" label="N" value="0" />
                    </div>
                    @error('record.importer_address')<x-input.error :message="$message"/>@enderror
                </fieldset>
            </div>

            <div class="tw-col-span-6 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Files</x-input.label >
                @if ($record->files())
                    <ul>
                        @forelse ($record->files() as $item)
                            <li class="tw-flex">
                                <a href="{{ $item->getUrl() }}" target="_blank">{{ $item->name }}</a>
                                <button type="button" class="ml-2" wire:click="removeFile({{ $item->id }})">
                                    <i class="material-icons tw-text-[#78909c]">delete</i>
                                </button>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                @endif
                <x-input.filepond wire:model="newFiles" multiple />

                @error('newFiles')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-6 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Receiving Comment</x-input.label >
                <x-input.textarea wire:model="record.receiving_comment" rows="5" />
                @error('record.receiving_comment')<x-input.error :message="$message"/>@enderror
            </div>
        </div>
    </section>

    {{-- Damage Report --}}
    <section class="tw-py-4">
        <div class="tw-mb-3 tw-border-b tw-border-gray-400">
            <h3 class="tw-text-lg tw-font-medium tw-leading-6 tw-text-gray-900">Damage Report</h3>
        </div>
        <div class="tw-grid tw-w-full tw-grid-cols-4 tw-gap-4 lg:tw-gap-6">
            <div class="tw-mt-5 lg:tw-mt-0">
                <x-input.label ># of Damaged Cartons</x-input.label >
                <x-input.text wire:model="record.number_damaged_cartons" />
                @error('record.number_damaged_cartons')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-1 tw-mt-5 lg:tw-mt-0">
                <x-input.label ># of Damaged Units</x-input.label >
                <x-input.text wire:model="record.number_damaged_units" />
                @error('record.number_damaged_units')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-1 tw-mt-5 lg:tw-mt-0">
                <x-input.label ># to Reject/Destroy</x-input.label >
                <x-input.text wire:model="record.number_to_reject_destroy" />
                @error('record.number_to_reject_destroy')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-2 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Nature of Damage</x-input.label >
                <x-input.textarea wire:model="record.nature_of_damage" rows="5" />
                @error('record.nature_of_damage')<x-input.error :message="$message"/>@enderror
            </div>
        </div>
    </section>

    {{-- Sampling Report --}}
    <section class="tw-py-4" dusk="sampling-report">
        <div class="tw-mb-3 tw-border-b tw-border-gray-400">
            <h3 dusk="sampling-report" class="tw-text-lg tw-font-medium tw-leading-6 tw-text-gray-900">Sampling Report</h3>
        </div>
        <div x-data="{
            unitsSentForTesting: @entangle('record.number_units_sent_for_testing'),
            unitsForStability: @entangle('record.number_units_for_stability'),
            unitsRetained: @entangle('record.number_units_retained'),
        }" class="tw-grid tw-w-full tw-grid-cols-2 tw-gap-4 lg:tw-grid-cols-4 lg:tw-gap-6">
            <div class="tw-col-span-1 tw-mt-5 lg:tw-mt-0">
                <x-input.label ># of Units Sent For Testing</x-input.label >
                <x-input.text dusk="units-sent-for-testing" x-model.number="unitsSentForTesting" />
                @error('record.number_units_sent_for_testing')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="lg:col-span- tw-col-span-1 tw-mt-5 lg:tw-mt-0">
                <x-input.label ># of Units For Stability</x-input.label >
                <x-input.text dusk="units-for-stability" x-model.number="unitsForStability" />
                @error('record.number_units_for_stability')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-1 tw-mt-5 lg:tw-mt-0">
                <x-input.label ># of Units Retained</x-input.label >
                <x-input.text dusk="units-retained" x-model.number="unitsRetained" />
                @error('record.number_units_retained')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-1 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Units Taken</x-input.label >
                <x-input.text-static dusk="units-taken" x-text="unitsSentForTesting + unitsForStability + unitsRetained"
                    wire:model="record.units_taken" />
            </div>

            <div class="tw-col-span-8 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Regulatory Compliance Comment</x-input.label >
                <x-input.textarea wire:model="record.regulatory_compliance_comment" rows="5" />
                @error('record.regulatory_compliance_comment')<x-input.error :message="$message"/>@enderror
            </div>
        </div>
    </section>

    {{-- Identity Testing --}}
    <section class="tw-py-4">
        <div class="tw-mb-3 tw-border-b tw-border-gray-400">
            <h3 class="tw-text-lg tw-font-medium tw-leading-6 tw-text-gray-900">Identity Testing</h3>
        </div>

        <div class="tw-grid tw-w-full tw-grid-cols-2 tw-gap-4 lg:tw-grid-cols-4 lg:tw-gap-6">
            <div class="tw-col-span-8 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Identity Description</x-input.label >
                <x-input.textarea wire:model="record.identity_description" rows="5" />
                @error('record.identity_description')<x-input.error :message="$message"/>@enderror
            </div>

            <div class="tw-col-span-1 tw-mt-5 lg:tw-mt-0">
              <x-input.label class="tw-text-base tw-font-medium tw-text-gray-900">Matches Written Specification</x-input.label >
                <fieldset>
                    <legend class="tw-sr-only">Matches Written Specification</legend>
                    <div class="tw-flex tw-space-x-4">
                        <x-input.radio wire:model="record.matches_written_specification" label="Y" value="1" />
                        <x-input.radio wire:model="record.matches_written_specification" label="N" value="0" />
                    </div>
                    @error('record.matches_written_specification')<x-input.error :message="$message"/>@enderror
                </fieldset>
            </div>

            <div class="tw-col-span-5 tw-mt-5 lg:tw-mt-0">
                <x-input.label >Out of Specifications Report</x-input.label >
                <x-input.textarea wire:model="record.out_of_specifications_comment" rows="5" />
                @error('record.out_of_specifications_comment')<x-input.error :message="$message"/>@enderror
            </div>
        </div>
    </section>

    <x-form-footer-buttons submitText="Save" :cancelRoute="route('qc.index')">
        @if ($record->id)
            <div
                class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-bg-gray-100 tw-py-2 tw-px-4 tw-shadow-md">
                <h4>Generate Tag</h4>
                <div class="tw-flex tw-items-center tw-justify-center tw-space-x-2">
                    <button wire:click="generateTag('approval')"
                        class="tw-m-0 tw-inline-flex tw-h-10 tw-cursor-pointer tw-select-none tw-items-center tw-justify-center tw-overflow-visible tw-whitespace-nowrap tw-rounded-sm tw-bg-gray-300 tw-bg-none tw-py-0 tw-px-4 tw-text-center tw-text-sm tw-font-bold tw-uppercase tw-leading-none tw-text-gray-700 tw-shadow-sm"
                        title="Approve">
                        Approval
                        <i class="material-icons tw-pl-2 tw-text-gray-500">check_circle</i>
                    </button>
                    <button wire:click="generateTag('rejection')"
                        class="tw-m-0 tw-inline-flex tw-h-10 tw-cursor-pointer tw-select-none tw-items-center tw-justify-center tw-overflow-visible tw-whitespace-nowrap tw-rounded-sm tw-bg-gray-300 tw-bg-none tw-py-0 tw-px-4 tw-text-center tw-text-sm tw-font-bold tw-uppercase tw-leading-none tw-text-gray-700 tw-shadow-sm"
                        title="Reject">
                        Rejection
                        <i class="material-icons tw-pl-2 tw-text-gray-500">cancel</i>
                    </button>
                    <button wire:click="generateTag('pre-released')"
                        class="tw-m-0 tw-inline-flex tw-h-10 tw-cursor-pointer tw-select-none tw-items-center tw-justify-center tw-overflow-visible tw-whitespace-nowrap tw-rounded-sm tw-bg-gray-300 tw-bg-none tw-py-0 tw-px-4 tw-text-center tw-text-sm tw-font-bold tw-uppercase tw-leading-none tw-text-gray-700 tw-shadow-sm"
                        title="Pre-Release">
                        Pre-Released
                        <i class="material-icons tw-pl-2 tw-text-gray-500">pending</i>
                    </button>
                    <button wire:click="generateTag('destruction')"
                        class="tw-m-0 tw-inline-flex tw-h-10 tw-cursor-pointer tw-select-none tw-items-center tw-justify-center tw-overflow-visible tw-whitespace-nowrap tw-rounded-sm tw-bg-gray-300 tw-bg-none tw-py-0 tw-px-4 tw-text-center tw-text-sm tw-font-bold tw-uppercase tw-leading-none tw-text-gray-700 tw-shadow-sm"
                        title="Pre-Release">
                        Destruction
                        <i class="material-icons tw-pl-2 tw-text-gray-500">delete</i>
                    </button>
                </div>
            </div>
            <x-modal.dialog wire:model.defer="showPreReleaseModal">
                <x-slot name="title">Generate Pre-Release Tag</x-slot>
                <x-slot name="content">
                    <div class="tw-grid-cols-1 tw-w-full tw-grid tw-gap-4 sm:tw-gap-6">
                        <div class="tw-mt-5 lg:tw-mt-0">
                            <x-input.label >Reason for Pre-Release</x-input.label >
                            <x-input.text wire:model="record.pre_release_reason" />
                            @error('record.pre_release_reason')<x-input.error :message="$message"/>@enderror
                        </div>
                        <div class="tw-col-span-4 tw-mt-5 lg:tw-mt-0">
                            <x-input.label >Requested By</x-input.label >
                            <x-input.text wire:model="record.pre_release_requested_by" />
                            @error('record.pre_release_requested_by')<x-input.error :message="$message"/>@enderror
                        </div>
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <x-button.secondary wire:click="$set('showPreReleaseModal', false)">Cancel</x-button.secondary>
                    <x-button.primary wire:click="generateTag('pre-released')" icon="disk" prefixIcon="true">Generate</x-button.primary>
                </x-slot>
            </x-modal.dialog>
        @endif
    </x-form-footer-buttons>

</form>
