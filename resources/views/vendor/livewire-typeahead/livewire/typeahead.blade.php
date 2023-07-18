<div>
    <div x-data="{
        open: @entangle('showDropdown'),
        search: @entangle('search'),
        selected: @entangle('selected'),
        highlightedIndex: 0,
        highlightPrevious() {
            if (this.highlightedIndex > 0) {
                this.highlightedIndex = this.highlightedIndex - 1;
                this.scrollIntoView();
            }
        },
        highlightNext() {
            if (this.highlightedIndex < this.$refs.results.children.length - 1) {
                this.highlightedIndex = this.highlightedIndex + 1;
                this.scrollIntoView();
            }
        },
        updateSelected(id, name) {
            this.selected = id;
            this.search = name;
            this.open = false;
            this.highlightedIndex = 0;
        },
        reset() {
            this.selected = '';
            this.search = '';
            this.open = false;
            this.highlightedIndex = 0;
        },
        scrollIntoView() {
            this.$refs.results.children[this.highlightedIndex].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        },
    }" class="tw-space-y-1">
        <div class="tw-relative" x-on:value-selected="updateSelected($event.detail.id, $event.detail.name)">
            <div class="tw-relative">
                {{-- <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <!-- Heroicon name: solid search-circle -->
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z"
                            clip-rule="evenodd" />
                    </svg>
                </div> --}}
                <x-input.text x-ref="input"
                x-on:keydown.arrow-down.stop.prevent="highlightNext()"
                x-on:keydown.arrow-up.stop.prevent="highlightPrevious()"
                x-on:keydown.enter.stop.prevent="(!$refs.input.text) ? $dispatch('value-selected', {
                    id: $refs.results.children[highlightedIndex].getAttribute('data-result-id'),
                    name: $refs.results.children[highlightedIndex].getAttribute('data-result-name')
                }) : ''"
                    wire:model.debounce.150ms="search" type="search"
                    class="tw-block tw-w-full tw-border-gray-300 tw-rounded-md focus:tw-border-blue-300 focus:tw-ring focus:tw-ring-blue-200 focus:tw-ring-opacity-50 sm:tw-text-sm sm:tw-leading-5"
                    placeholder="{{ $placeholder }}"
                    type="search"/>
            </div>

            <div x-show="open"
                x-cloak
                x-on:click.away="open = false"
                x-transition:enter=""
                x-transition:enter-start=""
                x-transition:enter-end=""
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="tw-absolute tw-z-10 tw-w-full tw-mt-1 tw-bg-white tw-rounded-md tw-shadow-lg">
                <ul
                    x-ref="results"
                    tabindex="-1" role="listbox"
                    aria-labelledby="listbox-label"
                    aria-activedescendant="listbox-item-3"
                    class="tw-py-1 tw-overflow-auto tw-text-base tw-leading-6 tw-rounded-md tw-shadow-xs tw-max-h-60 focus:tw-outline-none sm:tw-text-sm sm:tw-leading-5">
                    @forelse($results as $index => $result)
                    <li
                        wire:key="{{ $index }}"
                        data-result-id="{{ $result->id }}"
                        data-result-name="{{ $result->{$typeaheadText} }}"
                        x-on:click.stop="$dispatch('value-selected', {
                            id: {{ $result->id }},
                            name: '{{ addslashes($result->{$typeaheadText}) }}'
                        })"
                        class="tw-relative tw-py-2 tw-pl-10 tw-text-gray-900 tw-cursor-default tw-select-none tw-pr-9 hover:tw-bg-indigo-600 hover:tw-text-white"
                        :class="{
                            'tw-bg-indigo-400': {{ $index }} === highlightedIndex,
                            'tw-text-white': {{ $index }} === highlightedIndex
                        }"
                        role="option">
                        <span class="tw-block tw-truncate">
                          {{ $result->typehead_option }}
                        </span>
                    </li>
                    @empty
                    <li
                        class="tw-relative tw-py-2 tw-pl-10 tw-text-gray-900 tw-cursor-default tw-select-none tw-pr-9 hover:tw-bg-indigo-600 hover:tw-text-white">
                        No results found</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
