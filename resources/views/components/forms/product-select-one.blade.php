@php
  $selectClasses = "tw-py-0 tw-px-4 tw-m-0 tw-w-full tw-h-10 tw-text-base tw-font-medium tw-block tw-leading-9 tw-normal-case tw-whitespace-pre tw-rounded-sm tw-border tw-border-solid tw-appearance-none tw-cursor-pointer tw-border-zinc-300 tw-focus:border-sky-800";
@endphp

<div class="dropdown-wrap">
  <div class="dropdown-icon">
    <select {{ $attributes->get('wire:model') }} {{ $attributes->merge(['class' => $selectClasses]) }}>
      <option value="" class="tw-leading-9 tw-text-gray-800 tw-whitespace-nowrap tw-cursor-pointer">
        Select a vendor...
      </option>
      @foreach ($options as $key => $value)
      <option value="{{ $key }}" class="tw-leading-9 tw-text-gray-800 tw-whitespace-nowrap tw-cursor-pointer">
        {{ $value }}
      </option>
      @endforeach
    </select>
  </div>
</div>
