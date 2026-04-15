@extends('layouts.admin')

@section('title', 'Tambah Program TJSL')

@section('content')

<style>
.tjsl-form-grid {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 20px;
}
.tjsl-gallery-upload {
    border: 1.5px dashed var(--line);
    border-radius: 14px;
    padding: 16px;
    background: #fafcfb;
}
.tjsl-help {
    margin-top: 6px;
    font-size: 12px;
    color: var(--text3);
}
@media (max-width: 900px) {
    .tjsl-form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>TJSL</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Tambah</span>
        </div>
        <h1 class="a-page-title">Tambah Program TJSL</h1>
        <p class="a-page-desc">Buat program TJSL baru per tahun, lengkap dengan galeri dokumentasi</p>
    </div>
</div>

<form action="{{ route('admin.tjsl.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="tjsl-form-grid">
        <div>
            <div class="a-card">
                <div class="a-card-head">
                    <div>
                        <div class="a-card-title">Informasi Program</div>
                        <div class="a-card-desc">Konten ID akan diterjemahkan otomatis ke EN</div>
                    </div>
                </div>

                <div class="a-card-body">
                    <div class="a-form-group">
                        <label class="a-label">Tahun</label>
                        <input type="text" name="year" class="a-input" value="{{ old('year') }}" placeholder="2024" required>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Judul Program (ID)</label>
                        <input type="text" name="title" class="a-input" value="{{ old('title') }}" required>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Ringkasan (ID)</label>
                        <textarea name="summary" class="a-textarea" rows="4">{{ old('summary') }}</textarea>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Konten Lengkap (ID)</label>
                        <textarea name="content" class="a-textarea" rows="10">{{ old('content') }}</textarea>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Urutan Tampil</label>
                        <input type="number" name="sort_order" class="a-input" value="{{ old('sort_order', 0) }}" min="0">
                    </div>

                    <div class="a-form-group" style="margin-bottom:0">
                        <label style="display:flex;align-items:center;gap:8px;font-weight:600">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            Aktifkan program ini
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="a-card">
                <div class="a-card-head">
                    <div>
                        <div class="a-card-title">Media</div>
                        <div class="a-card-desc">Gambar utama dan galeri kegiatan</div>
                    </div>
                </div>

                <div class="a-card-body">
                    <div class="a-form-group">
                        <label class="a-label">Featured Image</label>
                        <input type="file" name="featured_image" class="a-input" accept=".jpg,.jpeg,.png,.webp">
                        <div class="tjsl-help">Digunakan sebagai banner / cover utama program</div>
                    </div>

                    <div class="a-form-group" style="margin-bottom:0">
                        <label class="a-label">Galeri Foto</label>
                        <div class="tjsl-gallery-upload">
                            <input type="file" name="gallery_images[]" class="a-input" accept=".jpg,.jpeg,.png,.webp" multiple>
                            <div class="tjsl-help">
                                Bisa upload banyak foto sekaligus untuk dokumentasi kegiatan TJSL.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px">
                <button type="submit" class="a-btn a-btn--primary">Simpan Program</button>
                <a href="{{ route('admin.tjsl.index') }}" class="a-btn a-btn--secondary">Kembali</a>
            </div>
        </div>
    </div>
</form>

@endsection