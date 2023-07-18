@php
  $selectClasses = "tw-py-0 tw-px-4 tw-m-0 tw-w-full tw-h-10 tw-text-base tw-font-medium tw-block tw-leading-9 tw-normal-case tw-whitespace-pre tw-rounded-sm tw-border tw-border-solid tw-appearance-none tw-cursor-pointer tw-border-zinc-300 tw-focus:border-sky-800";
@endphp

<div
x-cloak
  wire:ignore
    x-data="{
        multiple: false,
        renderChoiceLimit: 25,
        searchResultLimit: 25,
        searchFloor: 2,
        value: @entangle('selections'),
        options: {{ json_encode($options) }},
        debounce: 120,
        init() {
            this.$nextTick(() => {
                let choices = new Choices(this.$refs.select, {
                    renderChoiceLimit: this.renderChoiceLimit,
                    searchResultLimit: this.searchResultLimit,
                    searchFloor: this.searchFloor,
                    allowHTML: false,
                })

                const refreshChoices = () => {
                    let selection = this.multiple ? this.value : [this.value]

                    choices.clearStore()

                    choices.setChoices(this.options.map(({ value, label }) => ({
                        value,
                        label,
                        selected: selection.includes(value),
                    })))
                }

                this.$refs.select.addEventListener('change', () => {
                    this.value = choices.getValue(true)
                    $wire.emitUp('productSelected', $wire.model, this.value)
                })

                this.$refs.select.addEventListener('search', async (e) => {
                    if (e.detail.value) {
                        clearTimeout(this.debounce)
                        this.debounce = setTimeout(() => {
                            $wire.call('search', e.detail.value)
                        }, 300)
                    {{-- refreshChoices()._handleSearch(e.detail.value) --}}
                    }
                })

                $wire.on('select-options-updated', (options, value) => {
                    this.options = options
                    refreshChoices()
                    {{-- refreshChoices()._handleSearch(value) --}}
                })

                this.$watch('value', () => refreshChoices())
                this.$watch('options', () => refreshChoices())

                refreshChoices()
            })
        }
    }"
    class="tw-w-full">
    <select x-ref="select" :multiple="multiple"></select>
</div>
