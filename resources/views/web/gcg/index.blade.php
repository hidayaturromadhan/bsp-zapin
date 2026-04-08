@extends('layouts.app')

@section('title', $locale === 'id' ? 'Good Corporate Governance' : 'Good Corporate Governance')

@section('content')

<style>
.gcg-page {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.gcg-top-band {
    background: #204712;
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    margin-top: -32px;
    padding: 36px 0 34px;
}

.gcg-top-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 28px;
}

.gcg-hero {
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 20px;
    padding: 48px 40px 44px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.gcg-hero-orb {
    position: absolute;
    top: -40px;
    right: -40px;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background: var(--g100);
    pointer-events: none;
}

.gcg-hero-orb2 {
    position: absolute;
    bottom: -30px;
    left: -30px;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: var(--g100);
    pointer-events: none;
}

.gcg-hero-accent {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 18px;
    position: relative;
}

.gcg-hero-line {
    width: 32px;
    height: 2px;
    background: #204712;
    border-radius: 2px;
    flex-shrink: 0;
}

.gcg-hero-tag {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #204712;
}

.gcg-hero-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--g900);
    margin-bottom: 12px;
    line-height: 1.2;
    position: relative;
}

.gcg-hero-desc {
    font-size: 14px;
    color: var(--text3);
    line-height: 1.75;
    max-width: 560px;
    margin: 0 auto 24px;
    position: relative;
}

.gcg-hero-desc.no-pills {
    margin-bottom: 0;
}

.gcg-hero-pills {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
    position: relative;
}

.gcg-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    background: var(--g100);
    border: 1px solid var(--g200);
    font-size: 12px;
    font-weight: 600;
    color: var(--g800);
}

.gcg-hero-pill-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #204712;
    flex-shrink: 0;
}

/* ── DOKUMEN GRID ───────────────────────────────────── */
.gcg-documents-wrap {
    max-width: 1280px;
    margin: 0 auto;
    width: 100%;
}

.gcg-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 280px));
    justify-content: center;
    gap: 28px 22px;
}

.gcg-doc-card {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.gcg-doc-cover-link {
    display: block;
    width: 100%;
    text-decoration: none;
}

.gcg-doc-cover {
    position: relative;
    width: 100%;
    aspect-ratio: 3 / 4;
    border-radius: 20px;
    overflow: hidden;
    background: var(--white);
    border: 1px solid #e6ece7;
    box-shadow:
        0 10px 30px rgba(15, 41, 6, 0.08),
        0 2px 8px rgba(15, 41, 6, 0.05);
    transition:
        transform .22s ease,
        box-shadow .22s ease,
        border-color .22s ease;
}

.gcg-doc-card:hover .gcg-doc-cover {
    transform: translateY(-4px);
    border-color: #cfe1d1;
    box-shadow:
        0 18px 38px rgba(15, 41, 6, 0.12),
        0 6px 14px rgba(15, 41, 6, 0.08);
}

.gcg-doc-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.gcg-doc-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: linear-gradient(180deg, #fbfcfb 0%, #eef5eb 100%);
    color: var(--g800);
    text-align: center;
    padding: 24px;
}

.gcg-doc-placeholder-icon {
    width: 62px;
    height: 62px;
    border-radius: 18px;
    background: #204712;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 18px rgba(32, 71, 18, .22);
}

.gcg-doc-placeholder-text {
    font-size: 13px;
    color: var(--text3);
    line-height: 1.6;
    max-width: 180px;
}

.gcg-doc-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(10, 18, 10, 0.78) 0%,
        rgba(10, 18, 10, 0.48) 45%,
        rgba(10, 18, 10, 0.12) 100%
    );
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 10px;
    padding: 18px;
    opacity: 0;
    visibility: hidden;
    transition: opacity .22s ease, visibility .22s ease;
}

.gcg-doc-card:hover .gcg-doc-overlay,
.gcg-doc-card:focus-within .gcg-doc-overlay {
    opacity: 1;
    visibility: visible;
}

.gcg-doc-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 104px;
    height: 42px;
    padding: 0 16px;
    border-radius: 11px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition:
        transform .15s ease,
        background .15s ease,
        border-color .15s ease,
        color .15s ease,
        box-shadow .15s ease;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

.gcg-doc-action:hover {
    transform: translateY(-1px);
}

.gcg-doc-action--view {
    background: rgba(255,255,255,.95);
    color: #204712;
    box-shadow: 0 4px 12px rgba(0,0,0,.12);
}

.gcg-doc-action--download {
    background: #204712;
    color: #fff;
    border: 1px solid rgba(255,255,255,.16);
    box-shadow: 0 6px 16px rgba(32, 71, 18, .28);
}

.gcg-doc-title {
    width: 100%;
    margin-top: 14px;
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    line-height: 1.55;
    text-align: center;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 44px;
}

.gcg-empty {
    width: 100%;
    max-width: 720px;
    margin: 0 auto;
    text-align: center;
    padding: 54px 28px;
    color: var(--text3);
    font-size: 14px;
    border: 1px dashed var(--line);
    border-radius: 18px;
    background: var(--white);
}

@media (max-width: 1060px) {
    .gcg-top-inner,
    .gcg-documents-wrap {
        padding-left: 18px;
        padding-right: 18px;
    }
}

@media (max-width: 680px) {
    .gcg-top-band {
        margin-top: -24px;
        padding: 24px 0 24px;
    }

    .gcg-top-inner,
    .gcg-documents-wrap {
        padding-left: 16px;
        padding-right: 16px;
    }

    .gcg-hero {
        padding: 36px 24px 32px;
        border-radius: 18px;
    }

    .gcg-hero-title {
        font-size: 22px;
    }

    .gcg-grid {
        grid-template-columns: minmax(0, 320px);
        gap: 22px;
    }

    .gcg-doc-overlay {
        opacity: 1;
        visibility: visible;
        align-items: flex-end;
        padding: 16px;
    }

    .gcg-doc-action {
        min-width: 96px;
        height: 40px;
    }

    .gcg-doc-title {
        font-size: 13.5px;
        min-height: unset;
    }
}
</style>

<div class="gcg-page">

    <div class="gcg-top-band">
        <div class="gcg-top-inner">
            <div class="gcg-hero">
                <div class="gcg-hero-orb"></div>
                <div class="gcg-hero-orb2"></div>

                <div class="gcg-hero-accent">
                    <div class="gcg-hero-line"></div>
                    <span class="gcg-hero-tag">PT BUMI SIAK PUSAKO ZAPIN</span>
                    <div class="gcg-hero-line"></div>
                </div>

                <div class="gcg-hero-title">Good Corporate Governance</div>

                <div class="gcg-hero-desc {{ isset($highlightItems) && $highlightItems->count() ? '' : 'no-pills' }}">
                    {{ $locale === 'id'
                        ? 'Pedoman tata kelola, dokumen kebijakan, dan regulasi perusahaan yang transparan dan akuntabel.'
                        : 'Corporate governance guidelines, policy documents, and regulations — transparent and accountable.' }}
                </div>

                @if(isset($highlightItems) && $highlightItems->count())
                    <div class="gcg-hero-pills">
                        @foreach($highlightItems as $item)
                            @php
                                $highlightLabel = $locale === 'en'
                                    ? (!empty($item->label_en) ? $item->label_en : $item->label_id)
                                    : $item->label_id;
                            @endphp
                            <span class="gcg-hero-pill">
                                <span class="gcg-hero-pill-dot"></span>
                                {{ $highlightLabel }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="gcg-documents-wrap">
        <div class="gcg-grid">
            @forelse($documents as $doc)
                @php
                    $docTranslation = $doc->translations->firstWhere('locale', $locale) ?? $doc->translations->first();
                    $docTitle = $docTranslation->title ?? $doc->file_name ?? 'Document';
                @endphp

                <div class="gcg-doc-card">
                    <div class="gcg-doc-cover">
                        @if(!empty($doc->cover))
                            <img src="{{ asset('images/gcg/' . $doc->cover) }}" alt="{{ $docTitle }}">
                        @else
                            <div class="gcg-doc-placeholder">
                                <div class="gcg-doc-placeholder-icon">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9z"/>
                                        <polyline points="14 2 14 9 21 9"/>
                                    </svg>
                                </div>
                                <div class="gcg-doc-placeholder-text">
                                    {{ $locale === 'id'
                                        ? 'Cover dokumen belum tersedia.'
                                        : 'Document cover is not available yet.' }}
                                </div>
                            </div>
                        @endif

                        <div class="gcg-doc-overlay">
                            <a href="{{ asset('documents/gcg/' . $doc->file_path) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="gcg-doc-action gcg-doc-action--view">
                                {{ $locale === 'id' ? 'Lihat' : 'View' }}
                            </a>

                            <a href="{{ route('gcg.download', $doc) }}"
                               class="gcg-doc-action gcg-doc-action--download">
                                {{ $locale === 'id' ? 'Download' : 'Download' }}
                            </a>
                        </div>
                    </div>

                    <div class="gcg-doc-title">{{ $docTitle }}</div>
                </div>
            @empty
                <div class="gcg-empty">
                    {{ $locale === 'id' ? 'Belum ada dokumen GCG.' : 'No GCG documents yet.' }}
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection