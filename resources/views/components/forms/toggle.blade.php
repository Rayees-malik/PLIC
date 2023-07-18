@props([
  'label',
  'wireModel'
])

<!-- Toggle -->
<div
    x-data="{ value: @entangle($wireModel) }"
    class="tw-flex tw-items-center"
    x-id="['toggle-label']"
>
    <input type="hidden" :value="value">

    <!-- Label -->
    <label
        @click="$refs.toggle.click(); $refs.toggle.focus()"
        :id="$id('toggle-label')"
        class="text-gray-900"
    >{{ $label }}</label>

    <!-- Button -->
    <button
        x-ref="toggle"
        @click="value = ! value; $dispatch('input')"
        type="button"
        role="switch"
        :aria-checked="value"
        :aria-labelledby="$id('toggle-label')"
        :class="value ? 'tw-bg-gray-900 tw-border-2 tw-border-white' : 'tw-bg-white tw-border-2 tw-border-gray-900'"
        class="tw-relative tw-ml-4 tw-inline-flex tw-w-14 tw-rounded-full tw-py-1 tw-px-0"
    >
        <span
            :class="value ? 'tw-bg-white tw-translate-x-6' : 'tw-bg-gray-900 tw-translate-x-1'"
            class="tw-h-6 tw-w-6 tw-rounded-full tw-transition"
            aria-hidden="true"
        ></span>
    </button>
</div>
