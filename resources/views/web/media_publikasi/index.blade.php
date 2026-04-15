@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');
@endphp

<style>
.media-shell {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 16px;
}

.media-head {
    margin-bottom: 22px;
}

.media-title-main {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.2;
    color: #111827;
    margin: 0 0 8px;
}

.media-desc-main {
    font-size: 14px;
    line-height: 1.8;
    color: #6b7280;
    margin: 0;
    max-width: 760px;
}

.media-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 30px;
    align-items: start;
}

.media-main {
    min-width: 0;
}

.media-sidebar {
    position: sticky;
    top: 20px;
}

/* =========================
   GRID BERITA
========================= */
.media-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px;
}

/* item terakhir center kalau jumlah ganjil */
.media-grid .media-card:last-child:nth-child(odd) {
    grid-column: 1 / -1;
    max-width: 520px;
    width: 100%;
    margin: 0 auto;
}

.media-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.media-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,.08);
    border-color: #d7e2d1;
}

.media-card-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.media-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    background: #eef2f7;
}

.media-card-body {
    padding: 16px;
}

.media-meta {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.media-category {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    background: #eef5eb;
    color: #21560e;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .02em;
}

.media-title {
    font-size: 18px;
    font-weight: 800;
    line-height: 1.5;
    color: #111827;
    margin: 0 0 8px;
    transition: color .18s ease;
}

.media-card-link:hover .media-title {
    color: #173f08;
}

.media-excerpt {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.8;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* =========================
   SIDEBAR
========================= */
.sidebar-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e5e7eb;
    padding: 18px;
    margin-bottom: 18px;
    box-shadow: 0 8px 18px rgba(15, 23, 42, .04);
}

.sidebar-title {
    font-size: 18px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 14px;
}

.sidebar-input,
.sidebar-select {
    width: 100%;
    height: 44px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    padding: 0 12px;
    margin-bottom: 10px;
    box-sizing: border-box;
    font: inherit;
    color: #111827;
    background: #fff;
}

.sidebar-btn,
.sidebar-reset {
    width: 100%;
    height: 44px;
    border-radius: 10px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
}

.sidebar-btn {
    background: #173f08;
    color: #fff;
    margin-bottom: 10px;
}

.sidebar-reset {
    background: #fff;
    color: #111827;
    border: 1px solid #d1d5db;
}

.recent-item {
    display: flex;
    gap: 12px;
    margin-bottom: 14px;
    text-decoration: none;
    color: inherit;
}

.recent-item:last-child {
    margin-bottom: 0;
}

.recent-item img,
.recent-thumb-empty {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    display: block;
    background: #eef2f7;
}

.recent-thumb-empty {
    border: 1px solid #e5e7eb;
}

.recent-title {
    font-size: 14px;
    font-weight: 700;
    line-height: 1.55;
    color: #111827;
    margin-bottom: 4px;
    transition: color .18s ease;
}

.recent-item:hover .recent-title {
    color: #173f08;
}

.recent-date {
    font-size: 12px;
    color: #6b7280;
}

/* =========================
   EMPTY
========================= */
.media-empty {
    padding: 44px 24px;
    text-align: center;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
}

.media-empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 16px;
    border-radius: 18px;
    background: #eef5eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.media-empty-title {
    margin: 0 0 8px;
    font-size: 22px;
    font-weight: 800;
    color: #111827;
}

.media-empty-text {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
    line-height: 1.7;
}

/* =========================
   PAGINATION
========================= */
.media-pagination {
    margin-top: 30px;
}

.media-pagination-nav {
    display: flex;
    flex-direction: column;
    gap: 14px;
    align-items: center;
}

.media-pagination-summary {
    font-size: 13px;
    color: #6b7280;
    text-align: center;
}

.media-pagination-summary span {
    font-weight: 700;
    color: #111827;
}

.media-pagination-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.media-page-btn,
.media-page-dots {
    min-width: 40px;
    height: 40px;
    padding: 0 14px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    background: #fff;
    color: #111827;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
}

.media-page-btn:hover {
    border-color: #173f08;
    color: #173f08;
}

.media-page-btn--active {
    background: #173f08;
    border-color: #173f08;
    color: #fff;
}

.media-page-btn--disabled {
    opacity: .45;
    cursor: not-allowed;
}

.media-page-dots {
    border-style: dashed;
    color: #6b7280;
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 1024px) {
    .media-layout {
        grid-template-columns: 1fr;
    }

    .media-sidebar {
        position: static;
    }
}

@media (max-width: 700px) {
    .media-grid {
        grid-template-columns: 1fr;
    }

    .media-grid .media-card:last-child:nth-child(odd) {
        grid-column: auto;
        max-width: none;
        margin: 0;
    }

    .media-card img {
        height: 210px;
    }
}
</style>

<div class="media-shell">

    <header class="media-head">
        <h1 class="media-title-main">
            {{ $locale === 'id' ? 'Media & Publikasi' : 'Media & Publications' }}
        </h1>

        <p class="media-desc-main">
            {{ $locale === 'id'
                ? 'Temukan berita terbaru, publikasi resmi, dan berbagai informasi perusahaan yang telah dipublikasikan untuk masyarakat dan para pemangku kepentingan.'
                : 'Discover the latest news, official publications, and various company updates published for the public and stakeholders.' }}
        </p>
    </header>

    <div class="media-layout">

        <div class="media-main">
            @if($news->count())
                <div class="media-grid">
                    @foreach($news as $n)
                        @php
                            $t = method_exists($n, 'getTranslationByLocale')
                                ? $n->getTranslationByLocale($locale)
                                : ($n->translations->firstWhere('locale', $locale) ?? $n->translations->firstWhere('locale', 'id'));
                        @endphp

                        @if($t && !empty($t->slug))
                            <article class="media-card">
                                <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}" class="media-card-link">
                                    @if($n->featured_image)
                                        <img src="{{ asset($n->featured_image) }}" alt="{{ $t->title }}">
                                    @endif

                                    <div class="media-card-body">
                                        <div class="media-meta">
                                            @if($n->category?->name)
                                                <span class="media-category">{{ $n->category->name }}</span>
                                            @endif

                                            <span>{{ optional($n->published_at)->format('d M Y') }}</span>
                                        </div>

                                        <h3 class="media-title">{{ $t->title }}</h3>

                                        @if(!empty($t->excerpt))
                                            <p class="media-excerpt">{{ $t->excerpt }}</p>
                                        @endif
                                    </div>
                                </a>
                            </article>
                        @endif
                    @endforeach
                </div>

                <div class="media-pagination">
                    {{ $news->links('vendor.pagination.media') }}
                </div>
            @else
                <section class="media-empty">
                    <div class="media-empty-icon">📰</div>
                    <h2 class="media-empty-title">
                        {{ $locale === 'id' ? 'Belum ada berita' : 'No news yet' }}
                    </h2>
                    <p class="media-empty-text">
                        {{ $locale === 'id'
                            ? 'Belum ada berita atau hasil filter belum menemukan data.'
                            : 'There are no news items available or your filters returned no data.' }}
                    </p>
                </section>
            @endif
        </div>

        <aside class="media-sidebar">
            <div class="sidebar-card">
                <div class="sidebar-title">
                    {{ $locale === 'id' ? 'Pencarian & Filter' : 'Search & Filter' }}
                </div>

                <form method="GET" action="{{ route('media_publikasi.index', ['locale' => $locale]) }}">
                    <input
                        name="q"
                        value="{{ $q }}"
                        class="sidebar-input"
                        placeholder="{{ $locale === 'id' ? 'Cari berita...' : 'Search...' }}"
                    >

                    <select name="year" class="sidebar-select">
                        <option value="">{{ $locale === 'id' ? 'Semua Tahun' : 'All Year' }}</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ (string) $year === (string) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>

                    <select name="sort" class="sidebar-select">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>
                            {{ $locale === 'id' ? 'Terbaru' : 'Latest' }}
                        </option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>
                            {{ $locale === 'id' ? 'Terlama' : 'Oldest' }}
                        </option>
                    </select>

                    <button type="submit" class="sidebar-btn">
                        {{ $locale === 'id' ? 'Terapkan Filter' : 'Apply Filter' }}
                    </button>

                    <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="sidebar-reset">
                        {{ $locale === 'id' ? 'Reset' : 'Reset' }}
                    </a>
                </form>
            </div>

            <div class="sidebar-card">
                <div class="sidebar-title">
                    {{ $locale === 'id' ? 'Posts' : 'Posts' }}
                </div>

                @foreach($recentPosts as $p)
                    @php
                        $t = method_exists($p, 'getTranslationByLocale')
                            ? $p->getTranslationByLocale($locale)
                            : ($p->translations->firstWhere('locale', $locale) ?? $p->translations->firstWhere('locale', 'id'));
                    @endphp

                    @if($t && !empty($t->slug))
                        <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}" class="recent-item">
                            @if($p->featured_image)
                                <img src="{{ asset($p->featured_image) }}" alt="{{ $t->title }}">
                            @else
                                <div class="recent-thumb-empty"></div>
                            @endif

                            <div>
                                <div class="recent-title">{{ $t->title }}</div>
                                <div class="recent-date">
                                    {{ optional($p->published_at)->format('d M Y') }}
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </aside>

    </div>
</div>

@endsection