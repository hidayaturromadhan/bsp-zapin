@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="
        width:100%;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        flex-wrap:wrap;
        padding:16px 20px;
        border-top:1px solid var(--line, #e5e7eb);
        background:#fff;
    ">
        {{-- Info --}}
        <div style="
            font-size:13px;
            color:var(--text3, #6b7280);
            font-weight:600;
        ">
            Menampilkan
            <span style="font-weight:800;color:var(--text, #111827);">{{ $paginator->firstItem() }}</span>
            sampai
            <span style="font-weight:800;color:var(--text, #111827);">{{ $paginator->lastItem() }}</span>
            dari
            <span style="font-weight:800;color:var(--text, #111827);">{{ $paginator->total() }}</span>
            data
        </div>

        {{-- Buttons --}}
        <div style="
            display:flex;
            align-items:center;
            gap:6px;
            flex-wrap:wrap;
        ">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="
                    min-width:36px;
                    height:36px;
                    padding:0 12px;
                    display:inline-flex;
                    align-items:center;
                    justify-content:center;
                    border-radius:10px;
                    border:1px solid #e5e7eb;
                    background:#f9fafb;
                    color:#9ca3af;
                    font-size:13px;
                    font-weight:800;
                    cursor:not-allowed;
                    user-select:none;
                ">
                    ‹
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="
                    min-width:36px;
                    height:36px;
                    padding:0 12px;
                    display:inline-flex;
                    align-items:center;
                    justify-content:center;
                    border-radius:10px;
                    border:1px solid #d1d5db;
                    background:#fff;
                    color:#374151;
                    font-size:13px;
                    font-weight:800;
                    text-decoration:none;
                    transition:.15s ease;
                ">
                    ‹
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Dots --}}
                @if (is_string($element))
                    <span style="
                        min-width:36px;
                        height:36px;
                        padding:0 10px;
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        color:#9ca3af;
                        font-size:13px;
                        font-weight:800;
                        user-select:none;
                    ">
                        {{ $element }}
                    </span>
                @endif

                {{-- Page Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" style="
                                min-width:36px;
                                height:36px;
                                padding:0 12px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:10px;
                                border:1px solid var(--g700, #21560e);
                                background:var(--g800, #173f08);
                                color:#fff;
                                font-size:13px;
                                font-weight:900;
                                user-select:none;
                                box-shadow:0 8px 18px rgba(23,63,8,.18);
                            ">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" style="
                                min-width:36px;
                                height:36px;
                                padding:0 12px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:10px;
                                border:1px solid #d1d5db;
                                background:#fff;
                                color:#374151;
                                font-size:13px;
                                font-weight:800;
                                text-decoration:none;
                                transition:.15s ease;
                            ">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="
                    min-width:36px;
                    height:36px;
                    padding:0 12px;
                    display:inline-flex;
                    align-items:center;
                    justify-content:center;
                    border-radius:10px;
                    border:1px solid #d1d5db;
                    background:#fff;
                    color:#374151;
                    font-size:13px;
                    font-weight:800;
                    text-decoration:none;
                    transition:.15s ease;
                ">
                    ›
                </a>
            @else
                <span style="
                    min-width:36px;
                    height:36px;
                    padding:0 12px;
                    display:inline-flex;
                    align-items:center;
                    justify-content:center;
                    border-radius:10px;
                    border:1px solid #e5e7eb;
                    background:#f9fafb;
                    color:#9ca3af;
                    font-size:13px;
                    font-weight:800;
                    cursor:not-allowed;
                    user-select:none;
                ">
                    ›
                </span>
            @endif
        </div>
    </nav>
@else
    @if ($paginator->total() > 0)
        <div style="
            width:100%;
            padding:16px 20px;
            border-top:1px solid var(--line, #e5e7eb);
            background:#fff;
            font-size:13px;
            color:var(--text3, #6b7280);
            font-weight:600;
        ">
            Menampilkan
            <span style="font-weight:800;color:var(--text, #111827);">{{ $paginator->firstItem() }}</span>
            sampai
            <span style="font-weight:800;color:var(--text, #111827);">{{ $paginator->lastItem() }}</span>
            dari
            <span style="font-weight:800;color:var(--text, #111827);">{{ $paginator->total() }}</span>
            data
        </div>
    @endif
@endif