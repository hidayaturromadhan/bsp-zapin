@extends('layouts.reviewer')

@section('title', 'Preview TJSL Reviewer')

@section('content')
@php
    $translation = $translation ?? $program->getTranslation($locale ?? 'id');
    $trEn = $program->getTranslation('en');

    $slides = collect();

    if ($program->featured_image) {
        $slides->push([
            'src' => asset($program->featured_image),
            'caption' => $translation?->title ?? 'TJSL',
        ]);
    }

    foreach ($program->images as $image) {
        $slides->push([
            'src' => asset($image->image_path),
            'caption' => $image->caption ?: ($translation?->title ?? 'TJSL'),
        ]);
    }

    $badgeClass = $program->status === 'published' ? 'a-badge--green' : 'a-badge--gray';
@endphp

<style>
    .preview-wrap {
        display: grid;
        gap: 18px;
    }

    .preview-hero {
        background: linear-gradient(135deg, #173f08, #245d0f);
        color: #fff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .14);
    }

    .preview-top {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .preview-label {
        display: inline-flex;
        padding: 8px 13px;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 12px;
        font-weight: 900;
        margin-bottom: 14px;
    }

    .preview-title {
        margin: 0;
        max-width: 880px;
        font-size: clamp(26px, 4vw, 42px);
        line-height: 1.12;
        letter-spacing: -.04em;
        font-weight: 900;
    }

    .preview-desc {
        max-width: 760px;
        margin: 12px 0 0;
        color: rgba(255,255,255,.78);
        line-height: 1.75;
        font-size: 14px;
    }

    .preview-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, .08);
        overflow: hidden;
    }

    .preview-slider {
        position: relative;
        height: 430px;
        background: #eef6eb;
        overflow: hidden;
    }

    .preview-track {
        height: 100%;
        display: flex;
        transition: transform .45s ease;
    }

    .preview-slide {
        min-width: 100%;
        height: 100%;
        position: relative;
    }

    .preview-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .preview-caption {
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: 18px;
        color: #fff;
        padding: 14px 16px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(23,63,8,.78), rgba(15,23,42,.58));
        font-size: 13px;
        line-height: 1.6;
    }

    .preview-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,.28);
        background: rgba(255,255,255,.18);
        color: #fff;
        font-size: 26px;
        cursor: pointer;
        z-index: 5;
    }

    .preview-arrow.prev { left: 14px; }
    .preview-arrow.next { right: 14px; }

    .preview-dots {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 14px;
        display: flex;
        justify-content: center;
        gap: 7px;
        z-index: 6;
    }

    .preview-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        border: 0;
        background: rgba(255,255,255,.5);
        cursor: pointer;
    }

    .preview-dot.active {
        width: 24px;
        background: #fff;
    }

    .preview-body {
        padding: 24px;
        display: grid;
        gap: 18px;
    }

    .preview-meta {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .preview-meta-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
    }

    .preview-meta-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .07em;
    }

    .preview-meta-value {
        margin-top: 6px;
        font-size: 14px;
        color: #0f172a;
        font-weight: 900;
    }

    .preview-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .preview-box {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
        background: #fff;
    }

    .preview-box h3 {
        margin: 0 0 10px;
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
    }

    .preview-summary {
        color: #64748b;
        line-height: 1.75;
        margin-bottom: 14px;
    }

    .preview-rich {
        color: #334155;
        line-height: 1.85;
        font-size: 14px;
    }

    .preview-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 12px;
    }

    .preview-gallery-item {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #fff;
    }

    .preview-gallery-item img {
        width: 100%;
        height: 135px;
        object-fit: cover;
        display: block;
    }

    .preview-gallery-caption {
        padding: 10px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
    }

    @media(max-width: 900px) {
        .preview-slider { height: 300px; }
        .preview-meta { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .preview-grid { grid-template-columns: 1fr; }
    }

    @media(max-width: 640px) {
        .preview-hero { padding: 20px; }
        .preview-body { padding: 16px; }
        .preview-meta { grid-template-columns: 1fr; }
        .preview-slider { height: 250px; }
    }
</style>

<div class="preview-wrap">
    <div class="preview-hero">
        <div class="preview-top">
            <div>
                <div class="preview-label">Reviewer Preview</div>
                <h1 class="preview-title">{{ $translation?->title ?? 'Preview TJSL' }}</h1>
                <p class="preview-desc">
                    {{ $translation?->summary ?: 'Preview tampilan konten TJSL sebelum dipublikasikan ke halaman publik.' }}
                </p>
            </div>

            <div style="display:flex;gap:8px;flex-wrap:wrap">
                <a href="{{ route('reviewer.tjsl.show', $program) }}" class="a-btn a-btn--secondary">
                    Kembali ke Detail
                </a>
                <a href="{{ route('reviewer.tjsl.index') }}" class="a-btn a-btn--light">
                    Daftar TJSL
                </a>
            </div>
        </div>
    </div>

    <div class="preview-card">
        @if($slides->count())
            <div class="preview-slider" data-preview-slider>
                <div class="preview-track">
                    @foreach($slides as $slide)
                        <div class="preview-slide">
                            <img src="{{ $slide['src'] }}" alt="{{ $slide['caption'] }}">
                            <div class="preview-caption">{{ $slide['caption'] }}</div>
                        </div>
                    @endforeach
                </div>

                @if($slides->count() > 1)
                    <button type="button" class="preview-arrow prev" data-preview-prev>‹</button>
                    <button type="button" class="preview-arrow next" data-preview-next>›</button>

                    <div class="preview-dots">
                        @foreach($slides as $slide)
                            <button type="button" class="preview-dot {{ $loop->first ? 'active' : '' }}" data-preview-dot="{{ $loop->index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <div class="preview-body">
            <div class="preview-meta">
                <div class="preview-meta-item">
                    <div class="preview-meta-label">Status</div>
                    <div class="preview-meta-value">
                        <span class="a-badge {{ $badgeClass }}">{{ $program->status_label }}</span>
                    </div>
                </div>

                <div class="preview-meta-item">
                    <div class="preview-meta-label">Tahun</div>
                    <div class="preview-meta-value">{{ $program->year }}</div>
                </div>

                <div class="preview-meta-item">
                    <div class="preview-meta-label">Writer</div>
                    <div class="preview-meta-value">{{ $program->author?->name ?? '-' }}</div>
                </div>

                <div class="preview-meta-item">
                    <div class="preview-meta-label">Publish</div>
                    <div class="preview-meta-value">{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
            </div>

            <div class="preview-grid">
                <div class="preview-box">
                    <h3>Bahasa Indonesia</h3>
                    <div class="preview-summary">{{ $translation?->summary ?? '-' }}</div>
                    <div class="preview-rich">
                        {!! nl2br(e($translation?->content ?? '-')) !!}
                    </div>
                </div>

                <div class="preview-box">
                    <h3>English Otomatis</h3>
                    <div class="preview-summary">{{ $trEn?->summary ?? '-' }}</div>
                    <div class="preview-rich">
                        {!! nl2br(e($trEn?->content ?? '-')) !!}
                    </div>
                </div>
            </div>

            <div class="preview-box">
                <h3>Galeri Dokumentasi</h3>

                @if($program->images->count())
                    <div class="preview-gallery">
                        @foreach($program->images as $image)
                            <div class="preview-gallery-item">
                                <img src="{{ asset($image->image_path) }}" alt="{{ $image->caption ?: 'Galeri TJSL' }}">
                                <div class="preview-gallery-caption">{{ $image->caption ?: 'Tanpa caption' }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="color:#64748b">Belum ada gambar galeri.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const slider = document.querySelector('[data-preview-slider]');
    if (!slider) return;

    const track = slider.querySelector('.preview-track');
    const slides = slider.querySelectorAll('.preview-slide');
    const prev = slider.querySelector('[data-preview-prev]');
    const next = slider.querySelector('[data-preview-next]');
    const dots = slider.querySelectorAll('[data-preview-dot]');

    let index = 0;

    function render() {
        track.style.transform = `translateX(-${index * 100}%)`;
        dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
    }

    if (prev) {
        prev.addEventListener('click', function () {
            index = index <= 0 ? slides.length - 1 : index - 1;
            render();
        });
    }

    if (next) {
        next.addEventListener('click', function () {
            index = index >= slides.length - 1 ? 0 : index + 1;
            render();
        });
    }

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            index = Number(dot.dataset.previewDot);
            render();
        });
    });
})();
</script>
@endsection