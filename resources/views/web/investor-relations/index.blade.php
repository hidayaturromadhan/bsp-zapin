@extends('layouts.app')

@section('title', $locale === 'id' ? 'Hubungan Investor' : 'Investor Relations')

@section('content')

<style>
.n-main {
    padding-left: 0 !important;
    padding-right: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

.ir-page {
    display: flex;
    flex-direction: column;
}

.ir-band {
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    background: #163b0d;
    background-image:
        radial-gradient(ellipse 60% 80% at 15% 50%, rgba(47,125,50,.34) 0%, transparent 65%),
        radial-gradient(ellipse 40% 60% at 85% 30%, rgba(32,71,18,.5) 0%, transparent 60%);
    padding: 44px 0 48px;
}

.ir-band-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 28px;
}

.ir-hero {
    background: #fff;
    border-radius: 22px;
    padding: 52px 48px 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow:
        0 0 0 1px rgba(32,71,18,.1),
        0 20px 60px rgba(5,18,2,.28),
        0 4px 12px rgba(5,18,2,.12);
}

.ir-hero-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    background: rgba(32,71,18,.06);
}
.ir-hero-orb-1 { width: 220px; height: 220px; top: -60px; right: -60px; }
.ir-hero-orb-2 { width: 140px; height: 140px; bottom: -40px; left: -40px; }
.ir-hero-orb-3 { width: 80px; height: 80px; top: 30px; left: -20px; background: rgba(154,111,10,.05); }

.ir-hero-accent {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 20px;
    position: relative;
}

.ir-hero-line {
    width: 36px;
    height: 2px;
    background: #204712;
    border-radius: 2px;
    flex-shrink: 0;
}

.ir-hero-tag {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: #204712;
}

.ir-hero-title {
    font-size: 32px;
    font-weight: 800;
    color: #0f1f0a;
    margin-bottom: 14px;
    line-height: 1.18;
    letter-spacing: -.02em;
    position: relative;
}

.ir-hero-desc {
    font-size: 14.5px;
    color: #5a6b55;
    line-height: 1.75;
    max-width: 620px;
    margin: 0 auto 28px;
    position: relative;
}
.ir-hero-desc.no-pills { margin-bottom: 0; }

.ir-hero-pills {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
    position: relative;
}

.ir-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 16px;
    border-radius: 999px;
    background: rgba(32,71,18,.07);
    border: 1px solid rgba(32,71,18,.15);
    font-size: 12px;
    font-weight: 600;
    color: #204712;
}

.ir-hero-pill-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #204712;
    flex-shrink: 0;
}

.ir-docs {
    width: 100%;
    padding: 48px 28px 72px;
}

.ir-docs-inner {
    max-width: 1280px;
    margin: 0 auto;
}

.ir-section-label {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 36px;
}

.ir-section-label-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, #dce8d8 0%, transparent 100%);
}

.ir-section-label-text {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #7a9470;
    white-space: nowrap;
}

.ir-grid {
    --ir-card-min: 220px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(var(--ir-card-min), 1fr));
    gap: 34px 26px;
    justify-content: center;
    align-items: start;
}

.ir-grid > * {
    min-width: 0;
}

.ir-card {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: min(100%, 248px);
    margin: 0 auto;
    transform: translateY(0);
    opacity: 0;
    animation: ir-card-in .6s cubic-bezier(.22,.61,.36,1) forwards;
}

.ir-card:nth-child(2n) { animation-delay: .05s; }
.ir-card:nth-child(3n) { animation-delay: .1s; }
.ir-card:nth-child(4n) { animation-delay: .15s; }

@keyframes ir-card-in {
    from {
        opacity: 0;
        transform: translateY(18px) scale(.985);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.ir-book-wrap {
    position: relative;
    width: 100%;
    filter: drop-shadow(0 10px 24px rgba(5,18,2,.12));
    transition: transform .28s cubic-bezier(.22,.61,.36,1), filter .28s ease;
}

.ir-card:hover .ir-book-wrap {
    transform: translateY(-6px);
    filter: drop-shadow(0 18px 34px rgba(5,18,2,.18));
}

.ir-book-cover {
    position: relative;
    width: 100%;
    aspect-ratio: 3 / 4;
    border-radius: 5px 18px 18px 5px;
    overflow: hidden;
    background: #edf4eb;
    border-left: 6px solid rgba(0,0,0,.16);
    box-shadow:
        inset -2px 0 6px rgba(0,0,0,.05),
        inset 2px 0 4px rgba(255,255,255,.55),
        0 1px 0 rgba(255,255,255,.5);
    transition: transform .32s cubic-bezier(.22,.61,.36,1), box-shadow .32s ease;
}

.ir-card:hover .ir-book-cover {
    transform: perspective(900px) rotateY(-4deg) rotateX(.6deg);
    box-shadow:
        inset -2px 0 6px rgba(0,0,0,.05),
        inset 2px 0 4px rgba(255,255,255,.55),
        0 16px 28px rgba(5,18,2,.12);
}

.ir-book-cover::before {
    content: '';
    position: absolute;
    top: 8px;
    bottom: 8px;
    right: -4px;
    width: 4px;
    background: repeating-linear-gradient(
        to bottom,
        #e2e2e2 0px, #e2e2e2 1px,
        #f4f4f4 1px, #f4f4f4 3px
    );
    border-radius: 0 2px 2px 0;
    z-index: 0;
}

.ir-book-cover::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(255,255,255,.12) 0%, rgba(255,255,255,0) 34%, rgba(0,0,0,.04) 100%);
    pointer-events: none;
    z-index: 1;
}

.ir-book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .38s cubic-bezier(.22,.61,.36,1), filter .38s ease;
}

.ir-card:hover .ir-book-cover img {
    transform: scale(1.045);
    filter: saturate(1.04) contrast(1.02);
}

.ir-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    background: linear-gradient(160deg, #f2f8f0 0%, #dcebd7 100%);
    padding: 32px;
    text-align: center;
    position: relative;
    z-index: 2;
}

.ir-placeholder-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    background: #204712;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
        0 8px 22px rgba(32,71,18,.32),
        0 2px 6px rgba(32,71,18,.18);
}

.ir-placeholder-text {
    font-size: 12px;
    color: #6b8065;
    line-height: 1.6;
    max-width: 140px;
}

.ir-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(4,12,2,.94) 0%,
        rgba(4,12,2,.68) 44%,
        rgba(4,12,2,.14) 100%
    );
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 18px 14px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(8px);
    transition: opacity .24s ease, visibility .24s ease, transform .28s cubic-bezier(.22,.61,.36,1);
    z-index: 10;
}

.ir-card:hover .ir-overlay,
.ir-card:focus-within .ir-overlay {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.ir-overlay-title {
    font-size: 11.5px;
    font-weight: 600;
    color: rgba(255,255,255,.78);
    text-align: center;
    line-height: 1.42;
    margin-bottom: 2px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    width: 100%;
}

.ir-overlay-actions {
    display: flex;
    gap: 8px;
    width: 100%;
}

.ir-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    height: 40px;
    border-radius: 11px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    transition: transform .16s ease, box-shadow .16s ease, background .16s ease;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.ir-btn:hover { transform: translateY(-2px); }

.ir-btn--view {
    background: rgba(255,255,255,.94);
    color: #204712;
    box-shadow: 0 4px 14px rgba(0,0,0,.18);
}
.ir-btn--dl {
    background: linear-gradient(180deg, #28561a 0%, #204712 100%);
    color: #fff;
    border: 1px solid rgba(255,255,255,.14);
    box-shadow: 0 8px 18px rgba(32,71,18,.34);
}

.ir-card-year {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 14px;
    padding: 5px 12px;
    border-radius: 999px;
    background: rgba(32,71,18,.06);
    border: 1px solid rgba(32,71,18,.12);
    color: #204712;
    font-size: 11.5px;
    font-weight: 700;
}

.ir-card-title {
    width: 100%;
    margin-top: 12px;
    font-size: 14px;
    font-weight: 700;
    color: #1a2e16;
    line-height: 1.55;
    text-align: center;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ir-empty {
    width: 100%;
    text-align: center;
    padding: 64px 28px;
    color: #8a9e85;
    font-size: 14px;
    border: 1.5px dashed rgba(32,71,18,.16);
    border-radius: 18px;
    background: rgba(32,71,18,.025);
}

@media (max-width: 1060px) {
    .ir-band-inner { padding: 0 20px; }
    .ir-docs { padding: 42px 20px 60px; }
    .ir-grid { --ir-card-min: 200px; gap: 28px 20px; }
    .ir-card { width: min(100%, 232px); }
}

@media (max-width: 680px) {
    .ir-band { padding: 28px 0 32px; }
    .ir-band-inner { padding: 0 16px; }
    .ir-docs { padding: 34px 16px 52px; }

    .ir-hero { padding: 36px 22px 32px; border-radius: 18px; }
    .ir-hero-title { font-size: 22px; }
    .ir-hero-desc { font-size: 13.5px; }

    .ir-grid {
        --ir-card-min: 150px;
        gap: 22px 14px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .ir-card { width: 100%; max-width: none; }

    .ir-overlay {
        opacity: 1;
        visibility: visible;
        transform: none;
        padding: 10px;
        gap: 6px;
    }

    .ir-overlay-title { display: none; }

    .ir-btn {
        height: 34px;
        font-size: 11px;
        border-radius: 8px;
        gap: 3px;
    }

    .ir-card-title {
        font-size: 12.5px;
        margin-top: 10px;
    }
}

@media (max-width: 400px) {
    .ir-grid { grid-template-columns: 1fr; }
    .ir-card { max-width: 260px; }
}
</style>

<div class="ir-page">

    <div class="ir-band">
        <div class="ir-band-inner">
            <div class="ir-hero">
                <div class="ir-hero-orb ir-hero-orb-1"></div>
                <div class="ir-hero-orb ir-hero-orb-2"></div>
                <div class="ir-hero-orb ir-hero-orb-3"></div>

                <div class="ir-hero-accent">
                    <div class="ir-hero-line"></div>
                    <span class="ir-hero-tag">PT BUMI SIAK PUSAKO ZAPIN</span>
                    <div class="ir-hero-line"></div>
                </div>

                <div class="ir-hero-title">
                    {{ $locale === 'id' ? 'Hubungan Investor' : 'Investor Relations' }}
                </div>

                <div class="ir-hero-desc {{ isset($highlightItems) && $highlightItems->count() ? '' : 'no-pills' }}">
                    {{ $locale === 'id'
                        ? 'Laporan tahunan dan dokumen hubungan investor yang tersaji secara profesional, informatif, dan mudah diakses.'
                        : 'Annual reports and investor relations documents presented professionally, informatively, and with easy access.' }}
                </div>

                @if(isset($highlightItems) && $highlightItems->count())
                    <div class="ir-hero-pills">
                        @foreach($highlightItems as $item)
                            @php
                                $label = $locale === 'en'
                                    ? (!empty($item->label_en) ? $item->label_en : $item->label_id)
                                    : $item->label_id;
                            @endphp
                            <span class="ir-hero-pill">
                                <span class="ir-hero-pill-dot"></span>
                                {{ $label }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="ir-docs">
        <div class="ir-docs-inner">

            <div class="ir-section-label">
                <span class="ir-section-label-text">
                    {{ $locale === 'id' ? 'Laporan Tahunan' : 'Annual Reports' }}
                </span>
                <div class="ir-section-label-line"></div>
            </div>

            <div class="ir-grid">
                @forelse($documents as $doc)
                    @php
                        $tr = $doc->translations->firstWhere('locale', $locale) ?? $doc->translations->first();
                        $docTitle = $tr->title ?? $doc->file_name ?? 'Document';
                    @endphp

                    <div class="ir-card">
                        <div class="ir-book-wrap">
                            <div class="ir-book-cover">

                                @if(!empty($doc->cover))
                                    <img src="{{ asset('images/investor-relations/' . $doc->cover) }}" alt="{{ $docTitle }}">
                                @else
                                    <div class="ir-placeholder">
                                        <div class="ir-placeholder-icon">
                                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9z"/>
                                                <polyline points="14 2 14 9 21 9"/>
                                                <line x1="9" y1="13" x2="15" y2="13"/>
                                                <line x1="9" y1="17" x2="13" y2="17"/>
                                            </svg>
                                        </div>
                                        <div class="ir-placeholder-text">
                                            {{ $locale === 'id' ? 'Cover belum tersedia.' : 'Cover not available.' }}
                                        </div>
                                    </div>
                                @endif

                                <div class="ir-overlay">
                                    <div class="ir-overlay-title">{{ $docTitle }}</div>
                                    <div class="ir-overlay-actions">
                                        <a href="{{ asset('documents/investor-relations/' . $doc->file_path) }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="ir-btn ir-btn--view">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            {{ $locale === 'id' ? 'Lihat' : 'View' }}
                                        </a>

                                        <a href="{{ route('investor-relations.download', $doc) }}"
                                           class="ir-btn ir-btn--dl">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                            {{ $locale === 'id' ? 'Unduh' : 'Download' }}
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @if($doc->year)
                            <div class="ir-card-year">{{ $doc->year }}</div>
                        @endif

                        <div class="ir-card-title">{{ $docTitle }}</div>
                    </div>
                @empty
                    <div class="ir-empty">
                        {{ $locale === 'id' ? 'Belum ada dokumen hubungan investor.' : 'No investor relations documents yet.' }}
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</div>

@endsection