@if ($paginator->hasPages())
    <nav class="media-pagination-nav" role="navigation" aria-label="Pagination Navigation">
        <div class="media-pagination-card">
            <div class="media-pagination-info">
                @if ($paginator->firstItem())
                    <span class="media-pagination-info-main">
                        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
                    </span>
                    <span class="media-pagination-info-sub">
                        dari {{ $paginator->total() }} data
                    </span>
                @else
                    <span class="media-pagination-info-main">Belum ada data</span>
                @endif
            </div>

            <div class="media-pagination-list">
                @if ($paginator->onFirstPage())
                    <span class="media-page-btn media-page-btn--disabled" aria-disabled="true" aria-label="Previous">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </span>
                @else
                    <a class="media-page-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="media-page-dots" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="media-page-btn media-page-btn--active" aria-current="page">
                                    {{ $page }}
                                </span>
                            @else
                                <a class="media-page-btn" href="{{ $url }}" aria-label="Page {{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a class="media-page-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                @else
                    <span class="media-page-btn media-page-btn--disabled" aria-disabled="true" aria-label="Next">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif