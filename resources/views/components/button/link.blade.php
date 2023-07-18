@props(['icon' => '', 'href' => '', 'target' => ''])

<a
  href="{{ $href }}"
  target="{{ $target }}"
  class="tw-inline-flex tw-justify-center tw-items-center tw-py-0 tw-px-4 tw-m-0 tw-h-10 tw-text-sm tw-font-bold tw-leading-none tw-text-center tw-text-gray-700 tw-uppercase tw-whitespace-nowrap tw-break-words tw-bg-gray-100 tw-rounded-sm tw-cursor-pointer tw-select-none"
  style="text-decoration: none; box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px; outline: none;"
>
  <i
    class="tw-inline-block tw-my-0 tw-mx-1 tw-text-2xl tw-not-italic tw-leading-none tw-text-center tw-text-gray-700 tw-normal-case tw-whitespace-nowrap tw-border-0 tw-border-gray-200 tw-border-solid tw-cursor-pointer tw-pointer-events-none"
    style='font-family: "Material Icons"; font-weight: normal; letter-spacing: normal; overflow-wrap: normal; direction: ltr; font-feature-settings: "liga";'
    >{{ $icon }}</i
  >
  {{ $slot }}
</a>
