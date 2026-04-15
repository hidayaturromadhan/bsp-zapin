@if ($paginator->hasPages())
    <nav class="media-pagination-nav" role="navigation" aria-label="Pagination Navigation">
        <div class="media-pagination-summary">
            Showing
            <span>{{ $paginator->firstItem() }}</span>
            to
            <span>{{ $paginator->lastItem() }}</span>
            of
            <span>{{ $paginator->total() }}</span>
            results
        </div>

        <div class="media-pagination-list">
            @if ($paginator->onFirstPage())
                <span class="media-page-btn media-page-btn--disabled">‹ Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="media-page-btn" rel="prev">‹ Prev</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="media-page-dots">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="media-page-btn media-page-btn--active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="media-page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="media-page-btn" rel="next">Next ›</a>
            @else
                <span class="media-page-btn media-page-btn--disabled">Next ›</span>
            @endif
        </div>
    </nav>
@endif