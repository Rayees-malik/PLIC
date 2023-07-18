@php
    $value = isset($value) ? $value : null;
@endphp

<div
    x-data="{
        value: '{{ $value }}',
        init() {
            let picker = flatpickr(this.$refs.picker, {
                dateFormat: 'Y/m/d',
                defaultDate: this.value,
            })

            this.$watch('value', () => picker.setDate(this.value))
        },
    }"
    class="tw-w-full"
>
    <div class="tw-relative">
        <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3 tw-flex tw-items-center tw-pointer-events-none">
            <i class="material-icons tw-text-[#78909c]">calendar_today</i>
        </div>
        <x-input.text x-ref="picker" class="tw-pl-9" {{ $attributes }} />
    </div>
</div>
