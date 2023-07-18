@php
    $classes = 'tw-overflow-visible tw-py-0 tw-block tw-m-0 tw-w-full tw-h-10 tw-text-base tw-font-medium tw-leading-9 tw-break-words tw-appearance-none';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }} {{ $attributes }}>{{ $slot }}</span>
