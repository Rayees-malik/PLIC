<div>
  @include('wizards.navigation-steps')

  <h1>Identity Testing</h1>
  <div class="tw-w-full tw-grid lg:tw-grid-cols-4 lg:tw-gap-6 tw-grid-cols-2 tw-gap-4 ">
    <div class="tw-mt-5 lg:tw-mt-0 tw-col-span-8">
      <x-input.label >Identity Description</x-input.label >
      <x-input.textarea wire:model="identityDescription" rows="5" />
      @error('identityDescription') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1 xl:tw-col-span-2">
      {{-- <x-input.label >Matches Written Specification</x-input.label > --}}
      <x-input.checkbox wire:model="matchesWrittenSpecification" value="1" label="Matches Written Specification" />
      @error('matchesWrittenSpecification') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 tw-col-span-8">
      <x-input.label >Out of Specifications Report</x-input.label >
      <x-input.textarea wire:model="outOfSpecificationsComment" rows="5" />
      @error('outOfSpecificationsComment') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>
  </div>

  @include('wizards.navigation-buttons')
</div>
