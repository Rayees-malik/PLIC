@php
    $classes = 'tw-overflow-visible tw-py-0 tw-px-4 tw-m-0 tw-w-full tw-text-base tw-font-medium tw-leading-9 tw-break-words tw-rounded-[3px] tw-border tw-border-solid tw-appearance-none focus:tw-outline-none tw-cursor-text tw-border-[#d8d8d8] focus:tw-border-[#145994] tw-shadow-[0_2px_1px_0px_rgb(38,50,56,0.6)] focus:tw-shadow-[0_2px_1px_0px_rgb(14,64,106,0.6)] tw-transition tw-ease-in duration-[200ms]';
@endphp

<textarea {{ $attributes->merge(['class' => $classes]) }} {{ $attributes }}></textarea>
