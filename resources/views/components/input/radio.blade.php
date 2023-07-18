@props([
    'value',
    'label',
])

<div class="tw-flex tw-items-center">
    <input type="radio" {{ $attributes }} class="tw-h-4 tw-w-4 tw-border-gray-300 tw-text-indigo-600 focus:tw-ring-indigo-500" value="{{ $value }}">
    <label class="tw-ml-3 tw-mb-0 tw-block tw-text-sm tw-font-medium tw-text-gray-700">{{ $label }}</label>
</div>
