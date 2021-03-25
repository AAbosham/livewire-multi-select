<div id="options" class="{{ $styles['search'] }}">

    @include($searchInputView, [
        'name' => $name,
        'placeholder' => $placeholder,
        'styles' => $styles,
    ])

    @if($isSearching)
        @include($searchOptionsContainer, [
            'options' => $options,
            'emptyOptions' => $emptyOptions,
            'isSearching' => $isSearching,
            'styles' => $styles,
            'value' => $value,
        ])
    @endif

</div>
