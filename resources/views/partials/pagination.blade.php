@if ($paginator->hasPages())
    <div class="catalog_pager">
        @if ($paginator->onFirstPage())

        @else
            <a class="nav back" href="{{ $paginator->previousPageUrl() }}" rel="prev">назад</a>
        @endif

        @foreach ($elements as $element)

            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span>
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span>
                            {{ $page }}
                        </span>
                    @else
                        <a rel="nofollow" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="nav forward" href="{{ $paginator->nextPageUrl() }}" rel="next">вперед</a>
        @else

        @endif

    </div>
@endif
