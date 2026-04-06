@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');
@endphp

<style>
    .media-shell {
        max-width: 1080px;
        margin: 0 auto;
    }

    .media-head {
        margin-bottom: 28px;
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
        max-width: 760px;
        font-size: 15px;
        line-height: 1.8;
        color: #4b5563;
    }

    .media-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .media-card {
        display: flex;
        gap: 18px;
        align-items: stretch;
        padding: 18px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .04);
        transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
    }

    .media-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, .08);
        border-color: #d8e4d4;
    }

    .media-thumb-wrap {
        width: 240px;
        flex-shrink: 0;
    }

    .media-thumb {
        width: 100%;
        height: 148px;
        object-fit: cover;
        display: block;
        border-radius: 14px;
        background: #eef5eb;
    }

    .media-body {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .media-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
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

    .media-link {
        display: inline-block;
        color: #111827;
        text-decoration: none;
    }

    .media-link:hover .media-card-title {
        color: #173f08;
    }

    .media-card-title {
        margin: 0 0 10px;
        font-size: 24px;
        line-height: 1.35;
        font-weight: 800;
        color: #111827;
        transition: color .14s ease;
        word-break: break-word;
    }

    .media-excerpt {
        margin: 0;
        font-size: 14px;
        line-height: 1.8;
        color: #4b5563;
    }

    .media-empty {
        padding: 40px 22px;
        text-align: center;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
    }

    .media-empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 16px;
        border-radius: 16px;
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
        margin-top: 26px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 860px) {
        .media-card {
            flex-direction: column;
        }

        .media-thumb-wrap {
            width: 100%;
        }

        .media-thumb {
            height: 220px;
        }

        .media-card-title {
            font-size: 22px;
        }
    }

    @media (max-width: 640px) {
        .media-head {
            margin-bottom: 22px;
        }

        .media-title {
            font-size: 28px;
        }

        .media-desc {
            font-size: 14px;
        }

        .media-card {
            padding: 14px;
            border-radius: 16px;
        }

        .media-thumb {
            height: 190px;
            border-radius: 12px;
        }

        .media-card-title {
            font-size: 20px;
        }

        .media-excerpt {
            font-size: 13.5px;
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

    @if($news->count())
        <section class="media-list">
            @foreach($news as $n)
                @php
                    $t = method_exists($n, 'getTranslationByLocale')
                        ? $n->getTranslationByLocale($locale)
                        : $n->translations->firstWhere('locale', $locale) ?? $n->translations->firstWhere('locale', 'id');
                @endphp

                <article class="media-card">
                    @if($n->featured_image)
                        <div class="media-thumb-wrap">
                            @if($t && $t->slug)
                                <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}">
                                    <img
                                        src="{{ asset($n->featured_image) }}"
                                        alt="{{ $t->title ?? 'News image' }}"
                                        class="media-thumb"
                                        onerror="this.style.display='none';"
                                    >
                                </a>
                            @else
                                <img
                                    src="{{ asset($n->featured_image) }}"
                                    alt="News image"
                                    class="media-thumb"
                                    onerror="this.style.display='none';"
                                >
                            @endif
                        </div>
                    @endif

                    <div class="media-body">
                        <div class="media-meta">
                            @if($n->category?->name)
                                <span class="media-category">{{ $n->category->name }}</span>
                            @endif

                            <span>{{ optional($n->published_at)->format('d M Y') }}</span>
                        </div>

                        @if($t)
                            @if($t->slug)
                                <a
                                    href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}"
                                    class="media-link"
                                >
                                    <h2 class="media-card-title">{{ $t->title }}</h2>
                                </a>
                            @else
                                <h2 class="media-card-title">{{ $t->title }}</h2>
                            @endif

                            @if($t->excerpt)
                                <p class="media-excerpt">{{ $t->excerpt }}</p>
                            @endif
                        @else
                            <h2 class="media-card-title" style="font-size:18px; margin-bottom:6px;">
                                {{ $locale === 'id' ? 'Terjemahan belum tersedia' : 'Translation not available yet' }}
                            </h2>
                            <p class="media-excerpt">
                                {{ $locale === 'id'
                                    ? 'Konten untuk bahasa ini belum tersedia.'
                                    : 'Content for this language is not available yet.' }}
                            </p>
                        @endif
                    </div>
                </article>
            @endforeach
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