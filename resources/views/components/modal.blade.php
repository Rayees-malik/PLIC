@props(['id', 'maxWidth'])

@php
$id = $id ?? md5($attributes->wire('model'));

$maxWidth = [
    'sm' => 'sm:tw-max-w-sm',
    'md' => 'sm:tw-max-w-md',
    'lg' => 'sm:tw-max-w-lg',
    'xl' => 'sm:tw-max-w-xl',
    '2xl' => 'sm:tw-max-w-2xl',
][$maxWidth ?? '2xl'];
@endphp

<div
    x-data="{ show: @entangle($attributes->wire('model')).defer }"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="tw-fixed tw-inset-0 tw-overflow-y-auto tw-px-4 tw-pb-6 tw-pt-64 sm:tw-px-0 tw-z-1050"
    style="display: none;"
>
    <div x-show="show" class="tw-fixed tw-inset-0 tw-transform tw-transition-all" x-on:click="show = false"
        x-transition:enter="tw-ease-out tw-duration-300"
        x-transition:enter-start="tw-opacity-0"
        x-transition:enter-end="tw-opacity-100"
        x-transition:leave="tw-ease-in tw-duration-200"
        x-transition:leave-start="tw-opacity-100"
        x-transition:leave-end="tw-opacity-0"
      >
        <div class="tw-absolute tw-inset-0 tw-bg-gray-500 dark:tw-bg-gray-900 tw-opacity-75"></div>
    </div>

    <div
      x-show="show"
      class="tw-mb-6 tw-bg-white dark:tw-bg-gray-800 tw-rounded-lg tw-overflow-hidden tw-shadow-xl tw-transform tw-transition-all sm:tw-w-full {{ $maxWidth }} sm:tw-mx-auto"
      x-trap.inert.noscroll="tw-show"
      x-transition:enter="tw-ease-out tw-duration-300"
      x-transition:enter-start="tw-opacity-0 tw-translate-y-4 sm:tw-translate-y-0 sm:tw-scale-95"
      x-transition:enter-end="tw-opacity-100 tw-translate-y-0 sm:tw-scale-100"
      x-transition:leave="tw-ease-in tw-duration-200"
      x-transition:leave-start="tw-opacity-100 tw-translate-y-0 sm:tw-scale-100"
      x-transition:leave-end="tw-opacity-0 tw-translate-y-4 sm:tw-translate-y-0 sm:tw-scale-95"
    >
        {{ $slot }}
    </div>
</div>
