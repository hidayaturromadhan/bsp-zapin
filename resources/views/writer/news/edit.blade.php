@extends('layouts.writer')

@section('content')
<style>
    .wn-page { max-width: 1180px; }
    .wn-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .wn-title { margin:0; font-size:30px; font-weight:800; color:#111827; letter-spacing:-.03em; }
    .wn-subtitle { margin-top:6px; font-size:14px; color:#6b7280; line-height:1.7; }
    .wn-back { display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; font-weight:700; text-decoration:none; }

    .wn-layout { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:20px; align-items:start; }
    .wn-card { background:#fff; border:1px solid #e5e7eb; border-radius:18px; padding:22px; box-shadow:0 10px 24px rgba(15,23,42,.04); }
    .wn-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
    .wn-field { margin-bottom:16px; }
    .wn-field.full { grid-column:1 / -1; }

    .wn-label {
        display:block;
        margin-bottom:8px;
        font-size:13px;
        font-weight:800;
        color:#111827;
    }

    .wn-input, .wn-textarea, .wn-select {
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

    .wn-input:focus, .wn-textarea:focus, .wn-select:focus {
        outline:none;
        border-color:#7aa46d;
        box-shadow:0 0 0 4px rgba(47,125,50,.08);
    }

    .wn-textarea {
        min-height:110px;
        padding:12px;
        resize:vertical;
    }

    .wn-help {
        margin-top:6px;
        font-size:12px;
        color:#6b7280;
        line-height:1.6;
    }

    .wn-thumb {
        width:180px;
        max-width:100%;
        border-radius:12px;
        border:1px solid #e5e7eb;
        display:block;
        margin-top:10px;
        object-fit:cover;
        background:#f8fafc;
    }

    .wn-builder-head {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        margin:10px 0 14px;
        flex-wrap:wrap;
    }

    .wn-builder-title {
        margin:0;
        font-size:18px;
        font-weight:800;
        color:#111827;
    }

    .wn-builder-actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .wn-btn {
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
        transition:.18s ease;
    }

    .wn-btn:hover {
        transform:translateY(-1px);
    }

    .wn-btn--primary {
        background:#173f08;
        border-color:#173f08;
        color:#fff;
    }

    .wn-btn--danger {
        background:#fff5f5;
        border-color:#efc8c8;
        color:#b42318;
    }

    .wn-blocks {
        display:grid;
        gap:14px;
    }

    .wn-block {
        padding:14px;
        border:1px solid #e5e7eb;
        border-radius:14px;
        background:#f9fafb;
        transition:border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .wn-block:hover {
        border-color:#cbd5e1;
    }

    .wn-block-top {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin-bottom:12px;
        flex-wrap:wrap;
    }

    .wn-block-head-left {
        display:flex;
        align-items:center;
        gap:10px;
        min-width:0;
    }

    .wn-block-badge {
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

    .wn-block-actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .wn-drag-handle {
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

    .wn-drag-handle:active {
        cursor:grabbing;
    }

    .wn-drag-handle svg {
        width:18px;
        height:18px;
        stroke:currentColor;
    }

    .wn-sortable-ghost {
        opacity:.4;
    }

    .wn-sortable-chosen {
        box-shadow:0 14px 28px rgba(15,23,42,.14);
        border-color:#94a3b8;
    }

    .wn-sortable-drag {
        transform:rotate(.4deg);
    }

    .wn-actions {
        display:flex;
        justify-content:flex-end;
        margin-top:20px;
    }

    .wn-side-title {
        margin:0 0 12px;
        font-size:16px;
        font-weight:800;
        color:#111827;
    }

    .wn-side-row {
        padding:10px 0;
        border-bottom:1px solid #f1f5f9;
    }

    .wn-side-row:last-child {
        border-bottom:0;
    }

    .wn-side-label {
        font-size:12px;
        font-weight:800;
        color:#6b7280;
        text-transform:uppercase;
        letter-spacing:.04em;
        margin-bottom:4px;
    }

    .wn-side-value {
        font-size:14px;
        color:#111827;
        line-height:1.6;
    }

    .wn-file-input {
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

    .wn-file-upload {
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

    .wn-file-upload:hover {
        border-color:#9fb79a;
        background:#fbfdfb;
    }

    .wn-file-upload:focus-within {
        border-color:#7aa46d;
        box-shadow:0 0 0 4px rgba(47,125,50,.08);
    }

    .wn-file-trigger {
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

    .wn-file-trigger svg {
        width:16px;
        height:16px;
        stroke:currentColor;
    }

    .wn-file-name {
        min-width:0;
        font-size:13px;
        color:#64748b;
        line-height:1.5;
        display:flex;
        align-items:center;
    }

    .wn-file-name span {
        display:block;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }

    .wn-select-wrap {
        position:relative;
        width:100%;
        border:1px solid #d1d5db;
        border-radius:12px;
        background:#fff;
        min-height:56px;
        transition:border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .wn-select-wrap:hover {
        border-color:#9fb79a;
        background:#fbfdfb;
    }

    .wn-select-wrap:focus-within {
        border-color:#7aa46d;
        box-shadow:0 0 0 4px rgba(47,125,50,.08);
    }

    .wn-select-wrap::after {
        content:'';
        position:absolute;
        top:50%;
        right:16px;
        width:10px;
        height:10px;
        border-right:2px solid #64748b;
        border-bottom:2px solid #64748b;
        transform:translateY(-65%) rotate(45deg);
        pointer-events:none;
    }

    .wn-select-custom {
        width:100%;
        min-height:56px;
        border:none;
        outline:none;
        background:transparent;
        padding:0 46px 0 16px;
        border-radius:12px;
        font:inherit;
        font-size:14px;
        font-weight:600;
        color:#111827;
        appearance:none;
        -webkit-appearance:none;
        -moz-appearance:none;
        cursor:pointer;
    }

    .wn-featured-preview {
        margin-top:12px;
        padding:12px;
        border:1px solid #e5e7eb;
        border-radius:14px;
        background:#f8fafc;
    }

    .wn-featured-preview-label {
        font-size:12px;
        font-weight:800;
        color:#64748b;
        text-transform:uppercase;
        letter-spacing:.04em;
        margin-bottom:8px;
    }

    .wn-gallery-note {
        margin-top:8px;
        font-size:12px;
        color:#6b7280;
        line-height:1.6;
    }

    .hidden { display:none !important; }

    @media (max-width: 980px) {
        .wn-layout { grid-template-columns:1fr; }
    }

    @media (max-width: 760px) {
        .wn-grid { grid-template-columns:1fr; }
        .wn-block-top { align-items:flex-start; }

        .wn-file-upload {
            align-items:flex-start;
            flex-direction:column;
        }

        .wn-file-trigger {
            width:100%;
        }

        .wn-file-name {
            width:100%;
        }
    }
</style>

<div class="wn-page">
    <div class="wn-head">
        <div>
            <h1 class="wn-title">Edit News</h1>
            <div class="wn-subtitle">Perbarui konten, susun ulang block dengan drag, dan kirim ulang ke reviewer.</div>
        </div>

        <a href="{{ route('writer.news.index') }}" class="wn-back">Kembali</a>
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

    <form method="POST" action="{{ route('writer.news.update', $news) }}" enctype="multipart/form-data" id="writer-news-form">
        @csrf
        @method('PUT')

        <div class="wn-layout">
            <div>
                <div class="wn-card">
                    <div class="wn-grid">
                        <div class="wn-field full">
                            <label class="wn-label">Kategori</label>
                            <div class="wn-select-wrap">
                                <select name="news_category_id" class="wn-select-custom" required>
                                    <option value="">Pilih kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string) old('news_category_id', $news->news_category_id) === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="wn-field full">
                            <label class="wn-label">Judul (ID)</label>
                            <input type="text" name="id_title" class="wn-input" value="{{ old('id_title', $tId->title) }}" required>
                        </div>

                        <div class="wn-field full">
                            <label class="wn-label">Slug (ID, optional)</label>
                            <input type="text" name="id_slug" class="wn-input" value="{{ old('id_slug', $tId->slug) }}">
                        </div>

                        <div class="wn-field full">
                            <label class="wn-label">Excerpt (ID)</label>
                            <textarea name="id_excerpt" class="wn-textarea">{{ old('id_excerpt', $tId->excerpt) }}</textarea>
                        </div>

                        <div class="wn-field">
                            <label class="wn-label">Featured Image</label>

                            <input
                                type="file"
                                name="featured_image"
                                id="featured_image"
                                class="wn-file-input"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <div class="wn-file-upload">
                                <label for="featured_image" class="wn-file-trigger">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M12 16V4"/>
                                        <path d="M7 9l5-5 5 5"/>
                                        <path d="M5 20h14"/>
                                    </svg>
                                    <span>Pilih Gambar</span>
                                </label>

                                <div class="wn-file-name" id="featured_image_name">
                                    <span>Biarkan kosong jika tidak ingin mengganti gambar</span>
                                </div>
                            </div>

                            @if($news->featured_image)
                                <div class="wn-featured-preview">
                                    <div class="wn-featured-preview-label">Preview saat ini</div>
                                    <img src="{{ asset($news->featured_image) }}" alt="Featured image" class="wn-thumb">
                                </div>
                            @endif
                        </div>

                        <div class="wn-field">
                            <label class="wn-label">Jadwal Publish Target</label>
                            <input
                                type="datetime-local"
                                name="published_at"
                                class="wn-input"
                                value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}"
                            >
                            <div class="wn-help">Jadwal ini akan dipakai saat reviewer approve, kecuali reviewer override manual.</div>
                        </div>

                        <div class="wn-field full">
                            <label class="wn-label">Galeri Tambahan</label>

                            <input
                                type="file"
                                name="gallery_images[]"
                                id="gallery_images"
                                class="wn-file-input"
                                accept=".jpg,.jpeg,.png,.webp"
                                multiple
                            >

                            <div class="wn-file-upload">
                                <label for="gallery_images" class="wn-file-trigger">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M12 16V4"/>
                                        <path d="M7 9l5-5 5 5"/>
                                        <path d="M5 20h14"/>
                                    </svg>
                                    <span>Pilih Galeri</span>
                                </label>

                                <div class="wn-file-name" id="gallery_images_name">
                                    <span>Belum ada file dipilih</span>
                                </div>
                            </div>

                            <div class="wn-gallery-note">
                                Kamu bisa memilih lebih dari satu gambar untuk galeri tambahan.
                            </div>
                        </div>
                    </div>

                    <div class="wn-builder-head">
                        <h2 class="wn-builder-title">Content Builder</h2>

                        <div class="wn-builder-actions">
                            <button type="button" class="wn-btn" data-add-block="heading">+ Heading</button>
                            <button type="button" class="wn-btn" data-add-block="text">+ Text</button>
                            <button type="button" class="wn-btn" data-add-block="image">+ Image</button>
                        </div>
                    </div>

                    <div class="wn-blocks" id="blocks-wrapper">
                        @foreach($blocks as $i => $block)
                            <div class="wn-block" data-block-item>
                                <div class="wn-block-top">
                                    <div class="wn-block-head-left">
                                        <button type="button" class="wn-drag-handle" title="Tahan lalu geser untuk ubah urutan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                                <path d="M9 5h.01M9 12h.01M9 19h.01M15 5h.01M15 12h.01M15 19h.01"/>
                                            </svg>
                                        </button>

                                        <span class="wn-block-badge">{{ strtoupper($block['type'] ?? 'TEXT') }}</span>
                                    </div>

                                    <div class="wn-block-actions">
                                        <button type="button" class="wn-btn wn-btn--danger" data-remove-block>Hapus</button>
                                    </div>
                                </div>

                                <input type="hidden" name="blocks[{{ $i }}][type]" value="{{ $block['type'] ?? 'text' }}" data-field="type">

                                <div class="wn-field block-heading {{ ($block['type'] ?? 'text') === 'heading' ? '' : 'hidden' }}">
                                    <label class="wn-label">Heading</label>
                                    <input type="text" name="blocks[{{ $i }}][title]" class="wn-input" value="{{ $block['title'] ?? '' }}" data-field="title">
                                </div>

                                <div class="wn-field block-text {{ ($block['type'] ?? 'text') === 'text' ? '' : 'hidden' }}">
                                    <label class="wn-label">Paragraph</label>
                                    <textarea name="blocks[{{ $i }}][body]" class="wn-textarea" data-field="body">{{ $block['body'] ?? '' }}</textarea>
                                </div>

                                <div class="wn-field block-image {{ ($block['type'] ?? 'text') === 'image' ? '' : 'hidden' }}">
                                    <label class="wn-label">Upload Image</label>
                                    <input type="file" name="block_images[{{ $i }}]" class="wn-input" accept=".jpg,.jpeg,.png,.webp" data-file-field="image">

                                    @if(!empty($block['image']))
                                        <input type="hidden" name="blocks[{{ $i }}][existing_image]" value="{{ $block['image'] }}" data-field="existing_image">
                                        <img src="{{ asset($block['image']) }}" alt="Block image" class="wn-thumb">
                                    @endif
                                </div>

                                <div class="wn-field block-image {{ ($block['type'] ?? 'text') === 'image' ? '' : 'hidden' }}" style="margin-bottom:0;">
                                    <label class="wn-label">Caption</label>
                                    <input type="text" name="blocks[{{ $i }}][caption]" class="wn-input" value="{{ $block['caption'] ?? '' }}" data-field="caption">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="wn-actions">
                        <button type="submit" class="wn-btn wn-btn--primary">Simpan & Kirim Ulang ke Reviewer</button>
                    </div>
                </div>
            </div>

            <div>
                <div class="wn-card">
                    <h3 class="wn-side-title">Status Saat Ini</h3>

                    <div class="wn-side-row">
                        <div class="wn-side-label">Status</div>
                        <div class="wn-side-value">{{ ucfirst(str_replace('_', ' ', $news->status)) }}</div>
                    </div>

                    <div class="wn-side-row">
                        <div class="wn-side-label">Publish Target</div>
                        <div class="wn-side-value">{{ $news->published_at?->format('Y-m-d H:i') ?? '-' }}</div>
                    </div>

                    <div class="wn-side-row">
                        <div class="wn-side-label">Reviewer Note</div>
                        <div class="wn-side-value">{{ $news->review_note ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
(function () {
    const wrapper = document.getElementById('blocks-wrapper');
    const form = document.getElementById('writer-news-form');
    const featuredInput = document.getElementById('featured_image');
    const featuredName = document.getElementById('featured_image_name');
    const galleryInput = document.getElementById('gallery_images');
    const galleryName = document.getElementById('gallery_images_name');

    function blockTemplate(type, index) {
        return `
            <div class="wn-block" data-block-item>
                <div class="wn-block-top">
                    <div class="wn-block-head-left">
                        <button type="button" class="wn-drag-handle" title="Tahan lalu geser untuk ubah urutan">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <path d="M9 5h.01M9 12h.01M9 19h.01M15 5h.01M15 12h.01M15 19h.01"/>
                            </svg>
                        </button>

                        <span class="wn-block-badge">${type.toUpperCase()}</span>
                    </div>

                    <div class="wn-block-actions">
                        <button type="button" class="wn-btn wn-btn--danger" data-remove-block>Hapus</button>
                    </div>
                </div>

                <input type="hidden" name="blocks[${index}][type]" value="${type}" data-field="type">

                <div class="wn-field block-heading ${type === 'heading' ? '' : 'hidden'}">
                    <label class="wn-label">Heading</label>
                    <input type="text" name="blocks[${index}][title]" class="wn-input" data-field="title">
                </div>

                <div class="wn-field block-text ${type === 'text' ? '' : 'hidden'}">
                    <label class="wn-label">Paragraph</label>
                    <textarea name="blocks[${index}][body]" class="wn-textarea" data-field="body"></textarea>
                </div>

                <div class="wn-field block-image ${type === 'image' ? '' : 'hidden'}">
                    <label class="wn-label">Upload Image</label>
                    <input type="file" name="block_images[${index}]" class="wn-input" accept=".jpg,.jpeg,.png,.webp" data-file-field="image">
                </div>

                <div class="wn-field block-image ${type === 'image' ? '' : 'hidden'}" style="margin-bottom:0;">
                    <label class="wn-label">Caption</label>
                    <input type="text" name="blocks[${index}][caption]" class="wn-input" data-field="caption">
                </div>
            </div>
        `;
    }

    function reindexBlocks() {
        const items = wrapper.querySelectorAll('[data-block-item]');

        items.forEach((item, index) => {
            const typeField = item.querySelector('[data-field="type"]');
            const type = typeField ? typeField.value : 'text';

            const badge = item.querySelector('.wn-block-badge');
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

    function updateSingleFileName(input, target, emptyText = 'Belum ada file dipilih') {
        if (!input || !target) return;
        const file = input.files && input.files[0] ? input.files[0].name : emptyText;
        target.innerHTML = `<span>${file}</span>`;
    }

    function updateMultipleFileName(input, target) {
        if (!input || !target) return;

        if (!input.files || input.files.length === 0) {
            target.innerHTML = `<span>Belum ada file dipilih</span>`;
            return;
        }

        if (input.files.length === 1) {
            target.innerHTML = `<span>${input.files[0].name}</span>`;
            return;
        }

        target.innerHTML = `<span>${input.files.length} file dipilih</span>`;
    }

    if (featuredInput && featuredName) {
        featuredInput.addEventListener('change', function () {
            updateSingleFileName(this, featuredName, 'Biarkan kosong jika tidak ingin mengganti gambar');
        });
    }

    if (galleryInput && galleryName) {
        galleryInput.addEventListener('change', function () {
            updateMultipleFileName(this, galleryName);
        });
    }

    document.querySelectorAll('[data-add-block]').forEach((btn) => {
        btn.addEventListener('click', function () {
            const type = this.getAttribute('data-add-block');
            const index = wrapper.querySelectorAll('[data-block-item]').length;
            wrapper.insertAdjacentHTML('beforeend', blockTemplate(type, index));
            reindexBlocks();
        });
    });

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
        handle: '.wn-drag-handle',
        draggable: '[data-block-item]',
        ghostClass: 'wn-sortable-ghost',
        chosenClass: 'wn-sortable-chosen',
        dragClass: 'wn-sortable-drag',
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