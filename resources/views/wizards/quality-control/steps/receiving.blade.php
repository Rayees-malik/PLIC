<div>
  @include('wizards.navigation-steps')

  <h1>Receiving</h1>
  <div class="tw-w-full tw-grid lg:tw-grid-cols-6 lg:tw-gap-6 tw-grid-cols-2 tw-gap-4 ">
    <div class="tw-mt-5 lg:tw-mt-0 tw-col-span-2 lg:tw-col-span-3">
      <x-input.label >Vendor</x-input.label >
      <x-forms.vendor-select-one wire:model="vendorId" />
      @error('vendorId') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 md:tw-mt-0 lg:tw-col-span-4 tw-col-span-2 lg:tw-order-2">
      <x-input.label >Product</x-input.label >
      {{-- <x-input.text wire:model="productId" /> --}}
      <livewire:product-typeahead :selected="$product"/>
      @error('productId') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-1 tw-col-span-2 -lg:tw-order-1">
      <x-input.label >PO Number</x-input.label >
      <x-input.text wire:model="poNumber" value="" />
      @error('poNumber') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1">
      <x-input.label >Received At</x-input.label >
      <x-input.datepicker wire:model="receivedDate" />
      @error('receivedDate') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-1 tw-col-span-1 lg:tw-order-2">
      <x-input.label >Quantity Received</x-input.label >
      <x-input.text wire:model="quantityReceived" />
      @error('quantityReceived') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-1 tw-col-span-1 lg:tw-order-2">
      <x-input.label >Lot Number</x-input.label >
      <x-input.text wire:model="lotNumber" />
      @error('lotNumber') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1 lg:tw-order-2">
      <x-input.label >Expiry Date</x-input.label >
      <x-input.datepicker wire:model="expiryDate" />
      @error('expiryDate') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1 lg:tw-order-2">
      <x-input.label >Bin Number</x-input.label >
      <x-input.text wire:model="binNumber" />
      @error('binNumber') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1 lg:tw-order-2 xl:tw-col-span-2">
      <x-input.label >DIN/NPN</x-input.label >
      <x-input.text wire:model="dinNpnNumber" />
      @error('dinNpnNumber') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 lg:tw-order-2">
      <x-input.checkbox wire:model="sealsIntact" label="Seals Intact" value="1" />
      @error('sealsIntact') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 lg:tw-order-2">
      <x-input.checkbox wire:model="dinNpnOnLabel" label="DIN/NPN on Label" value="1" />
      @error('dinNpnOnLabel') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 lg:tw-order-2">
      <x-input.checkbox wire:model="bilingualLabel" label="Bilingual Label" value="1" />
      @error('bilingualLabel') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-3 lg:tw-order-last">
        <x-input.label >Certificates</x-input.label >
        <ul>
          @forelse ($existingCertificates as $item)
            <li>$item</li>
          @empty

          @endforelse
        </ul>
        <x-input.filepond wire:model="certificates.*" multiple />

        @error('certificates.*') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 tw-col-span-full lg:tw-col-span-3 lg:tw-order-last">
      <x-input.label >Receiving Comments</x-input.label >
      <x-input.textarea wire:model="receivingComments" rows="5" />
      @error('receivingComments') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>
  </div>

@include('wizards.navigation-buttons')
</div>
