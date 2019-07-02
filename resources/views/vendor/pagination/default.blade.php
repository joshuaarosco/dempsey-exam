@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <!-- <li class="page-item disabled"><span class="page-link">&laquo;</span></li> -->
            <li><a href="#">Previous</a></li>
        @else
            <!-- <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li> -->
            <li><a href="{{ $paginator->previousPageUrl() }}">Previous</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
            <!-- <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li> -->
            <li><a href="#">{{ $element }}</a></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <!-- <li class="page-item active"><span class="page-link">{{ $page }}</span></li> -->
                        <li><a href="#" class="current">{{ $page }}</a></li>
                    @else
                        <!-- <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li> -->
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <!-- <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li> -->
            <li><a href="{{ $paginator->nextPageUrl() }}">Next</a></li>
        @else
            <li><a href="#">Next</a></li>
        @endif
    </ul>
@endif
