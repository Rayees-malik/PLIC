@props(['products' => []])

<div
    x-data="{
        multiple: false,
        renderChoiceLimit: 50,
        value: 1,
        options: [
          @foreach ($products as $product)
            {{ $product }}
          @endforeach
        ],
        init() {
            this.$nextTick(() => {
                let choices = new Choices(this.$refs.select, {
                  renderChoiceLimit: this.renderChoiceLimit,
                })

                let refreshChoices = () => {
                    let selection = this.multiple ? this.value : [this.value]

                    choices.clearStore()
                    choices.setChoices(this.options.map(({ value, label }) => ({
                        value,
                        label,
                        selected: selection.includes(value),
                    })))
                }

                refreshChoices()

                this.$refs.select.addEventListener('change', () => {
                    this.value = choices.getValue(true)
                })

                this.$watch('value', () => refreshChoices())
                this.$watch('options', () => refreshChoices())
            })
        }
    }"
    class="tw-max-w-sm tw-w-full"
>
    <select x-ref="select" :multiple="multiple"></select>
</div>
