@php
    $translation = $translation ?: $newsItem->getTranslationByLocale($locale ?? 'id');
    $trEn = $newsItem->getTranslationByLocale('en');

    $slides = collect();

    if ($newsItem->featured_image) {
        $slides->push([
            'src' => asset($newsItem->featured_image),
            'caption' => $translation?->title ?? 'Berita',
        ]);
    }

    foreach ($newsItem->images as $image) {
        $slides->push([
            'src' => asset($image->image_path),
            'caption' => $image->caption ?: ($translation?->title ?? 'Berita'),
        ]);
    }

    $badgeClass = $newsItem->status === 'published' ? 'a-badge--green' : 'a-badge--gray';
@endphp

<style>
    .news-preview-shell {
        display: grid;
        gap: 18px;
    }

    .news-preview-hero {
        background:
            radial-gradient(circle at top left, rgba(255,255,255,.14), transparent 36%),
            linear-gradient(135deg, #173f08, #245d0f);
        color: #fff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 14px 34px rgba(15,23,42,.12);
        overflow: hidden;
    }

    .news-preview-top {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .news-preview-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 32px;
        padding: 0 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        color: rgba(255,255,255,.86);
        font-size: 12px;
        font-weight: 900;
        margin-bottom: 14px;
    }

    .news-preview-title {
        margin: 0;
        max-width: 900px;
        font-size: clamp(28px, 4vw, 46px);
        line-height: 1.08;
        letter-spacing: -.045em;
        font-weight: 900;
    }

    .news-preview-desc {
        max-width: 760px;
        margin: 12px 0 0;
        color: rgba(255,255,255,.78);
        line-height: 1.75;
        font-size: 14px;
    }

    .news-preview-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .news-preview-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        box-shadow: 0 12px 32px rgba(15,23,42,.07);
        overflow: hidden;
    }

    .news-preview-slider {
        position: relative;
        height: 430px;
        background: #eef6eb;
        overflow: hidden;
    }

    .news-preview-track {
        height: 100%;
        display: flex;
        transition: transform .45s ease;
    }

    .news-preview-slide {
        min-width: 100%;
        height: 100%;
        position: relative;
    }

    .news-preview-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .news-preview-caption {
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

    .news-preview-arrow {
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

    .news-preview-arrow.prev { left: 14px; }
    .news-preview-arrow.next { right: 14px; }

    .news-preview-dots {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 14px;
        display: flex;
        justify-content: center;
        gap: 7px;
        z-index: 6;
    }

    .news-preview-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        border: 0;
        background: rgba(255,255,255,.5);
        cursor: pointer;
    }

    .news-preview-dot.active {
        width: 24px;
        background: #fff;
    }

    .news-preview-body {
        padding: 24px;
        display: grid;
        gap: 18px;
    }

    .news-preview-meta {
        display: grid;
        grid-template-columns: repeat(4,minmax(0,1fr));
        gap: 12px;
    }

    .news-preview-meta-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
    }

    .news-preview-meta-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .07em;
    }

    .news-preview-meta-value {
        margin-top: 6px;
        font-size: 14px;
        color: #0f172a;
        font-weight: 900;
    }

    .news-preview-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .news-preview-box {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
        background: #fff;
    }

    .news-preview-box h3 {
        margin: 0 0 10px;
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
    }

    .news-preview-summary {
        color: #64748b;
        line-height: 1.75;
        margin-bottom: 14px;
    }

    .news-preview-rich {
        color: #334155;
        line-height: 1.85;
        font-size: 14px;
    }

    .news-preview-rich figure {
        margin: 18px 0;
    }

    .news-preview-rich img {
        max-width: 100%;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
    }

    .news-preview-rich figcaption {
        margin-top: 8px;
        color: #64748b;
        font-size: 12px;
        text-align: center;
    }

    .news-preview-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill,minmax(190px,1fr));
        gap: 12px;
    }

    .news-preview-gallery-item {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #fff;
    }

    .news-preview-gallery-item img {
        width: 100%;
        height: 135px;
        object-fit: cover;
        display: block;
    }

    .news-preview-gallery-caption {
        padding: 10px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
    }

    @media(max-width:900px) {
        .news-preview-slider { height: 300px; }
        .news-preview-meta { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .news-preview-grid { grid-template-columns: 1fr; }
    }

    @media(max-width:640px) {
        .news-preview-hero { padding: 20px; }
        .news-preview-body { padding: 16px; }
        .news-preview-meta { grid-template-columns: 1fr; }
        .news-preview-slider { height: 250px; }
    }
</style>

<div class="news-preview-shell">
    <div class="news-preview-hero">
        <div class="news-preview-top">
            <div>
                <div class="news-preview-kicker">{{ $panelLabel ?? 'Preview Berita' }}</div>
                <h1 class="news-preview-title">{{ $translation?->title ?? 'Preview Berita' }}</h1>
                <p class="news-preview-desc">
                    {{ $translation?->excerpt ?: 'Preview tampilan berita sebelum dipublikasikan ke halaman publik.' }}
                </p>
            </div>

            <div class="news-preview-actions">
                <a href="{{ $backUrl }}" class="a-btn a-btn--secondary">{{ $backLabel ?? 'Kembali' }}</a>

                @if(request()->routeIs('writer.*'))
                    <a href="{{ route('writer.news.edit', $newsItem) }}" class="a-btn a-btn--primary">Edit Konten</a>
                @endif
            </div>
        </div>
    </div>

    <div class="news-preview-card">
        @if($slides->count())
            <div class="news-preview-slider" data-news-preview-slider>
                <div class="news-preview-track">
                    @foreach($slides as $slide)
                        <div class="news-preview-slide">
                            <img src="{{ $slide['src'] }}" alt="{{ $slide['caption'] }}">
                            <div class="news-preview-caption">{{ $slide['caption'] }}</div>
                        </div>
                    @endforeach
                </div>

                @if($slides->count() > 1)
                    <button type="button" class="news-preview-arrow prev" data-news-preview-prev>‹</button>
                    <button type="button" class="news-preview-arrow next" data-news-preview-next>›</button>

                    <div class="news-preview-dots">
                        @foreach($slides as $slide)
                            <button type="button" class="news-preview-dot {{ $loop->first ? 'active' : '' }}" data-news-preview-dot="{{ $loop->index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <div class="news-preview-body">
            <div class="news-preview-meta">
                <div class="news-preview-meta-item">
                    <div class="news-preview-meta-label">Status</div>
                    <div class="news-preview-meta-value">
                        <span class="a-badge {{ $badgeClass }}">{{ $newsItem->status_label }}</span>
                    </div>
                </div>

                <div class="news-preview-meta-item">
                    <div class="news-preview-meta-label">Kategori</div>
                    <div class="news-preview-meta-value">{{ $newsItem->category?->name ?? '-' }}</div>
                </div>

                <div class="news-preview-meta-item">
                    <div class="news-preview-meta-label">Writer</div>
                    <div class="news-preview-meta-value">{{ $newsItem->author?->name ?? '-' }}</div>
                </div>

                <div class="news-preview-meta-item">
                    <div class="news-preview-meta-label">Publish</div>
                    <div class="news-preview-meta-value">{{ $newsItem->published_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
            </div>

            <div class="news-preview-grid">
                <div class="news-preview-box">
                    <h3>Bahasa Indonesia</h3>
                    <div class="news-preview-summary">{{ $translation?->excerpt ?? '-' }}</div>
                    <div class="news-preview-rich">
                        {!! $translation?->content ?? '-' !!}
                    </div>
                </div>

                <div class="news-preview-box">
                    <h3>English Otomatis</h3>
                    <div class="news-preview-summary">{{ $trEn?->excerpt ?? '-' }}</div>
                    <div class="news-preview-rich">
                        {!! $trEn?->content ?? '-' !!}
                    </div>
                </div>
            </div>

            <div class="news-preview-box">
                <h3>Galeri Dokumentasi</h3>

                @if($newsItem->images->count())
                    <div class="news-preview-gallery">
                        @foreach($newsItem->images as $image)
                            <div class="news-preview-gallery-item">
                                <img src="{{ asset($image->image_path) }}" alt="{{ $image->caption ?: 'Galeri Berita' }}">
                                <div class="news-preview-gallery-caption">{{ $image->caption ?: 'Tanpa caption' }}</div>
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
    const slider = document.querySelector('[data-news-preview-slider]');
    if (!slider) return;

    const track = slider.querySelector('.news-preview-track');
    const slides = slider.querySelectorAll('.news-preview-slide');
    const prev = slider.querySelector('[data-news-preview-prev]');
    const next = slider.querySelector('[data-news-preview-next]');
    const dots = slider.querySelectorAll('[data-news-preview-dot]');

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
            index = Number(dot.dataset.newsPreviewDot);
            render();
        });
    });
})();
</script>