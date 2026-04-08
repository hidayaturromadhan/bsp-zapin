@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');

    $newsCollection = $news->getCollection();

    $featuredMain = $newsCollection->get(0);
    $featuredSide = $newsCollection->slice(1, 2);
    $latestNews = $newsCollection->slice(3);
@endphp

<style>
    .media-shell {
        max-width: 1180px;
        margin: 0 auto;
    }

    .media-head {
        margin-bottom: 30px;
    }

    .media-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #2f7d32;
        margin-bottom: 10px;
    }

    .media-eyebrow::before {
        content: '';
        width: 28px;
        height: 2px;
        border-radius: 999px;
        background: #2f7d32;
    }

    .media-title {
        margin: 0 0 10px;
        font-size: clamp(30px, 4vw, 42px);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #111827;
    }

    .media-desc {
        margin: 0;
        max-width: 780px;
        font-size: 15px;
        line-height: 1.8;
        color: #4b5563;
    }

    .media-section {
        margin-top: 34px;
    }

    .media-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .media-section-title {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.02em;
    }

    .media-section-sub {
        margin: 4px 0 0;
        font-size: 13px;
        color: #6b7280;
    }

    /* ─────────────────────────────
       FEATURED
    ───────────────────────────── */
    .media-featured {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(320px, .9fr);
        gap: 20px;
    }

    .media-featured-main,
    .media-featured-side-card,
    .media-latest-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
        overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .media-featured-main:hover,
    .media-featured-side-card:hover,
    .media-latest-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 34px rgba(15, 23, 42, .08);
        border-color: #d8e4d4;
    }

    .media-featured-main-thumb-wrap {
        display: block;
        text-decoration: none;
        background: #eef5eb;
    }

    .media-featured-main-thumb {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
        background: #eef5eb;
    }

    .media-featured-main-body {
        padding: 22px 22px 20px;
    }

    .media-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 12px;
        color: #6b7280;
    }

    .media-category {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        background: #eef5eb;
        color: #21560e;
        font-weight: 700;
        font-size: 11px;
        letter-spacing: .02em;
    }

    .media-featured-main-title {
        margin: 0 0 12px;
        font-size: clamp(24px, 3vw, 32px);
        line-height: 1.28;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.02em;
    }

    .media-link {
        color: inherit;
        text-decoration: none;
    }

    .media-link:hover .media-featured-main-title,
    .media-link:hover .media-featured-side-title,
    .media-link:hover .media-latest-title {
        color: #173f08;
    }

    .media-featured-main-excerpt {
        margin: 0;
        font-size: 14px;
        line-height: 1.85;
        color: #4b5563;
    }

    .media-featured-side {
        display: grid;
        gap: 18px;
    }

    .media-featured-side-card {
        display: grid;
        grid-template-columns: 130px minmax(0, 1fr);
        gap: 14px;
        padding: 14px;
        align-items: stretch;
    }

    .media-featured-side-thumb {
        width: 100%;
        height: 100%;
        min-height: 112px;
        border-radius: 14px;
        object-fit: cover;
        display: block;
        background: #eef5eb;
    }

    .media-featured-side-body {
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .media-featured-side-title {
        margin: 0 0 8px;
        font-size: 17px;
        line-height: 1.45;
        font-weight: 800;
        color: #111827;
        transition: color .14s ease;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .media-featured-side-excerpt {
        margin: 0;
        font-size: 13px;
        line-height: 1.7;
        color: #6b7280;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ─────────────────────────────
       LATEST GRID
    ───────────────────────────── */
    .media-latest-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .media-latest-thumb-wrap {
        display: block;
        text-decoration: none;
        background: #eef5eb;
    }

    .media-latest-thumb {
        width: 100%;
        aspect-ratio: 16 / 10;
        object-fit: cover;
        display: block;
        background: #eef5eb;
    }

    .media-latest-body {
        padding: 16px 16px 18px;
    }

    .media-latest-title {
        margin: 0 0 10px;
        font-size: 18px;
        line-height: 1.45;
        font-weight: 800;
        color: #111827;
        transition: color .14s ease;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 78px;
    }

    .media-latest-excerpt {
        margin: 0;
        font-size: 13.5px;
        line-height: 1.8;
        color: #4b5563;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ─────────────────────────────
       EMPTY
    ───────────────────────────── */
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

    .media-pagination {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 1024px) {
        .media-featured {
            grid-template-columns: 1fr;
        }

        .media-latest-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .media-head {
            margin-bottom: 22px;
        }

        .media-title {
            font-size: 28px;
        }

        .media-desc {
            font-size: 14px;
        }

        .media-featured-side-card {
            grid-template-columns: 1fr;
        }

        .media-featured-side-thumb {
            min-height: 190px;
        }

        .media-latest-grid {
            grid-template-columns: 1fr;
        }

        .media-featured-main-body {
            padding: 18px;
        }

        .media-latest-body {
            padding: 15px 15px 16px;
        }

        .media-featured-main-title {
            font-size: 22px;
        }

        .media-latest-title {
            font-size: 17px;
            min-height: unset;
        }
    }
</style>

<div class="media-shell">
    <header class="media-head">
        <div class="media-eyebrow">
            {{ $locale === 'id' ? 'Informasi' : 'Information' }}
        </div>

        <h1 class="media-title">
            {{ $locale === 'id' ? 'Media & Publikasi' : 'Media & Publications' }}
        </h1>

        <p class="media-desc">
            {{ $locale === 'id'
                ? 'Temukan berita terbaru, publikasi resmi, dan berbagai informasi perusahaan yang telah dipublikasikan untuk masyarakat dan para pemangku kepentingan.'
                : 'Discover the latest news, official publications, and various company updates published for the public and stakeholders.' }}
        </p>
    </header>

    @if($newsCollection->count())

        {{-- BERITA UNGGULAN --}}
        @if($featuredMain)
            <section class="media-section">
                <div class="media-section-head">
                    <div>
                        <h2 class="media-section-title">
                            {{ $locale === 'id' ? 'Berita Unggulan' : 'Featured News' }}
                        </h2>
                        <p class="media-section-sub">
                            {{ $locale === 'id'
                                ? 'Sorotan utama dan publikasi pilihan terbaru.'
                                : 'Top highlights and selected recent publications.' }}
                        </p>
                    </div>
                </div>

                <div class="media-featured">
                    @php
                        $mainTranslation = method_exists($featuredMain, 'getTranslationByLocale')
                            ? $featuredMain->getTranslationByLocale($locale)
                            : $featuredMain->translations->firstWhere('locale', $locale) ?? $featuredMain->translations->firstWhere('locale', 'id');
                    @endphp

                    <article class="media-featured-main">
                        @if($featuredMain->featured_image)
                            @if($mainTranslation && $mainTranslation->slug)
                                <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $mainTranslation->slug]) }}" class="media-featured-main-thumb-wrap">
                                    <img
                                        src="{{ asset($featuredMain->featured_image) }}"
                                        alt="{{ $mainTranslation->title ?? 'News image' }}"
                                        class="media-featured-main-thumb"
                                        onerror="this.style.display='none';"
                                    >
                                </a>
                            @else
                                <div class="media-featured-main-thumb-wrap">
                                    <img
                                        src="{{ asset($featuredMain->featured_image) }}"
                                        alt="News image"
                                        class="media-featured-main-thumb"
                                        onerror="this.style.display='none';"
                                    >
                                </div>
                            @endif
                        @endif

                        <div class="media-featured-main-body">
                            <div class="media-meta">
                                @if($featuredMain->category?->name)
                                    <span class="media-category">{{ $featuredMain->category->name }}</span>
                                @endif
                                <span>{{ optional($featuredMain->published_at)->format('d M Y') }}</span>
                            </div>

                            @if($mainTranslation)
                                @if($mainTranslation->slug)
                                    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $mainTranslation->slug]) }}" class="media-link">
                                        <h3 class="media-featured-main-title">{{ $mainTranslation->title }}</h3>
                                    </a>
                                @else
                                    <h3 class="media-featured-main-title">{{ $mainTranslation->title }}</h3>
                                @endif

                                @if($mainTranslation->excerpt)
                                    <p class="media-featured-main-excerpt">{{ $mainTranslation->excerpt }}</p>
                                @endif
                            @endif
                        </div>
                    </article>

                    <div class="media-featured-side">
                        @foreach($featuredSide as $n)
                            @php
                                $t = method_exists($n, 'getTranslationByLocale')
                                    ? $n->getTranslationByLocale($locale)
                                    : $n->translations->firstWhere('locale', $locale) ?? $n->translations->firstWhere('locale', 'id');
                            @endphp

                            <article class="media-featured-side-card">
                                <div>
                                    @if($n->featured_image)
                                        @if($t && $t->slug)
                                            <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}">
                                                <img
                                                    src="{{ asset($n->featured_image) }}"
                                                    alt="{{ $t->title ?? 'News image' }}"
                                                    class="media-featured-side-thumb"
                                                    onerror="this.style.display='none';"
                                                >
                                            </a>
                                        @else
                                            <img
                                                src="{{ asset($n->featured_image) }}"
                                                alt="News image"
                                                class="media-featured-side-thumb"
                                                onerror="this.style.display='none';"
                                            >
                                        @endif
                                    @endif
                                </div>

                                <div class="media-featured-side-body">
                                    <div class="media-meta" style="margin-bottom:8px;">
                                        @if($n->category?->name)
                                            <span class="media-category">{{ $n->category->name }}</span>
                                        @endif
                                        <span>{{ optional($n->published_at)->format('d M Y') }}</span>
                                    </div>

                                    @if($t)
                                        @if($t->slug)
                                            <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}" class="media-link">
                                                <h3 class="media-featured-side-title">{{ $t->title }}</h3>
                                            </a>
                                        @else
                                            <h3 class="media-featured-side-title">{{ $t->title }}</h3>
                                        @endif

                                        @if($t->excerpt)
                                            <p class="media-featured-side-excerpt">{{ $t->excerpt }}</p>
                                        @endif
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- BERITA TERBARU --}}
        <section class="media-section">
            <div class="media-section-head">
                <div>
                    <h2 class="media-section-title">
                        {{ $locale === 'id' ? 'Berita Terbaru' : 'Latest News' }}
                    </h2>
                    <p class="media-section-sub">
                        {{ $locale === 'id'
                            ? 'Update terbaru dari perusahaan dan publikasi resmi.'
                            : 'The latest company updates and official publications.' }}
                    </p>
                </div>
            </div>

            @if($latestNews->count())
                <div class="media-latest-grid">
                    @foreach($latestNews as $n)
                        @php
                            $t = method_exists($n, 'getTranslationByLocale')
                                ? $n->getTranslationByLocale($locale)
                                : $n->translations->firstWhere('locale', $locale) ?? $n->translations->firstWhere('locale', 'id');
                        @endphp

                        <article class="media-latest-card">
                            @if($n->featured_image)
                                @if($t && $t->slug)
                                    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}" class="media-latest-thumb-wrap">
                                        <img
                                            src="{{ asset($n->featured_image) }}"
                                            alt="{{ $t->title ?? 'News image' }}"
                                            class="media-latest-thumb"
                                            onerror="this.style.display='none';"
                                        >
                                    </a>
                                @else
                                    <div class="media-latest-thumb-wrap">
                                        <img
                                            src="{{ asset($n->featured_image) }}"
                                            alt="News image"
                                            class="media-latest-thumb"
                                            onerror="this.style.display='none';"
                                        >
                                    </div>
                                @endif
                            @endif

                            <div class="media-latest-body">
                                <div class="media-meta">
                                    @if($n->category?->name)
                                        <span class="media-category">{{ $n->category->name }}</span>
                                    @endif
                                    <span>{{ optional($n->published_at)->format('d M Y') }}</span>
                                </div>

                                @if($t)
                                    @if($t->slug)
                                        <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}" class="media-link">
                                            <h3 class="media-latest-title">{{ $t->title }}</h3>
                                        </a>
                                    @else
                                        <h3 class="media-latest-title">{{ $t->title }}</h3>
                                    @endif

                                    @if($t->excerpt)
                                        <p class="media-latest-excerpt">{{ $t->excerpt }}</p>
                                    @endif
                                @else
                                    <h3 class="media-latest-title">
                                        {{ $locale === 'id' ? 'Terjemahan belum tersedia' : 'Translation not available yet' }}
                                    </h3>
                                    <p class="media-latest-excerpt">
                                        {{ $locale === 'id'
                                            ? 'Konten untuk bahasa ini belum tersedia.'
                                            : 'Content for this language is not available yet.' }}
                                    </p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <section class="media-empty">
                    <div class="media-empty-icon">📰</div>
                    <h3 class="media-empty-title">
                        {{ $locale === 'id' ? 'Belum ada berita terbaru' : 'No latest news yet' }}
                    </h3>
                    <p class="media-empty-text">
                        {{ $locale === 'id'
                            ? 'Saat ini belum ada berita tambahan pada halaman ini.'
                            : 'There are no additional news items on this page yet.' }}
                    </p>
                </section>
            @endif
        </section>

        <div class="media-pagination">
            {{ $news->links() }}
        </div>
    @else
        <section class="media-empty">
            <div class="media-empty-icon">📰</div>
            <h2 class="media-empty-title">
                {{ $locale === 'id' ? 'Belum ada publikasi' : 'No publications yet' }}
            </h2>
            <p class="media-empty-text">
                {{ $locale === 'id'
                    ? 'Belum ada berita atau publikasi yang tersedia saat ini.'
                    : 'There are no news items or publications available at the moment.' }}
            </p>
        </section>
    @endif
</div>
@endsection