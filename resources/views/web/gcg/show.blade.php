@extends('layouts.app')

@section('title', ($translation->name ?? 'GCG') . ' — GCG')

@section('content')

<style>
.gcgs-wrap {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 28px;
    align-items: start;
}

/* ── Sidebar ── */
.gcgs-sidebar {
    position: sticky;
    top: calc(var(--nav-h) + 20px);
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.gcgs-sidebar-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text3);
    padding: 0 10px;
    margin-bottom: 6px;
}
.gcgs-sidebar-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text2);
    text-decoration: none;
    transition: background .14s, color .14s;
    border: 1px solid transparent;
    line-height: 1.35;
}
.gcgs-sidebar-link:hover {
    background: var(--g50);
    color: var(--g900);
}
.gcgs-sidebar-link.is-active {
    background: var(--g100);
    border-color: var(--g200);
    color: var(--g900);
    font-weight: 700;
}
.gcgs-sidebar-count {
    margin-left: auto;
    font-size: 11px;
    font-weight: 600;
    color: var(--g700);
    background: var(--g100);
    border-radius: 999px;
    padding: 2px 8px;
    flex-shrink: 0;
}
.gcgs-sidebar-link.is-active .gcgs-sidebar-count {
    background: var(--g200);
}

/* ── Main ── */
.gcgs-main { display: flex; flex-direction: column; gap: 24px; min-width: 0; }

/* ── Breadcrumb ── */
.gcgs-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text3);
    flex-wrap: wrap;
}
.gcgs-breadcrumb a {
    color: var(--g700);
    text-decoration: none;
    font-weight: 500;
    transition: color .13s;
}
.gcgs-breadcrumb a:hover { color: var(--g900); }
.gcgs-breadcrumb-sep { opacity: .4; }
.gcgs-breadcrumb-current { color: var(--text2); font-weight: 600; }

/* ── Category header ── */
.gcgs-header {
    background: var(--g100);
    border: 1px solid var(--g200);
    border-radius: 16px;
    padding: 24px 28px;
}
.gcgs-header-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 0;
}
.gcgs-header-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--g900);
    line-height: 1.3;
}
.gcgs-header-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    color: var(--g700);
    background: var(--g200);
    border-radius: 999px;
    padding: 5px 12px;
    white-space: nowrap;
    flex-shrink: 0;
}

/* ── Description paragraph ── */
.gcgs-desc {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid var(--g200);
    font-size: 14px;
    color: var(--g800);
    line-height: 1.8;
    white-space: pre-line;
}

/* ── Search ── */
.gcgs-search-wrap { position: relative; }
.gcgs-search-icon {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    color: var(--text3);
    pointer-events: none;
    display: flex;
}
.gcgs-search {
    width: 100%;
    height: 42px;
    padding: 0 16px 0 40px;
    border-radius: 10px;
    border: 1px solid var(--line);
    background: var(--white);
    font-family: var(--font);
    font-size: 14px;
    color: var(--text);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.gcgs-search:focus {
    border-color: var(--g500);
    box-shadow: 0 0 0 3px rgba(47,125,50,.12);
}
.gcgs-search::placeholder { color: var(--text3); }

/* ── Doc list ── */
.gcgs-list { display: flex; flex-direction: column; gap: 10px; }

.gcgs-doc {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--white);
    border: 1px solid var(--line);
    padding: 16px 18px;
    border-radius: 12px;
    transition: border-color .15s, box-shadow .15s, transform .15s;
}
.gcgs-doc:hover {
    border-color: var(--g200);
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    transform: translateX(2px);
}

.gcgs-doc-file-badge {
    width: 42px; height: 42px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .03em;
}
.gcgs-doc-file-badge.pdf  { background: #fde8e8; color: #c0392b; }
.gcgs-doc-file-badge.docx { background: #e8f0fe; color: #1a56db; }
.gcgs-doc-file-badge.xlsx { background: #e3f9e5; color: #1a7431; }
.gcgs-doc-file-badge.pptx { background: #fff3e0; color: #e65100; }
.gcgs-doc-file-badge.other { background: var(--line2); color: var(--text3); }

.gcgs-doc-info { flex: 1; min-width: 0; }
.gcgs-doc-title {
    font-weight: 600;
    font-size: 14px;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 4px;
}
.gcgs-doc-meta {
    font-size: 12px;
    color: var(--text3);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.gcgs-doc-meta-sep { opacity: .35; }

.gcgs-doc-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

.gcgs-btn-preview {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 14px;
    border-radius: 8px;
    background: var(--white);
    color: var(--text2);
    font-size: 13px;
    font-weight: 500;
    font-family: var(--font);
    text-decoration: none;
    border: 1px solid var(--line);
    cursor: pointer;
    transition: background .14s, border-color .14s, color .14s;
    white-space: nowrap;
}
.gcgs-btn-preview:hover {
    background: var(--g50);
    border-color: var(--g200);
    color: var(--g900);
}

.gcgs-btn-download {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 8px;
    background: var(--g500);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    font-family: var(--font);
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background .14s, transform .14s;
    white-space: nowrap;
}
.gcgs-btn-download:hover {
    background: var(--g700);
    transform: translateY(-1px);
}

/* ── Empty ── */
.gcgs-empty {
    text-align: center;
    padding: 48px 24px;
    color: var(--text3);
    font-size: 14px;
    background: var(--white);
    border: 1px dashed var(--line);
    border-radius: 12px;
    display: none;
}
.gcgs-empty.show { display: block; }

/* ── Responsive ── */
@media (max-width: 860px) {
    .gcgs-wrap { grid-template-columns: 1fr; }
    .gcgs-sidebar {
        position: static;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 6px;
    }
    .gcgs-sidebar-label { display: none; }
    .gcgs-sidebar-link {
        padding: 7px 14px;
        border: 1px solid var(--line);
        font-size: 13px;
    }
}
@media (max-width: 560px) {
    .gcgs-header { padding: 18px 20px; }
    .gcgs-header-top { flex-direction: column; gap: 10px; }
    .gcgs-doc { flex-wrap: wrap; }
    .gcgs-doc-actions { width: 100%; justify-content: flex-end; }
}
</style>

<div class="gcgs-wrap">

    {{-- ── Sidebar ── --}}
    <aside class="gcgs-sidebar">
        <div class="gcgs-sidebar-label">
            {{ $locale === 'id' ? 'Kategori' : 'Categories' }}
        </div>
        @foreach($allCategories as $cat)
            @php
                $st = $cat->translations->firstWhere('locale', $locale)
                    ?? $cat->translations->first();
                $isActive = $cat->id === $category->id;
            @endphp
            @if($st)
                <a href="{{ route('gcg.show', ['locale' => $locale, 'slug' => $st->slug]) }}"
                   class="gcgs-sidebar-link {{ $isActive ? 'is-active' : '' }}">
                    <span>{{ $st->name }}</span>
                    <span class="gcgs-sidebar-count">{{ $cat->activeDocuments->count() }}</span>
                </a>
            @endif
        @endforeach
    </aside>

    {{-- ── Main ── --}}
    <div class="gcgs-main">

        {{-- Breadcrumb --}}
        <nav class="gcgs-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('gcg.index', ['locale' => $locale]) }}">GCG</a>
            <span class="gcgs-breadcrumb-sep">›</span>
            <span class="gcgs-breadcrumb-current">{{ $translation->name }}</span>
        </nav>

        {{-- Header --}}
        <div class="gcgs-header">
            <div class="gcgs-header-top">
                <div class="gcgs-header-title">{{ $translation->name }}</div>
                <span class="gcgs-header-badge">
                    {{ $category->activeDocuments->count() }}
                    {{ $locale === 'id' ? 'dokumen' : ($category->activeDocuments->count() === 1 ? 'document' : 'documents') }}
                </span>
            </div>

            @if($translation->description)
                <div class="gcgs-desc">{{ $translation->description }}</div>
            @endif
        </div>

        {{-- Search — tampil hanya jika dokumen > 4 --}}
        @if($category->activeDocuments->count() > 4)
        <div class="gcgs-search-wrap">
            <div class="gcgs-search-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </div>
            <input type="search"
                   class="gcgs-search"
                   id="gcgsSearch"
                   placeholder="{{ $locale === 'id' ? 'Cari dokumen...' : 'Search documents...' }}"
                   autocomplete="off">
        </div>
        @endif

        {{-- Doc list --}}
        <div class="gcgs-list" id="gcgsList">
            @forelse($category->activeDocuments as $doc)
                @php
                    $dt = $doc->translations->firstWhere('locale', $locale)
                        ?? $doc->translations->first();
                    $ext = strtolower($doc->file_type ?? pathinfo($doc->file_name ?? '', PATHINFO_EXTENSION));
                    $badgeClass = in_array($ext, ['pdf','docx','xlsx','pptx']) ? $ext : 'other';
                    $fileSize = $doc->file_size;
                    $sizeLabel = $fileSize
                        ? ($fileSize >= 1048576
                            ? number_format($fileSize / 1048576, 1) . ' MB'
                            : round($fileSize / 1024) . ' KB')
                        : null;
                    $docTitle = $dt->title ?? $dt->name ?? $doc->file_name;
                    $previewUrl = in_array($ext, ['pdf']) ? asset('documents/gcg/' . $doc->file_path) : null;
                @endphp

                <div class="gcgs-doc" data-title="{{ strtolower($docTitle) }}">

                    <div class="gcgs-doc-file-badge {{ $badgeClass }}">
                        {{ strtoupper($ext ?: '?') }}
                    </div>

                    <div class="gcgs-doc-info">
                        <div class="gcgs-doc-title">{{ $docTitle }}</div>
                        <div class="gcgs-doc-meta">
                            @if($ext)
                                <span>{{ strtoupper($ext) }}</span>
                            @endif
                            @if($sizeLabel)
                                <span class="gcgs-doc-meta-sep">·</span>
                                <span>{{ $sizeLabel }}</span>
                            @endif
                            @if($doc->file_name && $doc->file_name !== $docTitle)
                                <span class="gcgs-doc-meta-sep">·</span>
                                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;">
                                    {{ $doc->file_name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="gcgs-doc-actions">
                        @if($previewUrl)
                            <a href="{{ $previewUrl }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="gcgs-btn-preview">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                {{ $locale === 'id' ? 'Lihat' : 'Preview' }}
                            </a>
                        @endif
                        <a href="{{ route('gcg.download', $doc->id) }}"
                           class="gcgs-btn-download">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            {{ $locale === 'id' ? 'Unduh' : 'Download' }}
                        </a>
                    </div>
                </div>

            @empty
                <div class="gcgs-empty show">
                    {{ $locale === 'id' ? 'Belum ada dokumen dalam kategori ini.' : 'No documents in this category yet.' }}
                </div>
            @endforelse
        </div>

        <div class="gcgs-empty" id="gcgsEmpty">
            {{ $locale === 'id' ? 'Dokumen tidak ditemukan.' : 'No documents found.' }}
        </div>

    </div>
</div>

<script>
(function () {
    var input = document.getElementById('gcgsSearch');
    if (!input) return;

    var rows  = document.querySelectorAll('#gcgsList .gcgs-doc');
    var empty = document.getElementById('gcgsEmpty');

    input.addEventListener('input', function () {
        var q = input.value.trim().toLowerCase();
        var visible = 0;

        rows.forEach(function (row) {
            var title = row.dataset.title || '';
            var show  = !q || title.includes(q);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (empty) empty.classList.toggle('show', visible === 0 && q.length > 0);
    });
}());
</script>

@endsection