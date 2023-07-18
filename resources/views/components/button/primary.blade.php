@props(['icon' => '', 'prefixIcon' => false,])

<div
  {{ $attributes->merge([
    'type' => 'button',
    'class' => 'tw-inline-flex tw-justify-center tw-items-center tw-py-0 tw-px-4 tw-my-0 tw-mr-0 tw-ml-auto tw-h-10 tw-text-xs tw-font-bold tw-leading-none tw-text-center tw-text-gray-100 tw-uppercase tw-whitespace-nowrap tw-break-words tw-rounded-sm tw-cursor-pointer tw-select-none tw-xl:text-sm tw-bg-sky-800'
  ]) }}

  {{-- style="display: inline-flex; box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px; text-decoration: none; outline: none; min-width: 100px;" --}}
>
@if ($prefixIcon && $icon)
<i
    class="tw-hidden tw-my-0 tw-mx-1 tw-text-2xl tw-not-italic tw-leading-none tw-text-center tw-text-gray-100 tw-normal-case tw-whitespace-nowrap tw-border-0 tw-border-gray-200 tw-border-solid tw-cursor-pointer tw-pointer-events-none tw-xl:inline-block"
    style='font-family: "Material Icons"; font-weight: normal; letter-spacing: normal; overflow-wrap: normal; direction: ltr; font-feature-settings: "liga";'
    >{{ $icon }}</i
  >
  @endif

  {{ $slot }}

  @if (!$prefixIcon && $icon)
  <i
    class="tw-hidden tw-my-0 tw-mx-1 tw-text-2xl tw-not-italic tw-leading-none tw-text-center tw-text-gray-100 tw-normal-case tw-whitespace-nowrap tw-border-0 tw-border-gray-200 tw-border-solid tw-cursor-pointer tw-pointer-events-none tw-xl:inline-block"
    style='font-family: "Material Icons"; font-weight: normal; letter-spacing: normal; overflow-wrap: normal; direction: ltr; font-feature-settings: "liga";'
    >{{ $icon }}</i
  >
  @endif

</div>
