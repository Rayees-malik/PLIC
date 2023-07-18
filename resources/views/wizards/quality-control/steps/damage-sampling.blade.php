<div>
  @include('wizards.navigation-steps')

  <h1>Damage and Sampling Report</h1>
  <div class="tw-w-full tw-grid lg:tw-grid-cols-4 lg:tw-gap-6 tw-grid-cols-2 tw-gap-4 ">
    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1">
      <x-input.label ># of Damaged Cartons</x-input.label >
      <x-input.text wire:model="numberDamagedCartons" />
      @error('number_damaged_cartons') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1">
      <x-input.label ># of Damaged Units</x-input.label >
      <x-input.text wire:model="numberDamagedUnits" />
      @error('number_damaged_units') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

     <div class="tw-mt-5 lg:tw-mt-0 tw-col-span-8">
      <x-input.label >Nature of Damage</x-input.label >
      <x-input.textarea wire:model="natureOfDamage" rows="5" />
      @error('nature_of_damage') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1 xl:tw-col-span-2">
      <x-input.label ># of Units Sent For Testing</x-input.label >
      <x-input.text wire:model="numberUnitsSentForTesting" />
      @error('number_units_sent_for_testing') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1 xl:tw-col-span-2">
      <x-input.label ># of Units For Stability</x-input.label >
      <x-input.text wire:model="numberUnitsForStability" />
      @error('number_units_for_stability') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1 xl:tw-col-span-2">
      <x-input.label ># of Units Retained</x-input.label >
      <x-input.text wire:model="numberUnitsRetained" />
      @error('number_units_retained') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 lg:tw-col-span-2 tw-col-span-1 xl:tw-col-span-2">
      <x-input.label >Units Taken</x-input.label >
      <x-input.text wire:model="unitsTaken" />
      @error('units_taken') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>

    <div class="tw-mt-5 lg:tw-mt-0 tw-col-span-8">
      <x-input.label >Regulatory Compliance Comments</x-input.label >
      <x-input.textarea wire:model="regulatoryComplianceComments" rows="5" />
      @error('regulatory_compliance_comments') <x-input.error>{{ $message }}</x-input.error> @enderror
    </div>
  </div>

  @include('wizards.navigation-buttons')
</div>
