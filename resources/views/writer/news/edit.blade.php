@extends('layouts.writer')

@section('title', 'Edit News')

@section('content')
<style>
    .wn-page {
        max-width: 1180px;
    }

    .wn-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .wn-title {
        margin: 0;
        font-size: 30px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.04em;
        line-height: 1.15;
    }

    .wn-subtitle {
        margin-top: 7px;
        font-size: 14px;
        color: #64748b;
        line-height: 1.7;
        max-width: 760px;
    }

    .wn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 15px;
        border-radius: 14px;
        border: 1px solid #dbe3ea;
        background: #fff;
        color: #334155;
        font-size: 13px;
        font-weight: 900;
        text-decoration: none;
        transition: transform .16s ease, background .16s ease, border-color .16s ease, color .16s ease, box-shadow .16s ease;
    }

    .wn-back:hover {
        transform: translateY(-1px);
        background: #eef6eb;
        border-color: rgba(23, 63, 8, .24);
        color: #173f08;
        box-shadow: 0 10px 22px rgba(15, 23, 42, .07);
    }

    .wn-back svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        flex-shrink: 0;
    }

    .wn-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 20px;
        align-items: start;
    }

    .wn-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
    }

    .wn-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .wn-field {
        margin-bottom: 16px;
    }

    .wn-field.full {
        grid-column: 1 / -1;
    }

    .wn-label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.01em;
    }

    .wn-input,
    .wn-textarea,
    .wn-select {
        width: 100%;
        min-height: 46px;
        border: 1px solid #d1d5db;
        border-radius: 13px;
        padding: 0 13px;
        font: inherit;
        font-size: 14px;
        color: #111827;
        background: #fff;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        box-sizing: border-box;
    }

    .wn-input:hover,
    .wn-textarea:hover,
    .wn-select:hover {
        border-color: #b8c7b4;
        background: #fbfdfb;
    }

    .wn-input:focus,
    .wn-textarea:focus,
    .wn-select:focus {
        outline: none;
        border-color: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .09);
        background: #fff;
    }

    .wn-textarea {
        min-height: 118px;
        padding: 12px 13px;
        resize: vertical;
        line-height: 1.75;
    }

    .wn-help {
        margin-top: 7px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.65;
    }

    .wn-thumb {
        width: 180px;
        max-width: 100%;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        display: block;
        margin-top: 10px;
        object-fit: cover;
        background: #f8fafc;
    }

    .wn-builder-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 10px 0 14px;
        flex-wrap: wrap;
        padding-top: 6px;
    }

    .wn-builder-title {
        margin: 0;
        font-size: 18px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.025em;
    }

    .wn-builder-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .wn-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 15px;
        border-radius: 13px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #111827;
        font-size: 13px;
        font-weight: 900;
        cursor: pointer;
        text-decoration: none;
        transition: transform .16s ease, background .16s ease, border-color .16s ease, color .16s ease, box-shadow .16s ease;
        white-space: nowrap;
    }

    .wn-btn svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        flex-shrink: 0;
    }

    .wn-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, .07);
    }

    .wn-btn--primary {
        background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
        border-color: #173f08;
        color: #fff;
        box-shadow: 0 10px 22px rgba(23, 63, 8, .16);
    }

    .wn-btn--primary:hover {
        background: linear-gradient(135deg, #102d06 0%, #173f08 100%);
        border-color: #102d06;
        color: #fff;
    }

    .wn-btn--danger {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #be123c;
    }

    .wn-btn--danger:hover {
        background: #ffe4e6;
        border-color: #fda4af;
        color: #9f1239;
    }

    .wn-btn--add {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #334155;
    }

    .wn-btn--add:hover {
        background: #eef6eb;
        border-color: rgba(23, 63, 8, .25);
        color: #173f08;
    }

    .wn-blocks {
        display: grid;
        gap: 14px;
    }

    .wn-block {
        padding: 14px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #f8fafc;
        transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease, background .18s ease;
    }

    .wn-block:hover {
        border-color: #cbd5e1;
        background: #fbfdfb;
    }

    .wn-block-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .wn-block-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .wn-block-badge {
        display: inline-flex;
        align-items: center;
        min-height: 30px;
        padding: 0 11px;
        border-radius: 999px;
        background: #eef6eb;
        color: #173f08;
        font-size: 12px;
        font-weight: 900;
        border: 1px solid rgba(23, 63, 8, .08);
    }

    .wn-block-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .wn-drag-handle {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
        background: #fff;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: grab;
        flex-shrink: 0;
        padding: 0;
        transition: border-color .16s ease, color .16s ease, background .16s ease;
    }

    .wn-drag-handle:hover {
        border-color: #173f08;
        color: #173f08;
        background: #eef6eb;
    }

    .wn-drag-handle:active {
        cursor: grabbing;
    }

    .wn-drag-handle svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }

    .wn-sortable-ghost {
        opacity: .42;
    }

    .wn-sortable-chosen {
        box-shadow: 0 16px 34px rgba(15, 23, 42, .16);
        border-color: #94a3b8;
    }

    .wn-sortable-drag {
        transform: rotate(.4deg);
    }

    .wn-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .wn-side-title {
        margin: 0 0 14px;
        font-size: 17px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.025em;
    }

    .wn-side-row {
        padding: 11px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .wn-side-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .wn-side-label {
        font-size: 12px;
        font-weight: 900;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 5px;
    }

    .wn-side-value {
        font-size: 14px;
        color: #111827;
        line-height: 1.65;
        word-break: break-word;
        font-weight: 750;
    }

    .wn-file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        white-space: nowrap;
        border: 0;
    }

    .wn-file-upload {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 14px;
        background: #fff;
        min-height: 58px;
        padding: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .wn-file-upload:hover {
        border-color: #9fb79a;
        background: #fbfdfb;
    }

    .wn-file-upload:focus-within {
        border-color: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .09);
        background: #fff;
    }

    .wn-file-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 0 14px;
        border-radius: 12px;
        background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
        color: #fff;
        font-size: 13px;
        font-weight: 900;
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
        transition: transform .16s ease, box-shadow .16s ease;
    }

    .wn-file-trigger:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(23, 63, 8, .16);
    }

    .wn-file-trigger svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
    }

    .wn-file-name {
        min-width: 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        display: flex;
        align-items: center;
    }

    .wn-file-name span {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* SELECT CUSTOM */
    .wn-select-wrap {
        position: relative;
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 15px;
        background:
            linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%);
        min-height: 58px;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        box-shadow: 0 1px 0 rgba(255,255,255,.75) inset;
    }

    .wn-select-wrap:hover {
        border-color: #9fb79a;
        background:
            linear-gradient(180deg, #ffffff 0%, #f8fbf7 100%);
    }

    .wn-select-wrap:focus-within {
        border-color: #173f08;
        box-shadow:
            0 0 0 4px rgba(23,63,8,.09),
            0 1px 0 rgba(255,255,255,.75) inset;
        background: #fff;
    }

    .wn-select-wrap::before {
        content: '';
        position: absolute;
        top: 50%;
        right: 13px;
        width: 28px;
        height: 28px;
        border-radius: 10px;
        background: #eef6eb;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .wn-select-wrap::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 23px;
        width: 8px;
        height: 8px;
        border-right: 2px solid #173f08;
        border-bottom: 2px solid #173f08;
        transform: translateY(-65%) rotate(45deg);
        pointer-events: none;
    }

    .wn-select-custom {
        width: 100%;
        min-height: 58px;
        border: none;
        outline: none;
        background: transparent;
        padding: 0 56px 0 16px;
        border-radius: 15px;
        font: inherit;
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .wn-select-custom option {
        color: #111827;
        background: #fff;
        font-weight: 700;
        padding: 10px;
    }

    /* DATETIME / CALENDAR CUSTOM */
    .wn-date-wrap {
        position: relative;
        width: 100%;
    }

    .wn-date-input {
        width: 100%;
        min-height: 58px;
        border: 1px solid #d1d5db;
        border-radius: 15px;
        padding: 0 54px 0 16px;
        font: inherit;
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        background:
            linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%);
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        box-sizing: border-box;
        color-scheme: light;
    }

    .wn-date-input:hover {
        border-color: #9fb79a;
        background:
            linear-gradient(180deg, #ffffff 0%, #f8fbf7 100%);
    }

    .wn-date-input:focus {
        border-color: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .09);
        background: #fff;
    }

    .wn-date-icon {
        position: absolute;
        top: 50%;
        right: 13px;
        width: 30px;
        height: 30px;
        border-radius: 10px;
        background: #eef6eb;
        color: #173f08;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .wn-date-icon svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
    }

    .wn-date-input::-webkit-calendar-picker-indicator {
        position: absolute;
        right: 13px;
        width: 30px;
        height: 30px;
        opacity: 0;
        cursor: pointer;
    }

    .wn-featured-preview {
        margin-top: 12px;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #f8fafc;
    }

    .wn-featured-preview-label {
        font-size: 12px;
        font-weight: 900;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 8px;
    }

    .wn-gallery-note {
        margin-top: 8px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.65;
    }

    .hidden {
        display: none !important;
    }

    @media (max-width: 980px) {
        .wn-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .wn-title {
            font-size: 24px;
        }

        .wn-head {
            display: grid;
            gap: 14px;
        }

        .wn-back {
            width: 100%;
        }

        .wn-card {
            padding: 16px;
            border-radius: 18px;
        }

        .wn-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .wn-block-top {
            align-items: flex-start;
        }

        .wn-block-head-left {
            width: 100%;
        }

        .wn-block-actions {
            width: 100%;
        }

        .wn-block-actions .wn-btn {
            width: 100%;
        }

        .wn-builder-actions,
        .wn-builder-actions .wn-btn {
            width: 100%;
        }

        .wn-file-upload {
            align-items: flex-start;
            flex-direction: column;
        }

        .wn-file-trigger {
            width: 100%;
        }

        .wn-file-name {
            width: 100%;
        }

        .wn-actions {
            justify-content: stretch;
        }

        .wn-actions .wn-btn {
            width: 100%;
        }

        .wn-select-custom,
        .wn-date-input {
            min-height: 54px;
            font-size: 14px;
        }

        .wn-select-wrap,
        .wn-date-input {
            border-radius: 14px;
        }
    }
</style>

<div class="wn-page">
    <div class="wn-head">
        <div>
            <h1 class="wn-title">Edit News</h1>
            <div class="wn-subtitle">
                Perbarui konten, susun ulang block dengan drag, dan kirim ulang ke reviewer.
            </div>
        </div>

        <a href="{{ route('writer.news.index') }}" class="wn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/>
                <path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <form
        method="POST"
        action="{{ route('writer.news.update', $news) }}"
        enctype="multipart/form-data"
        id="writer-news-form"
        class="js-confirm-submit"
        data-title="Simpan perubahan news?"
        data-text="Perubahan akan disimpan dan berita akan dikirim ulang ke reviewer."
        data-confirm="Ya, Simpan"
        data-type="save"
        data-icon="question"
    >
        @csrf

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
                            <input type="text" name="id_title" class="wn-input" value="{{ old('id_title', $tId->title ?? '') }}" required>
                        </div>

                        <div class="wn-field full">
                            <label class="wn-label">Slug (ID, optional)</label>
                            <input type="text" name="id_slug" class="wn-input" value="{{ old('id_slug', $tId->slug ?? '') }}">
                            <div class="wn-help">
                                Kosongkan jika ingin slug dibuat otomatis dari judul.
                            </div>
                        </div>

                        <div class="wn-field full">
                            <label class="wn-label">Excerpt (ID)</label>
                            <textarea name="id_excerpt" class="wn-textarea">{{ old('id_excerpt', $tId->excerpt ?? '') }}</textarea>
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

                            <div class="wn-date-wrap">
                                <input
                                    type="datetime-local"
                                    name="published_at"
                                    class="wn-date-input"
                                    value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}"
                                >

                                <span class="wn-date-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 2v4"/>
                                        <path d="M16 2v4"/>
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <path d="M3 10h18"/>
                                    </svg>
                                </span>
                            </div>

                            <div class="wn-help">
                                Jadwal ini akan dipakai saat reviewer approve, kecuali reviewer override manual.
                            </div>
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
                            <button type="button" class="wn-btn wn-btn--add" data-add-block="heading">
                                + Heading
                            </button>
                            <button type="button" class="wn-btn wn-btn--add" data-add-block="text">
                                + Text
                            </button>
                            <button type="button" class="wn-btn wn-btn--add" data-add-block="image">
                                + Image
                            </button>
                        </div>
                    </div>

                    <div class="wn-blocks" id="blocks-wrapper">
                        @forelse($blocks as $i => $block)
                            @php
                                $blockType = $block['type'] ?? 'text';
                            @endphp

                            <div class="wn-block" data-block-item>
                                <div class="wn-block-top">
                                    <div class="wn-block-head-left">
                                        <button type="button" class="wn-drag-handle" title="Tahan lalu geser untuk ubah urutan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                                <path d="M9 5h.01M9 12h.01M9 19h.01M15 5h.01M15 12h.01M15 19h.01"/>
                                            </svg>
                                        </button>

                                        <span class="wn-block-badge">{{ strtoupper($blockType) }}</span>
                                    </div>

                                    <div class="wn-block-actions">
                                        <button type="button" class="wn-btn wn-btn--danger" data-remove-block>Hapus</button>
                                    </div>
                                </div>

                                <input type="hidden" name="blocks[{{ $i }}][type]" value="{{ $blockType }}" data-field="type">

                                <div class="wn-field block-heading {{ $blockType === 'heading' ? '' : 'hidden' }}">
                                    <label class="wn-label">Heading</label>
                                    <input type="text" name="blocks[{{ $i }}][title]" class="wn-input" value="{{ $block['title'] ?? '' }}" data-field="title">
                                </div>

                                <div class="wn-field block-text {{ $blockType === 'text' ? '' : 'hidden' }}">
                                    <label class="wn-label">Paragraph</label>
                                    <textarea name="blocks[{{ $i }}][body]" class="wn-textarea" data-field="body">{{ $block['body'] ?? '' }}</textarea>
                                </div>

                                <div class="wn-field block-image {{ $blockType === 'image' ? '' : 'hidden' }}">
                                    <label class="wn-label">Upload Image</label>
                                    <input type="file" name="block_images[{{ $i }}]" class="wn-input" accept=".jpg,.jpeg,.png,.webp" data-file-field="image">

                                    @if(!empty($block['image']))
                                        <input type="hidden" name="blocks[{{ $i }}][existing_image]" value="{{ $block['image'] }}" data-field="existing_image">
                                        <img src="{{ asset($block['image']) }}" alt="Block image" class="wn-thumb">
                                    @endif
                                </div>

                                <div class="wn-field block-image {{ $blockType === 'image' ? '' : 'hidden' }}" style="margin-bottom:0;">
                                    <label class="wn-label">Caption</label>
                                    <input type="text" name="blocks[{{ $i }}][caption]" class="wn-input" value="{{ $block['caption'] ?? '' }}" data-field="caption">
                                </div>
                            </div>
                        @empty
                            <div class="wn-block" data-block-item>
                                <div class="wn-block-top">
                                    <div class="wn-block-head-left">
                                        <button type="button" class="wn-drag-handle" title="Tahan lalu geser untuk ubah urutan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                                <path d="M9 5h.01M9 12h.01M9 19h.01M15 5h.01M15 12h.01M15 19h.01"/>
                                            </svg>
                                        </button>

                                        <span class="wn-block-badge">TEXT</span>
                                    </div>

                                    <div class="wn-block-actions">
                                        <button type="button" class="wn-btn wn-btn--danger" data-remove-block>Hapus</button>
                                    </div>
                                </div>

                                <input type="hidden" name="blocks[0][type]" value="text" data-field="type">

                                <div class="wn-field block-heading hidden">
                                    <label class="wn-label">Heading</label>
                                    <input type="text" name="blocks[0][title]" class="wn-input" data-field="title">
                                </div>

                                <div class="wn-field block-text">
                                    <label class="wn-label">Paragraph</label>
                                    <textarea name="blocks[0][body]" class="wn-textarea" data-field="body"></textarea>
                                </div>

                                <div class="wn-field block-image hidden">
                                    <label class="wn-label">Upload Image</label>
                                    <input type="file" name="block_images[0]" class="wn-input" accept=".jpg,.jpeg,.png,.webp" data-file-field="image">
                                </div>

                                <div class="wn-field block-image hidden" style="margin-bottom:0;">
                                    <label class="wn-label">Caption</label>
                                    <input type="text" name="blocks[0][caption]" class="wn-input" data-field="caption">
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="wn-actions">
                        <button type="submit" class="wn-btn wn-btn--primary">
                            Simpan & Kirim Ulang ke Reviewer
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <div class="wn-card">
                    <h3 class="wn-side-title">Status Saat Ini</h3>

                    <div class="wn-side-row">
                        <div class="wn-side-label">Status</div>
                        <div class="wn-side-value">
                            {{ ucfirst(str_replace('_', ' ', $news->status ?? 'draft')) }}
                        </div>
                    </div>

                    <div class="wn-side-row">
                        <div class="wn-side-label">Publish Target</div>
                        <div class="wn-side-value">
                            {{ $news->published_at?->format('Y-m-d H:i') ?? '-' }}
                        </div>
                    </div>

                    <div class="wn-side-row">
                        <div class="wn-side-label">Visibility</div>
                        <div class="wn-side-value">
                            {{ $news->is_visible ? 'Visible' : 'Hidden' }}
                        </div>
                    </div>

                    <div class="wn-side-row">
                        <div class="wn-side-label">Reviewer Note</div>
                        <div class="wn-side-value">
                            {{ $news->review_note ?: '-' }}
                        </div>
                    </div>

                    <div class="wn-side-row">
                        <div class="wn-side-label">Terakhir Diperbarui</div>
                        <div class="wn-side-value">
                            {{ $news->updated_at?->format('Y-m-d H:i') ?? '-' }}
                        </div>
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
        if (!wrapper) {
            return;
        }

        const items = wrapper.querySelectorAll('[data-block-item]');

        items.forEach((item, index) => {
            const typeField = item.querySelector('[data-field="type"]');
            const type = typeField ? typeField.value : 'text';

            const badge = item.querySelector('.wn-block-badge');
            if (badge) {
                badge.textContent = type.toUpperCase();
            }

            item.querySelectorAll('[name]').forEach((field) => {
                if (field.hasAttribute('data-file-field')) {
                    field.name = `block_images[${index}]`;
                    return;
                }

                const key = field.getAttribute('data-field');
                if (!key) {
                    return;
                }

                field.name = `blocks[${index}][${key}]`;
            });
        });
    }

    function updateSingleFileName(input, target, emptyText) {
        if (!input || !target) {
            return;
        }

        const file = input.files && input.files[0] ? input.files[0].name : emptyText;
        target.innerHTML = `<span>${file}</span>`;
    }

    function updateMultipleFileName(input, target) {
        if (!input || !target) {
            return;
        }

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

    if (wrapper) {
        wrapper.addEventListener('click', function (e) {
            const removeButton = e.target.closest('[data-remove-block]');

            if (!removeButton) {
                return;
            }

            const item = removeButton.closest('[data-block-item]');

            if (!item) {
                return;
            }

            item.remove();
            reindexBlocks();
        });
    }

    if (wrapper && typeof Sortable !== 'undefined') {
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
    }

    if (form) {
        form.addEventListener('submit', function () {
            reindexBlocks();
        });
    }

    reindexBlocks();
})();
</script>
@endsection