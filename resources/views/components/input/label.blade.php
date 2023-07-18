@php
    $classes = 'tw-inline-block tw-mb-0 tw-w-full tw-font-normal tw-leading-6 tw-text-left
        tw-text-gray-700 tw-break-words tw-border-0 tw-border-gray-200 tw-border-solid
        tw-cursor-default';
@endphp

<label {{ $attributes->merge(['class' => $classes]) }} {{ $attributes }}>{{ $slot }}</label>
