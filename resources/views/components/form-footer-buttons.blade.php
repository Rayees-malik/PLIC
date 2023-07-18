@props([
    'cancelRoute' => null,
    'cancelText' => 'Cancel',
    'cancelTitle' => 'Cancel',
    'submitText' => 'Save',
    'submitTitle' => 'Save Record',
])

<div class="tw-flex tw-w-full tw-justify-between">
    <a href="{{ $cancelRoute }}" title="{{ $cancelTitle }}"
        class="tw-m-0 tw-inline-flex tw-h-10 tw-cursor-pointer tw-select-none tw-items-center tw-justify-center tw-whitespace-nowrap tw-rounded-sm tw-bg-transparent tw-py-0 tw-px-4 tw-text-center tw-text-sm tw-font-bold tw-uppercase tw-leading-none tw-text-sky-800 tw-opacity-40"
        style="opacity: 0.4;">
        <i class="material-icons">arrow_back</i>
        {{ $cancelText }}
    </a>

    {{ $slot }}

    <button type="submit"
        class="tw-m-0 tw-inline-flex tw-h-10 tw-cursor-pointer tw-select-none tw-items-center tw-justify-center tw-overflow-visible tw-whitespace-nowrap tw-rounded-sm tw-bg-sky-800 tw-bg-none tw-py-0 tw-px-4 tw-text-center tw-text-sm tw-font-bold tw-uppercase tw-leading-none tw-text-gray-100"
        title="Next Step">
        {{ $submitText }}
        <i class="material-icons">arrow_forward</i>
    </button>
</div>
