<div>
  @include('wizards.navigation-steps')

  <h1>Release Checklist</h1>

  <div class="tw-w-full tw-grid lg:tw-grid-cols-4 lg:tw-gap-6 tw-grid-cols-2 tw-gap-4 ">
    <div class="tw-mt-5 lg:tw-mt-0 tw-col-span-8">
      <x-forms.fieldset>
        <x-slot:legend>3rd Party / Bulk</x-slot:legend>

        <label>Labelling Form Issued</label>
        <div class="inline-radio-group">
          <x-input.radio wire:model="labellingFormIssued" value="NA" label="N/A" />
          <x-input.radio wire:model="labellingFormIssued" value="YES" label="Yes" />
          <x-input.radio wire:model="labellingFormIssued" value="NO" label="No" />
        </div>

        <label class="tw-mt-6">Labelling Form Reviewed</label>
        <div class="inline-radio-group">
          <x-input.radio wire:model="labellingFormReviewed" value="NA" label="N/A" />
          <x-input.radio wire:model="labellingFormReviewed" value="YES" label="Yes" />
          <x-input.radio wire:model="labellingFormReviewed" value="NO" label="No" />
        </div>

        <label>Certificate Of Packaging</label>
        <div class="inline-radio-group">
          <x-input.radio wire:model="certificateOfPackaging" value="NA" label="N/A" />
          <x-input.radio wire:model="certificateOfPackaging" value="YES" label="Yes" />
          <x-input.radio wire:model="certificateOfPackaging" value="NO" label="No" />
        </div>

        <label>Certificate Of Analysis</label>
        <div class="inline-radio-group">
          <x-input.radio wire:model="certificateOfAnalysis" value="NA" label="N/A" />
          <x-input.radio wire:model="certificateOfAnalysis" value="YES" label="Yes" />
          <x-input.radio wire:model="certificateOfAnalysis" value="NO" label="No" />
        </div>

        <label>Contract Lab Results</label>
        <div class="inline-radio-group">
          <x-input.radio wire:model="contractLabResults" value="NA" label="N/A" />
          <x-input.radio wire:model="contractLabResults" value="YES" label="Yes" />
          <x-input.radio wire:model="contractLabResults" value="NO" label="No" />
        </div>
      </x-forms.fieldset>
    </div>
  </div>
  @include('wizards.navigation-buttons')
</div>
