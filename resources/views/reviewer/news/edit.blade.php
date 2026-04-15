@extends('layouts.reviewer')

@section('content')
<style>
    .re-page { max-width: 1160px; }
    .re-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .re-title { margin:0; font-size:30px; font-weight:800; color:#111827; letter-spacing:-.03em; }
    .re-subtitle { margin-top:6px; font-size:14px; color:#6b7280; line-height:1.7; }
    .re-back { display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; font-weight:700; text-decoration:none; }

    .re-layout { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:20px; align-items:start; }
    .re-card { background:#fff; border:1px solid #e5e7eb; border-radius:18px; padding:22px; box-shadow:0 10px 24px rgba(15,23,42,.04); }
    .re-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
    .re-field { margin-bottom:16px; }
    .re-field.full { grid-column:1 / -1; }

    .re-label {
        display:block;
        margin-bottom:8px;
        font-size:13px;
        font-weight:800;
        color:#111827;
    }

    .re-input, .re-textarea {
        width:100%;
        min-height:44px;
        border:1px solid #d1d5db;
        border-radius:10px;
        padding:0 12px;
        font:inherit;
        color:#111827;
        background:#fff;
        transition:border-color .18s ease, box-shadow .18s ease;
    }

    .re-input:focus, .re-textarea:focus {
        outline:none;
        border-color:#7aa46d;
        box-shadow:0 0 0 4px rgba(47,125,50,.08);
    }

    .re-textarea {
        min-height:110px;
        padding:12px;
        resize:vertical;
    }

    .re-help {
        margin-top:6px;
        font-size:12px;
        color:#6b7280;
        line-height:1.6;
    }

    .re-review {
        margin-bottom:16px;
        padding:14px;
        border-radius:14px;
        background:#f8fafc;
        border:1px solid #e5e7eb;
    }

    .re-review-title {
        margin:0 0 8px;
        font-size:15px;
        font-weight:800;
        color:#111827;
    }

    .re-review-meta {
        font-size:13px;
        line-height:1.8;
        color:#6b7280;
    }

    .re-review-note {
        margin-top:8px;
        font-size:13px;
        line-height:1.7;
        color:#374151;
    }

    .re-section-title {
        margin:10px 0 14px;
        font-size:18px;
        font-weight:800;
        color:#111827;
    }

    .re-blocks {
        display:grid;
        gap:14px;
    }

    .re-block {
        padding:14px;
        border:1px solid #e5e7eb;
        border-radius:14px;
        background:#f9fafb;
        transition:border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .re-block:hover {
        border-color:#cbd5e1;
    }

    .re-block-top {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin-bottom:12px;
        flex-wrap:wrap;
    }

    .re-block-head-left {
        display:flex;
        align-items:center;
        gap:10px;
        min-width:0;
    }

    .re-block-badge {
        display:inline-flex;
        align-items:center;
        min-height:30px;
        padding:0 10px;
        border-radius:999px;
        background:#eef5eb;
        color:#173f08;
        font-size:12px;
        font-weight:800;
    }

    .re-drag-handle {
        width:40px;
        height:40px;
        border-radius:10px;
        border:1px dashed #cbd5e1;
        background:#fff;
        color:#64748b;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        cursor:grab;
        flex-shrink:0;
    }

    .re-drag-handle:active {
        cursor:grabbing;
    }

    .re-drag-handle svg {
        width:18px;
        height:18px;
        stroke:currentColor;
    }

    .re-block-actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .re-status { display:inline-flex; align-items:center; justify-content:center; min-height:32px; padding:0 12px; border-radius:999px; font-size:12px; font-weight:800; line-height:1; }
    .re-status--blue { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
    .re-status--green { background:#ecfdf3; color:#166534; border:1px solid #bbf7d0; }
    .re-status--red { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
    .re-status--gray { background:#f3f4f6; color:#4b5563; border:1px solid #e5e7eb; }

    .re-thumb {
        width:180px;
        max-width:100%;
        border-radius:12px;
        border:1px solid #e5e7eb;
        display:block;
        margin-top:10px;
        object-fit:cover;
        background:#f8fafc;
    }

    .re-btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:40px;
        padding:0 14px;
        border-radius:10px;
        border:1px solid #d1d5db;
        background:#fff;
        color:#111827;
        font-weight:700;
        cursor:pointer;
        text-decoration:none;
        transition:.18s ease;
    }

    .re-btn:hover {
        transform:translateY(-1px);
    }

    .re-btn--primary { background:#173f08; border-color:#173f08; color:#fff; }
    .re-btn--approve { background:#173f08; color:#fff; border-color:#173f08; }
    .re-btn--reject { background:#fff1f2; color:#b42318; border-color:#fecdd3; }
    .re-btn--danger { background:#fff5f5; border-color:#efc8c8; color:#b42318; }

    .re-sortable-ghost { opacity:.4; }
    .re-sortable-chosen { box-shadow:0 14px 28px rgba(15,23,42,.14); border-color:#94a3b8; }
    .re-sortable-drag { transform:rotate(.4deg); }

    .re-actions { display:flex; justify-content:flex-end; margin-top:20px; }
    .re-side-stack { display:grid; gap:16px; }
    .re-side-title { margin:0 0 12px; font-size:16px; font-weight:800; color:#111827; }
    .re-side-row { padding:10px 0; border-bottom:1px solid #f1f5f9; }
    .re-side-row:last-child { border-bottom:0; }
    .re-side-label { font-size:12px; font-weight:800; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; margin-bottom:4px; }
    .re-side-value { font-size:14px; color:#111827; line-height:1.6; }

    .re-review-form { display:grid; gap:14px; }
    .re-review-actions { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .hidden { display:none !important; }

    .re-file-input {
        position:absolute;
        width:1px;
        height:1px;
        padding:0;
        margin:-1px;
        overflow:hidden;
        clip:rect(0,0,0,0);
        white-space:nowrap;
        border:0;
    }

    .re-file-upload {
        width:100%;
        border:1px solid #d1d5db;
        border-radius:12px;
        background:#fff;
        min-height:56px;
        padding:8px;
        display:flex;
        align-items:center;
        gap:10px;
        transition:border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .re-file-upload:hover {
        border-color:#9fb79a;
        background:#fbfdfb;
    }

    .re-file-upload:focus-within {
        border-color:#7aa46d;
        box-shadow:0 0 0 4px rgba(47,125,50,.08);
    }

    .re-file-trigger {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        min-height:38px;
        padding:0 14px;
        border-radius:10px;
        background:#173f08;
        color:#fff;
        font-size:13px;
        font-weight:700;
        cursor:pointer;
        white-space:nowrap;
        flex-shrink:0;
    }

    .re-file-trigger svg {
        width:16px;
        height:16px;
        stroke:currentColor;
    }

    .re-file-name {
        min-width:0;
        font-size:13px;
        color:#64748b;
        line-height:1.5;
        display:flex;
        align-items:center;
    }

    .re-file-name span {
        display:block;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }

    .re-featured-preview {
        margin-top:12px;
        padding:12px;
        border:1px solid #e5e7eb;
        border-radius:14px;
        background:#f8fafc;
    }

    .re-featured-preview-label {
        font-size:12px;
        font-weight:800;
        color:#64748b;
        text-transform:uppercase;
        letter-spacing:.04em;
        margin-bottom:8px;
    }

    @media (max-width:980px) {
        .re-layout { grid-template-columns:1fr; }
    }

    @media (max-width:760px) {
        .re-grid { grid-template-columns:1fr; }
        .re-review-actions { grid-template-columns:1fr; }
        .re-block-top { align-items:flex-start; }
        .re-file-upload {
            align-items:flex-start;
            flex-direction:column;
        }
        .re-file-trigger {
            width:100%;
        }
        .re-file-name {
            width:100%;
        }
    }
</style>

@php
    $statusClass = match($news->status) {
        'published' => 're-status--green',
        'rejected' => 're-status--red',
        'in_review' => 're-status--blue',
        default => 're-status--gray',
    };

    $statusLabel = ucfirst(str_replace('_', ' ', $news->status));
@endphp

<div class="re-page">
    <div class="re-head">
        <div>
            <h1 class="re-title">Edit Review News</h1>
            <div class="re-subtitle">Reviewer dapat mengatur ulang block dengan drag, revisi konten, lalu approve atau reject.</div>
        </div>

        <a href="{{ route('reviewer.news.index') }}" class="re-back">Kembali</a>
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#eef8ee;color:#17603a;border:1px solid #cfe9d3;font-size:14px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#fff4f4;color:#b42318;border:1px solid #f3c6c6;font-size:14px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li style="margin:4px 0;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="re-layout">
        <div>
            <div class="re-review">
                <h2 class="re-review-title">Info Review</h2>
                <div class="re-review-meta">
                    Status: <span class="re-status {{ $statusClass }}">{{ $statusLabel }}</span><br>
                    Penulis: {{ $news->author?->name ?? '-' }}<br>
                    Reviewer: {{ $news->reviewer?->name ?? '-' }}<br>
                    Jadwal dari Writer: {{ $news->published_at?->format('Y-m-d H:i') ?? '-' }}<br>
                    Reviewed At: {{ $news->reviewed_at?->format('Y-m-d H:i') ?? '-' }}
                </div>

                @if(!empty($news->review_note))
                    <div class="re-review-note">
                        Catatan terakhir: {{ $news->review_note }}
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('reviewer.news.update', $news) }}" enctype="multipart/form-data" id="reviewer-news-form">
                @csrf
                @method('PUT')

                <div class="re-card">
                    <div class="re-grid">
                        <div class="re-field full">
                            <label class="re-label">Judul (ID)</label>
                            <input type="text" name="id_title" class="re-input" value="{{ old('id_title', $tId->title) }}" required>
                        </div>

                        <div class="re-field full">
                            <label class="re-label">Slug (ID, optional)</label>
                            <input type="text" name="id_slug" class="re-input" value="{{ old('id_slug', $tId->slug) }}">
                        </div>

                        <div class="re-field full">
                            <label class="re-label">Excerpt (ID)</label>
                            <textarea name="id_excerpt" class="re-textarea">{{ old('id_excerpt', $tId->excerpt) }}</textarea>
                        </div>

                        <div class="re-field">
                            <label class="re-label">Featured Image</label>

                            <input
                                type="file"
                                name="featured_image"
                                id="featured_image"
                                class="re-file-input"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <div class="re-file-upload">
                                <label for="featured_image" class="re-file-trigger">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M12 16V4"/>
                                        <path d="M7 9l5-5 5 5"/>
                                        <path d="M5 20h14"/>
                                    </svg>
                                    <span>Pilih Gambar</span>
                                </label>

                                <div class="re-file-name" id="featured_image_name">
                                    <span>Belum ada file dipilih</span>
                                </div>
                            </div>

                            @if($news->featured_image)
                                <div class="re-featured-preview">
                                    <div class="re-featured-preview-label">Preview saat ini</div>
                                    <img src="{{ asset($news->featured_image) }}" alt="Featured" class="re-thumb">
                                </div>
                            @endif
                        </div>

                        <div class="re-field">
                            <label class="re-label">Jadwal Publish</label>
                            <input
                                type="datetime-local"
                                name="published_at"
                                class="re-input"
                                value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}"
                            >
                            <div class="re-help">
                                Kosongkan jika tidak ingin mengubah jadwal existing.
                            </div>
                        </div>

                        <div class="re-field full">
                            <label class="re-label">Catatan Reviewer</label>
                            <textarea name="review_note" class="re-textarea">{{ old('review_note', $news->review_note) }}</textarea>
                        </div>
                    </div>

                    <h2 class="re-section-title">Konten</h2>

                    <div class="re-blocks" id="blocks-wrapper">
                        @foreach($blocks as $i => $block)
                            <div class="re-block" data-block-item>
                                <div class="re-block-top">
                                    <div class="re-block-head-left">
                                        <button type="button" class="re-drag-handle" title="Tahan lalu geser untuk ubah urutan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                                <path d="M9 5h.01M9 12h.01M9 19h.01M15 5h.01M15 12h.01M15 19h.01"/>
                                            </svg>
                                        </button>

                                        <span class="re-block-badge">{{ strtoupper($block['type'] ?? 'TEXT') }}</span>
                                    </div>

                                    <div class="re-block-actions">
                                        <button type="button" class="re-btn re-btn--danger" data-remove-block>Hapus</button>
                                    </div>
                                </div>

                                <input type="hidden" name="blocks[{{ $i }}][type]" value="{{ $block['type'] ?? 'text' }}" data-field="type">

                                <div class="re-field block-heading {{ ($block['type'] ?? 'text') === 'heading' ? '' : 'hidden' }}">
                                    <label class="re-label">Heading</label>
                                    <input type="text" name="blocks[{{ $i }}][title]" class="re-input" value="{{ $block['title'] ?? '' }}" data-field="title">
                                </div>

                                <div class="re-field block-text {{ ($block['type'] ?? 'text') === 'text' ? '' : 'hidden' }}">
                                    <label class="re-label">Paragraph</label>
                                    <textarea name="blocks[{{ $i }}][body]" class="re-textarea" data-field="body">{{ $block['body'] ?? '' }}</textarea>
                                </div>

                                <div class="re-field block-image {{ ($block['type'] ?? 'text') === 'image' ? '' : 'hidden' }}">
                                    <label class="re-label">Upload Image</label>
                                    <input type="file" name="block_images[{{ $i }}]" class="re-input" accept=".jpg,.jpeg,.png,.webp" data-file-field="image">

                                    @if(!empty($block['image']))
                                        <input type="hidden" name="blocks[{{ $i }}][existing_image]" value="{{ $block['image'] }}" data-field="existing_image">
                                        <img src="{{ asset($block['image']) }}" alt="Block image" class="re-thumb">
                                    @endif
                                </div>

                                <div class="re-field block-image {{ ($block['type'] ?? 'text') === 'image' ? '' : 'hidden' }}" style="margin-bottom:0;">
                                    <label class="re-label">Caption</label>
                                    <input type="text" name="blocks[{{ $i }}][caption]" class="re-input" value="{{ $block['caption'] ?? '' }}" data-field="caption">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="re-actions">
                        <button type="submit" class="re-btn re-btn--primary">Simpan Revisi Reviewer</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="re-side-stack">
            <div class="re-card">
                <h3 class="re-side-title">Review Action</h3>

                <form method="POST" action="{{ route('reviewer.news.review', $news) }}" class="re-review-form">
                    @csrf

                    <div>
                        <label class="re-label">Override Jadwal Publish</label>
                        <input
                            type="datetime-local"
                            name="published_at"
                            class="re-input"
                            value=""
                        >
                        <div class="re-help">
                            Biarkan kosong untuk memakai jadwal yang sudah diset writer.
                        </div>
                    </div>

                    <div>
                        <label class="re-label">Review Note</label>
                        <textarea name="review_note" class="re-textarea">{{ old('review_note', $news->review_note) }}</textarea>
                    </div>

                    <div class="re-review-actions">
                        <button type="submit" name="action" value="approve" class="re-btn re-btn--approve" onclick="return confirm('Approve berita ini?')">
                            Approve
                        </button>

                        <button type="submit" name="action" value="reject" class="re-btn re-btn--reject" onclick="return confirm('Reject berita ini?')">
                            Reject
                        </button>
                    </div>
                </form>
            </div>

            <div class="re-card">
                <h3 class="re-side-title">Ringkasan Status</h3>

                <div class="re-side-row">
                    <div class="re-side-label">Status</div>
                    <div class="re-side-value"><span class="re-status {{ $statusClass }}">{{ $statusLabel }}</span></div>
                </div>

                <div class="re-side-row">
                    <div class="re-side-label">Visible di Public</div>
                    <div class="re-side-value">{{ $news->is_visible ? 'Ya' : 'Tidak' }}</div>
                </div>

                <div class="re-side-row">
                    <div class="re-side-label">Publish Time</div>
                    <div class="re-side-value">{{ $news->published_at?->format('Y-m-d H:i') ?? '-' }}</div>
                </div>

                <div class="re-side-row">
                    <div class="re-side-label">Logs</div>
                    <div class="re-side-value">
                        <a href="{{ route('reviewer.news.logs', $news) }}" class="re-btn">Lihat Audit Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
(function () {
    const wrapper = document.getElementById('blocks-wrapper');
    const form = document.getElementById('reviewer-news-form');
    const featuredInput = document.getElementById('featured_image');
    const featuredName = document.getElementById('featured_image_name');

    function reindexBlocks() {
        const items = wrapper.querySelectorAll('[data-block-item]');

        items.forEach((item, index) => {
            const typeField = item.querySelector('[data-field="type"]');
            const type = typeField ? typeField.value : 'text';

            const badge = item.querySelector('.re-block-badge');
            if (badge) badge.textContent = type.toUpperCase();

            item.querySelectorAll('[name]').forEach((field) => {
                if (field.hasAttribute('data-file-field')) {
                    field.name = `block_images[${index}]`;
                    return;
                }

                const key = field.getAttribute('data-field');
                if (!key) return;
                field.name = `blocks[${index}][${key}]`;
            });
        });
    }

    if (featuredInput && featuredName) {
        featuredInput.addEventListener('change', function () {
            const file = this.files && this.files[0] ? this.files[0].name : 'Belum ada file dipilih';
            featuredName.innerHTML = `<span>${file}</span>`;
        });
    }

    wrapper.addEventListener('click', function (e) {
        const item = e.target.closest('[data-block-item]');
        if (!item) return;

        if (e.target.matches('[data-remove-block]')) {
            item.remove();
            reindexBlocks();
        }
    });

    new Sortable(wrapper, {
        animation: 180,
        handle: '.re-drag-handle',
        draggable: '[data-block-item]',
        ghostClass: 're-sortable-ghost',
        chosenClass: 're-sortable-chosen',
        dragClass: 're-sortable-drag',
        onEnd: function () {
            reindexBlocks();
        }
    });

    form.addEventListener('submit', function () {
        reindexBlocks();
    });

    reindexBlocks();
})();
</script>
@endsection