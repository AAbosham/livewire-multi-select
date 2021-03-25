<div
    class="{{ $styles['searchOptionsContainer'] }}"
    x-show="isOpen"
    style="max-height: 180px;"
>
    @if(!$emptyOptions)
        
        @foreach($options as $option)
            {{-- @if(!in_array($option,$value)) --}}
                @include($searchOptionItem, [
                    'option' => $option,
                    'index' => $loop->index,
                    'styles' => $styles,
                ])
            {{-- @endif --}}
        @endforeach

      
    @elseif ($isSearching)
        @include($searchNoResultsView, [
            'styles' => $styles,
        ])
    @endif
</div>
