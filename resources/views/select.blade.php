<div>

    <div>
        @if(!$searchable && $shouldShow)
            @include($defaultView, [
                'name' => $name,
                'options' => $options,
                'placeholder' => $placeholder,
                'styles' => $styles,
            ])
        @endif
    </div>

    <div x-on:click.away="isOpen = false">
        @if($searchable)
            @if(!empty($value))
                <div class="{{ $styles['searchContainer'] }}">
                    @foreach($value as $key => $valueData)
                        @include($searchSelectedOptionView, [
                            'styles' => $styles,
                            'selectedOption' => $selectedOption,
                            'value' => $valueData,
                            'name' => $name,
                        ])
                    @endforeach
                    <button
                        id="remove-all-selected"
                        type="button"

                        {{-- x-on:keydown.enter.prevent="removeSelection(@this,'{{ $value['value'] }}')" --}}
                        {{-- x-on:keydown.space.prevent="removeSelection(@this,'{{ $value['value'] }}')" --}}
                    >
                        <span
                            type="button"
                            wire:click.prevent="removeSelectedAll"
                        >
                            <svg class="h-4 w-4 text-black" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                    </button>
                </div>
            @endif

            <div>
                @include($searchView, [
                    'name' => $name,
                    'placeholder' => $placeholder,
                    'options' => $options,
                    'value' => $value,
                    'isSearching' => !empty($searchTerm),
                    'emptyOptions' => $options->isEmpty(),
                    'styles' => $styles,
                ])
            </div>
        @endif
    </div>

</div>
