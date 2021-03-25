<input
    id="{{ $name }}"
    type="text"
    placeholder="{{ $placeholder }}"
    class="{{ $styles['searchInput'] }}"

    wire:keydown.enter.prevent=""
    wire:model.debounce.300ms="searchTerm"

    x-on:click="isOpen = true;searchTerm = ' '"
    {{-- wire:mouseover="searchTerm = ' '" --}}
    x-on:keydown="isOpen = true; searchTerm = ' '"

    x-on:keydown.arrow-up="selectUp(@this)"
    x-on:keydown.arrow-down="selectDown(@this)"
    x-on:keydown.enter.prevent="confirmSelection(@this)"
/>
