@php
    $translation = $translation ?: $program->getTranslation($locale ?? 'id');
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

    $badgeClass = match($program->status) {
        'published' => 'a-badge--green',
        default => 'a-badge--gray',
    };
@endphp

<style>
    .tj-preview-shell{
        display:grid;
        gap:18px;
    }

    .tj-preview-hero{
        background:
            radial-gradient(circle at top left, rgba(255,255,255,.12), transparent 36%),
            linear-gradient(135deg,#173f08,#21560e);
        color:#fff;
        border-radius:24px;
        padding:26px;
        overflow:hidden;
        position:relative;
        box-shadow:0 14px 34px rgba(15,23,42,.12);
    }

    .tj-preview-hero::after{
        content:"";
        position:absolute;
        width:260px;
        height:260px;
        right:-90px;
        top:-100px;
        border-radius:999px;
        background:rgba(255,255,255,.08);
    }

    .tj-preview-top{
        position:relative;
        z-index:2;
        display:flex;
        justify-content:space-between;
        gap:14px;
        flex-wrap:wrap;
        align-items:flex-start;
    }

    .tj-preview-kicker{
        display:inline-flex;
        align-items:center;
        gap:8px;
        min-height:32px;
        padding:0 12px;
        border-radius:999px;
        background:rgba(255,255,255,.11);
        border:1px solid rgba(255,255,255,.14);
        color:rgba(255,255,255,.84);
        font-size:12px;
        font-weight:900;
    }

    .tj-preview-title{
        position:relative;
        z-index:2;
        margin:16px 0 0;
        max-width:850px;
        font-size:clamp(26px,4vw,44px);
        line-height:1.08;
        letter-spacing:-.045em;
        font-weight:900;
    }

    .tj-preview-desc{
        position:relative;
        z-index:2;
        max-width:760px;
        margin:12px 0 0;
        color:rgba(255,255,255,.75);
        line-height:1.75;
        font-size:14px;
    }

    .tj-preview-actions{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        position:relative;
        z-index:2;
    }

    .tj-preview-card{
        background:#fff;
        border:1px solid #e2e8f0;
        border-radius:22px;
        box-shadow:0 12px 32px rgba(15,23,42,.07);
        overflow:hidden;
    }

    .tj-preview-slider{
        position:relative;
        height:430px;
        background:#eef6eb;
        overflow:hidden;
    }

    .tj-preview-track{
        height:100%;
        display:flex;
        transition:transform .45s ease;
    }

    .tj-preview-slide{
        min-width:100%;
        height:100%;
        position:relative;
    }

    .tj-preview-slide img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .tj-preview-slide-caption{
        position:absolute;
        left:18px;
        right:18px;
        bottom:18px;
        color:#fff;
        padding:16px;
        border-radius:16px;
        background:linear-gradient(135deg,rgba(23,63,8,.78),rgba(15,23,42,.56));
        backdrop-filter:blur(6px);
        font-size:13px;
        line-height:1.6;
    }

    .tj-preview-arrow{
        position:absolute;
        top:50%;
        transform:translateY(-50%);
        width:42px;
        height:42px;
        border-radius:999px;
        border:1px solid rgba(255,255,255,.28);
        background:rgba(255,255,255,.17);
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        font-weight:900;
        z-index:5;
        backdrop-filter:blur(6px);
    }

    .tj-preview-arrow.prev{left:14px}
    .tj-preview-arrow.next{right:14px}

    .tj-preview-dots{
        position:absolute;
        left:0;
        right:0;
        bottom:14px;
        display:flex;
        justify-content:center;
        gap:7px;
        z-index:6;
    }

    .tj-preview-dot{
        width:8px;
        height:8px;
        border-radius:999px;
        border:0;
        background:rgba(255,255,255,.48);
        cursor:pointer;
        transition:.2s ease;
    }

    .tj-preview-dot.active{
        width:24px;
        background:#fff;
    }

    .tj-preview-body{
        padding:24px;
        display:grid;
        gap:18px;
    }

    .tj-preview-meta{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:12px;
    }

    .tj-preview-meta-item{
        background:#f8fafc;
        border:1px solid #e2e8f0;
        border-radius:16px;
        padding:14px;
    }

    .tj-preview-meta-label{
        font-size:11px;
        color:#64748b;
        font-weight:900;
        text-transform:uppercase;
        letter-spacing:.07em;
    }

    .tj-preview-meta-value{
        margin-top:5px;
        font-size:14px;
        color:#0f172a;
        font-weight:900;
    }

    .tj-preview-content-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:18px;
    }

    .tj-preview-content-box{
        border:1px solid #e2e8f0;
        border-radius:18px;
        padding:18px;
        background:#fff;
    }

    .tj-preview-content-box h3{
        margin:0 0 10px;
        font-size:18px;
        font-weight:900;
        color:#0f172a;
    }

    .tj-preview-content-box .summary{
        color:#64748b;
        line-height:1.75;
        margin-bottom:14px;
    }

    .tj-preview-rich{
        color:#334155;
        line-height:1.85;
        font-size:14px;
    }

    .tj-preview-gallery{
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(190px,1fr));
        gap:12px;
    }

    .tj-preview-gallery-item{
        border-radius:16px;
        overflow:hidden;
        border:1px solid #e2e8f0;
        background:#fff;
    }

    .tj-preview-gallery-item img{
        width:100%;
        height:135px;
        object-fit:cover;
        display:block;
    }

    .tj-preview-gallery-caption{
        padding:10px;
        font-size:12px;
        color:#64748b;
        line-height:1.5;
    }

    @media(max-width:900px){
        .tj-preview-slider{height:300px}
        .tj-preview-meta{grid-template-columns:repeat(2,minmax(0,1fr))}
        .tj-preview-content-grid{grid-template-columns:1fr}
    }

    @media(max-width:640px){
        .tj-preview-hero{padding:20px}
        .tj-preview-body{padding:16px}
        .tj-preview-meta{grid-template-columns:1fr}
        .tj-preview-slider{height:250px}
    }
</style>

<div class="tj-preview-shell">
    <div class="tj-preview-hero">
        <div class="tj-preview-top">
            <div>
                <div class="tj-preview-kicker">{{ $panelLabel ?? 'Preview' }}</div>
                <h1 class="tj-preview-title">{{ $translation?->title ?? 'Preview TJSL' }}</h1>
                <p class="tj-preview-desc">
                    {{ $translation?->summary ?: 'Preview tampilan konten TJSL sebelum dipublikasikan ke halaman publik.' }}
                </p>
            </div>

            <div class="tj-preview-actions">
                <a href="{{ $backUrl }}" class="a-btn a-btn--secondary">{{ $backLabel ?? 'Kembali' }}</a>

                @if(request()->routeIs('writer.*'))
                    <a href="{{ route('writer.tjsl.edit', $program) }}" class="a-btn a-btn--primary">Edit Konten</a>
                @endif
            </div>
        </div>
    </div>

    <div class="tj-preview-card">
        @if($slides->count())
            <div class="tj-preview-slider" data-preview-slider>
                <div class="tj-preview-track">
                    @foreach($slides as $slide)
                        <div class="tj-preview-slide">
                            <img src="{{ $slide['src'] }}" alt="{{ $slide['caption'] }}">
                            <div class="tj-preview-slide-caption">{{ $slide['caption'] }}</div>
                        </div>
                    @endforeach
                </div>

                @if($slides->count() > 1)
                    <button type="button" class="tj-preview-arrow prev" data-preview-prev>‹</button>
                    <button type="button" class="tj-preview-arrow next" data-preview-next>›</button>

                    <div class="tj-preview-dots">
                        @foreach($slides as $slide)
                            <button type="button" class="tj-preview-dot {{ $loop->first ? 'active' : '' }}" data-preview-dot="{{ $loop->index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <div class="tj-preview-body">
            <div class="tj-preview-meta">
                <div class="tj-preview-meta-item">
                    <div class="tj-preview-meta-label">Status</div>
                    <div class="tj-preview-meta-value">
                        <span class="a-badge {{ $badgeClass }}">{{ $program->status_label }}</span>
                    </div>
                </div>

                <div class="tj-preview-meta-item">
                    <div class="tj-preview-meta-label">Tahun</div>
                    <div class="tj-preview-meta-value">{{ $program->year }}</div>
                </div>

                <div class="tj-preview-meta-item">
                    <div class="tj-preview-meta-label">Writer</div>
                    <div class="tj-preview-meta-value">{{ $program->author?->name ?? '-' }}</div>
                </div>

                <div class="tj-preview-meta-item">
                    <div class="tj-preview-meta-label">Publish</div>
                    <div class="tj-preview-meta-value">{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
            </div>

            <div class="tj-preview-content-grid">
                <div class="tj-preview-content-box">
                    <h3>Bahasa Indonesia</h3>
                    <div class="summary">{{ $translation?->summary ?? '-' }}</div>
                    <div class="tj-preview-rich">
                        {!! nl2br(e($translation?->content ?? '-')) !!}
                    </div>
                </div>

                <div class="tj-preview-content-box">
                    <h3>English Otomatis</h3>
                    <div class="summary">{{ $trEn?->summary ?? '-' }}</div>
                    <div class="tj-preview-rich">
                        {!! nl2br(e($trEn?->content ?? '-')) !!}
                    </div>
                </div>
            </div>

            <div class="tj-preview-content-box">
                <h3>Galeri Dokumentasi</h3>

                @if($program->images->count())
                    <div class="tj-preview-gallery">
                        @foreach($program->images as $image)
                            <div class="tj-preview-gallery-item">
                                <img src="{{ asset($image->image_path) }}" alt="{{ $image->caption ?: 'Galeri TJSL' }}">
                                <div class="tj-preview-gallery-caption">{{ $image->caption ?: 'Tanpa caption' }}</div>
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

    const track = slider.querySelector('.tj-preview-track');
    const slides = slider.querySelectorAll('.tj-preview-slide');
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