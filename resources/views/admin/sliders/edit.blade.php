@extends('layouts.admin')

@section('content')
<style>
    .admin-form-wrap {
        padding: 28px 32px;
    }

    .admin-form-shell {
        max-width: 760px;
    }

    .admin-page-head {
        margin-bottom: 22px;
    }

    .admin-page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.02em;
    }

    .admin-page-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.6;
    }

    .admin-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
        padding: 22px;
    }

    .admin-alert-error {
        margin-bottom: 16px;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b42318;
        font-size: 14px;
        font-weight: 600;
    }

    .admin-preview-card {
        margin-bottom: 18px;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background: #f3f4f6;
        box-shadow: 0 6px 14px rgba(15, 23, 42, .04);
    }

    .admin-preview-card img {
        width: 100%;
        max-height: 260px;
        object-fit: cover;
        display: block;
    }

    .admin-preview-caption {
        padding: 10px 14px;
        font-size: 12px;
        color: #6b7280;
        background: #fff;
        border-top: 1px solid #e5e7eb;
    }

    .admin-form-grid {
        display: grid;
        gap: 16px;
    }

    .admin-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .admin-label {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
    }

    .admin-input,
    .admin-file {
        width: 100%;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #111827;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        outline: none;
    }

    .admin-input:focus,
    .admin-file:focus {
        border-color: #2f7d32;
        box-shadow: 0 0 0 4px rgba(47, 125, 50, .12);
    }

    .admin-input::placeholder {
        color: #9ca3af;
    }

    .admin-help {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.5;
    }

    .admin-checkbox {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f9fafb;
        width: fit-content;
        cursor: pointer;
        user-select: none;
    }

    .admin-checkbox input {
        width: 16px;
        height: 16px;
        accent-color: #2f7d32;
    }

    .admin-checkbox-text {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .admin-form-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 6px;
    }

    .admin-btn-primary,
    .admin-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: all .18s ease;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .admin-btn-primary {
        background: #173f08;
        color: #fff;
        border-color: #173f08;
        box-shadow: 0 8px 18px rgba(23, 63, 8, .16);
    }

    .admin-btn-primary:hover {
        background: #21560e;
        border-color: #21560e;
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(23, 63, 8, .22);
    }

    .admin-btn-secondary {
        background: #fff;
        color: #374151;
        border-color: #d1d5db;
    }

    .admin-btn-secondary:hover {
        background: #f9fafb;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .admin-form-wrap {
            padding: 20px 16px;
        }

        .admin-page-title {
            font-size: 21px;
        }

        .admin-card {
            padding: 18px;
        }
    }
</style>

<div class="admin-form-wrap">
    <div class="admin-form-shell">
        <div class="admin-page-head">
            <h1 class="admin-page-title">Edit Slider</h1>
            <p class="admin-page-subtitle">
                Perbarui informasi slider, ubah urutan tampil, status aktif, atau ganti gambar jika diperlukan.
            </p>
        </div>

        @if($errors->any())
            <div class="admin-alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="admin-card">
            <div class="admin-preview-card">
                <img src="{{ asset($slider->image_path) }}" alt="{{ $slider->title ?: 'Slider preview' }}">
                <div class="admin-preview-caption">
                    Preview gambar slider saat ini.
                </div>
            </div>

            <form method="POST" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label class="admin-label" for="title">Title</label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ old('title', $slider->title) }}"
                            class="admin-input"
                            placeholder="Masukkan judul slider"
                        >
                    </div>

                    <div class="admin-field">
                        <label class="admin-label" for="link_url">Link URL</label>
                        <input
                            id="link_url"
                            type="text"
                            name="link_url"
                            value="{{ old('link_url', $slider->link_url) }}"
                            class="admin-input"
                            placeholder="Contoh: https://example.com/halaman"
                        >
                    </div>

                    <div class="admin-field">
                        <label class="admin-label" for="sort_order">Sort Order</label>
                        <input
                            id="sort_order"
                            type="number"
                            name="sort_order"
                            value="{{ old('sort_order', $slider->sort_order) }}"
                            class="admin-input"
                        >
                        <div class="admin-help">
                            Angka lebih kecil akan tampil lebih dulu.
                        </div>
                    </div>

                    <div class="admin-field">
                        <label class="admin-label">Status</label>
                        <label class="admin-checkbox">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                            <span class="admin-checkbox-text">Aktif</span>
                        </label>
                    </div>

                    <div class="admin-field">
                        <label class="admin-label" for="image">Ganti Gambar (opsional)</label>
                        <input
                            id="image"
                            type="file"
                            name="image"
                            class="admin-file"
                        >
                        <div class="admin-help">
                            Biarkan kosong jika tidak ingin mengganti gambar yang sekarang.
                        </div>
                    </div>

                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn-primary">Update</button>
                        <a href="{{ route('admin.sliders.index') }}" class="admin-btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection