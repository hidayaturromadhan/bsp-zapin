@extends('layouts.app')

@section('title', $locale === 'id' ? 'Good Corporate Governance' : 'Good Corporate Governance')

@section('content')

<style>
/* ═══════════════════════════════════════════════════════
   RESET MAIN PADDING
═══════════════════════════════════════════════════════ */
.n-main {
    padding-left: 0 !important;
    padding-right: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

/* ═══════════════════════════════════════════════════════
   PAGE WRAPPER
═══════════════════════════════════════════════════════ */
.gcg-page {
    display: flex;
    flex-direction: column;
}

/* ═══════════════════════════════════════════════════════
   HERO BAND — FULL BLEED KIRI KANAN
═══════════════════════════════════════════════════════ */
.gcg-band {
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    background: #1b3d0f;
    background-image:
        radial-gradient(ellipse 60% 80% at 15% 50%, rgba(47,125,50,.35) 0%, transparent 65%),
        radial-gradient(ellipse 40% 60% at 85% 30%, rgba(32,71,18,.5) 0%, transparent 60%);
    padding: 44px 0 48px;
}

.gcg-band-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── Hero card ── */
.gcg-hero {
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

.gcg-hero-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    background: rgba(32,71,18,.06);
}
.gcg-hero-orb-1 { width: 220px; height: 220px; top: -60px;  right: -60px; }
.gcg-hero-orb-2 { width: 140px; height: 140px; bottom: -40px; left: -40px; }
.gcg-hero-orb-3 { width: 80px;  height: 80px;  top: 30px;   left: -20px;  background: rgba(154,111,10,.05); }

.gcg-hero-accent {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 20px;
    position: relative;
}

.gcg-hero-line {
    width: 36px;
    height: 2px;
    background: #204712;
    border-radius: 2px;
    flex-shrink: 0;
}

.gcg-hero-tag {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: #204712;
}

.gcg-hero-title {
    font-size: 32px;
    font-weight: 800;
    color: #0f1f0a;
    margin-bottom: 14px;
    line-height: 1.18;
    position: relative;
    letter-spacing: -.02em;
}

.gcg-hero-desc {
    font-size: 14.5px;
    color: #5a6b55;
    line-height: 1.75;
    max-width: 540px;
    margin: 0 auto 28px;
    position: relative;
}
.gcg-hero-desc.no-pills { margin-bottom: 0; }

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
    gap: 7px;
    padding: 7px 16px;
    border-radius: 999px;
    background: rgba(32,71,18,.07);
    border: 1px solid rgba(32,71,18,.15);
    font-size: 12px;
    font-weight: 600;
    color: #204712;
}

.gcg-hero-pill-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #204712;
    flex-shrink: 0;
}

/* ═══════════════════════════════════════════════════════
   DOCUMENTS SECTION
═══════════════════════════════════════════════════════ */
.gcg-docs {
    width: 100%;
    padding: 48px 28px 72px;
}

.gcg-docs-inner {
    max-width: 1280px;
    margin: 0 auto;
}

/* Section label */
.gcg-section-label {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 36px;
}

.gcg-section-label-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, #dce8d8 0%, transparent 100%);
}

.gcg-section-label-text {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #7a9470;
    white-space: nowrap;
}

/* ── FLEX GRID — auto center semua card ke tengah ── */
.gcg-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 36px 24px;
    justify-content: center;
    align-items: flex-start;
}

/* ── Card — lebar fixed agar konsisten ── */
.gcg-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 240px;
    flex-shrink: 0;
}

/* ── Book shadow wrapper ── */
.gcg-book-wrap {
    position: relative;
    width: 100%;
    filter: drop-shadow(4px 6px 18px rgba(5,18,2,.14));
    transition: filter .28s ease;
}

.gcg-card:hover .gcg-book-wrap {
    filter: drop-shadow(6px 10px 26px rgba(5,18,2,.22));
}

/* ── Book cover ── */
.gcg-book-cover {
    position: relative;
    width: 100%;
    aspect-ratio: 3 / 4;
    border-radius: 3px 14px 14px 3px;
    overflow: hidden;
    background: #edf4eb;
    border-left: 5px solid rgba(0,0,0,.16);
    box-shadow:
        inset -2px 0 6px rgba(0,0,0,.05),
        inset 2px 0 4px rgba(255,255,255,.5);
    transition: transform .28s cubic-bezier(.22,.68,0,1.15);
}

.gcg-card:hover .gcg-book-cover {
    transform: perspective(600px) rotateY(-3deg) translateY(-5px);
}

/* Page-edge lines (dekoratif) */
.gcg-book-cover::before {
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

.gcg-book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .35s ease;
}

.gcg-card:hover .gcg-book-cover img {
    transform: scale(1.04);
}

/* ── Placeholder (tidak ada cover) ── */
.gcg-placeholder {
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
}

.gcg-placeholder-icon {
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

.gcg-placeholder-text {
    font-size: 12px;
    color: #6b8065;
    line-height: 1.6;
    max-width: 140px;
}

/* ── Hover overlay ── */
.gcg-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(4,12,2,.92) 0%,
        rgba(4,12,2,.55) 45%,
        rgba(4,12,2,.08) 100%
    );
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 14px;
    opacity: 0;
    visibility: hidden;
    transition: opacity .22s ease, visibility .22s ease;
    z-index: 10;
}

.gcg-card:hover .gcg-overlay,
.gcg-card:focus-within .gcg-overlay {
    opacity: 1;
    visibility: visible;
}

.gcg-overlay-title {
    font-size: 11.5px;
    font-weight: 600;
    color: rgba(255,255,255,.75);
    text-align: center;
    line-height: 1.4;
    margin-bottom: 2px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    width: 100%;
}

.gcg-overlay-actions {
    display: flex;
    gap: 8px;
    width: 100%;
}

/* ── Buttons ── */
.gcg-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    height: 40px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    transition: transform .14s ease, box-shadow .14s ease;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.gcg-btn:hover { transform: translateY(-2px); }

.gcg-btn--view {
    background: rgba(255,255,255,.93);
    color: #204712;
    box-shadow: 0 4px 14px rgba(0,0,0,.18);
}
.gcg-btn--view:hover { box-shadow: 0 6px 20px rgba(0,0,0,.22); }

.gcg-btn--dl {
    background: #204712;
    color: #fff;
    border: 1px solid rgba(255,255,255,.14);
    box-shadow: 0 6px 18px rgba(32,71,18,.42);
}
.gcg-btn--dl:hover { box-shadow: 0 8px 24px rgba(32,71,18,.52); }

/* ── Card title ── */
.gcg-card-title {
    width: 100%;
    margin-top: 16px;
    font-size: 13.5px;
    font-weight: 700;
    color: #1a2e16;
    line-height: 1.5;
    text-align: center;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ── Empty state ── */
.gcg-empty {
    width: 100%;
    text-align: center;
    padding: 64px 28px;
    color: #8a9e85;
    font-size: 14px;
    border: 1.5px dashed rgba(32,71,18,.16);
    border-radius: 18px;
    background: rgba(32,71,18,.025);
}

.gcg-empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: rgba(32,71,18,.07);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: #7a9470;
}

/* ═══════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════ */
@media (max-width: 1060px) {
    .gcg-band-inner { padding: 0 20px; }
    .gcg-docs       { padding: 40px 20px 60px; }
    .gcg-card       { width: 210px; }
}

@media (max-width: 680px) {
    .gcg-band       { padding: 28px 0 32px; }
    .gcg-band-inner { padding: 0 16px; }
    .gcg-docs       { padding: 32px 16px 52px; }

    .gcg-hero       { padding: 36px 22px 32px; border-radius: 18px; }
    .gcg-hero-title { font-size: 22px; letter-spacing: -.01em; }
    .gcg-hero-desc  { font-size: 13.5px; }

    .gcg-grid       { gap: 22px 14px; }
    .gcg-card       { width: calc(50% - 7px); }

    .gcg-overlay {
        opacity: 1;
        visibility: visible;
        padding: 10px;
        gap: 6px;
    }

    .gcg-overlay-title { display: none; }

    .gcg-btn {
        height: 34px;
        font-size: 11px;
        border-radius: 8px;
        gap: 3px;
    }

    .gcg-btn svg { width: 11px; height: 11px; }

    .gcg-card-title {
        font-size: 12.5px;
        margin-top: 12px;
    }
}

@media (max-width: 400px) {
    .gcg-card { width: 100%; max-width: 260px; }
}
</style>

<div class="gcg-page">

    <div class="gcg-band">
        <div class="gcg-band-inner">
            <div class="gcg-hero">
                <div class="gcg-hero-orb gcg-hero-orb-1"></div>
                <div class="gcg-hero-orb gcg-hero-orb-2"></div>
                <div class="gcg-hero-orb gcg-hero-orb-3"></div>

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
                                $hl = $locale === 'en'
                                    ? (!empty($item->label_en) ? $item->label_en : $item->label_id)
                                    : $item->label_id;
                            @endphp
                            <span class="gcg-hero-pill">
                                <span class="gcg-hero-pill-dot"></span>
                                {{ $hl }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="gcg-docs">
        <div class="gcg-docs-inner">

            <div class="gcg-section-label">
                <span class="gcg-section-label-text">
                    {{ $locale === 'id' ? 'Dokumen GCG' : 'GCG Documents' }}
                </span>
                <div class="gcg-section-label-line"></div>
            </div>

            <div class="gcg-grid">
                @forelse($documents as $doc)
                    @php
                        $tr       = $doc->translations->firstWhere('locale', $locale) ?? $doc->translations->first();
                        $docTitle = $tr->title ?? $doc->file_name ?? 'Document';
                    @endphp

                    <div class="gcg-card">
                        <div class="gcg-book-wrap">
                            <div class="gcg-book-cover">

                                @if(!empty($doc->cover))
                                    <img src="{{ asset('images/gcg/' . $doc->cover) }}" alt="{{ $docTitle }}">
                                @else
                                    <div class="gcg-placeholder">
                                        <div class="gcg-placeholder-icon">
                                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9z"/>
                                                <polyline points="14 2 14 9 21 9"/>
                                                <line x1="9" y1="13" x2="15" y2="13"/>
                                                <line x1="9" y1="17" x2="13" y2="17"/>
                                            </svg>
                                        </div>
                                        <div class="gcg-placeholder-text">
                                            {{ $locale === 'id' ? 'Cover belum tersedia.' : 'Cover not available.' }}
                                        </div>
                                    </div>
                                @endif

                                <div class="gcg-overlay">
                                    <div class="gcg-overlay-title">{{ $docTitle }}</div>
                                    <div class="gcg-overlay-actions">
                                        <a href="{{ asset('documents/gcg/' . $doc->file_path) }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="gcg-btn gcg-btn--view">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            {{ $locale === 'id' ? 'Lihat' : 'View' }}
                                        </a>
                                        <a href="{{ route('gcg.download', $doc) }}"
                                           class="gcg-btn gcg-btn--dl">
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

                        <div class="gcg-card-title">{{ $docTitle }}</div>
                    </div>

                @empty
                    <div class="gcg-empty">
                        <div class="gcg-empty-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.6"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9z"/>
                                <polyline points="14 2 14 9 21 9"/>
                            </svg>
                        </div>
                        {{ $locale === 'id' ? 'Belum ada dokumen GCG.' : 'No GCG documents yet.' }}
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</div>

@endsection