@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');
@endphp

<style>
    .tjsl-shell {
        max-width: 1080px;
        margin: 0 auto;
    }

    .tjsl-head {
        margin-bottom: 28px;
    }

    .tjsl-eyebrow {
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

    .tjsl-eyebrow::before {
        content: '';
        width: 28px;
        height: 2px;
        border-radius: 999px;
        background: #2f7d32;
    }

    .tjsl-title {
        margin: 0 0 10px;
        font-size: clamp(30px, 4vw, 42px);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #111827;
    }

    .tjsl-desc {
        margin: 0;
        max-width: 760px;
        font-size: 15px;
        line-height: 1.8;
        color: #4b5563;
    }

    .tjsl-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .tjsl-card {
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

    .tjsl-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, .08);
        border-color: #d8e4d4;
    }

    .tjsl-thumb-wrap {
        width: 240px;
        flex-shrink: 0;
    }

    .tjsl-thumb {
        width: 100%;
        height: 148px;
        object-fit: cover;
        display: block;
        border-radius: 14px;
        background: #eef5eb;
    }

    .tjsl-body {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .tjsl-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 12px;
        color: #6b7280;
    }

    .tjsl-category {
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

    .tjsl-link {
        display: inline-block;
        color: #111827;
        text-decoration: none;
    }

    .tjsl-link:hover .tjsl-card-title {
        color: #173f08;
    }

    .tjsl-card-title {
        margin: 0 0 10px;
        font-size: 24px;
        line-height: 1.35;
        font-weight: 800;
        color: #111827;
        transition: color .14s ease;
        word-break: break-word;
    }

    .tjsl-excerpt {
        margin: 0;
        font-size: 14px;
        line-height: 1.8;
        color: #4b5563;
    }

    .tjsl-empty {
        padding: 40px 22px;
        text-align: center;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
    }

    .tjsl-empty-icon {
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

    .tjsl-empty-title {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 800;
        color: #111827;
    }

    .tjsl-empty-text {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.7;
    }

    .tjsl-pagination {
        margin-top: 26px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 860px) {
        .tjsl-card {
            flex-direction: column;
        }

        .tjsl-thumb-wrap {
            width: 100%;
        }

        .tjsl-thumb {
            height: 220px;
        }

        .tjsl-card-title {
            font-size: 22px;
        }
    }

    @media (max-width: 640px) {
        .tjsl-head {
            margin-bottom: 22px;
        }

        .tjsl-title {
            font-size: 28px;
        }

        .tjsl-desc {
            font-size: 14px;
        }

        .tjsl-card {
            padding: 14px;
            border-radius: 16px;
        }

        .tjsl-thumb {
            height: 190px;
            border-radius: 12px;
        }

        .tjsl-card-title {
            font-size: 20px;
        }

        .tjsl-excerpt {
            font-size: 13.5px;
        }
    }
</style>

<div class="tjsl-shell">
    <header class="tjsl-head">
        <div class="tjsl-eyebrow">
            {{ $locale === 'id' ? 'Program Sosial' : 'Social Program' }}
        </div>

        <h1 class="tjsl-title">TJSL</h1>

        <p class="tjsl-desc">
            {{ $locale === 'id'
                ? 'Informasi kegiatan Tanggung Jawab Sosial dan Lingkungan perusahaan yang mencerminkan komitmen terhadap masyarakat, lingkungan, dan pembangunan berkelanjutan.'
                : 'Information on the company’s Social and Environmental Responsibility programs reflecting our commitment to communities, the environment, and sustainable development.' }}
        </p>
    </header>

    @if($news->count())
        <section class="tjsl-list">
            @foreach($news as $n)
                @php
                    $t = method_exists($n, 'getTranslationByLocale')
                        ? $n->getTranslationByLocale($locale)
                        : $n->translations->firstWhere('locale', $locale) ?? $n->translations->firstWhere('locale', 'id');
                @endphp

                <article class="tjsl-card">
                    @if($n->featured_image)
                        <div class="tjsl-thumb-wrap">
                            @if($t && $t->slug)
                                <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}">
                                    <img
                                        src="{{ asset($n->featured_image) }}"
                                        alt="{{ $t->title ?? 'TJSL image' }}"
                                        class="tjsl-thumb"
                                        onerror="this.style.display='none';"
                                    >
                                </a>
                            @else
                                <img
                                    src="{{ asset($n->featured_image) }}"
                                    alt="TJSL image"
                                    class="tjsl-thumb"
                                    onerror="this.style.display='none';"
                                >
                            @endif
                        </div>
                    @endif

                    <div class="tjsl-body">
                        <div class="tjsl-meta">
                            <span class="tjsl-category">TJSL</span>
                            <span>{{ optional($n->published_at)->format('d M Y') }}</span>
                        </div>

                        @if($t)
                            @if($t->slug)
                                <a
                                    href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}"
                                    class="tjsl-link"
                                >
                                    <h2 class="tjsl-card-title">{{ $t->title }}</h2>
                                </a>
                            @else
                                <h2 class="tjsl-card-title">{{ $t->title }}</h2>
                            @endif

                            @if($t->excerpt)
                                <p class="tjsl-excerpt">{{ $t->excerpt }}</p>
                            @endif
                        @else
                            <h2 class="tjsl-card-title" style="font-size:18px; margin-bottom:6px;">
                                {{ $locale === 'id' ? 'Terjemahan belum tersedia' : 'Translation not available yet' }}
                            </h2>
                            <p class="tjsl-excerpt">
                                {{ $locale === 'id'
                                    ? 'Konten untuk bahasa ini belum tersedia.'
                                    : 'Content for this language is not available yet.' }}
                            </p>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>

        <div class="tjsl-pagination">
            {{ $news->links() }}
        </div>
    @else
        <section class="tjsl-empty">
            <div class="tjsl-empty-icon">🌿</div>
            <h2 class="tjsl-empty-title">
                {{ $locale === 'id' ? 'Belum ada program TJSL' : 'No TJSL program updates yet' }}
            </h2>
            <p class="tjsl-empty-text">
                {{ $locale === 'id'
                    ? 'Belum ada berita atau publikasi TJSL yang tersedia saat ini.'
                    : 'There are no TJSL news items or publications available at the moment.' }}
            </p>
        </section>
    @endif
</div>
@endsection