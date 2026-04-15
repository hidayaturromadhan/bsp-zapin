@extends('layouts.admin')

@section('title', 'Edit Program TJSL')

@section('content')

@php
    $trId = $program->translations->firstWhere('locale', 'id');
    $trEn = $program->translations->firstWhere('locale', 'en');
@endphp

<style>
.tjsl-form-grid {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 20px;
}
.tjsl-gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 12px;
}
.tjsl-gallery-item {
    position: relative;
    border: 1px solid var(--line);
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}
.tjsl-gallery-item img {
    width: 100%;
    height: 110px;
    object-fit: cover;
    display: block;
}
.tjsl-gallery-item-body {
    padding: 10px;
}
.tjsl-gallery-item form {
    margin-top: 8px;
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
            <span>Edit</span>
        </div>
        <h1 class="a-page-title">Edit Program TJSL</h1>
        <p class="a-page-desc">Perbarui informasi, media utama, dan dokumentasi galeri</p>
    </div>
</div>

<form action="{{ route('admin.tjsl.update', $program) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="tjsl-form-grid">
        <div>
            <div class="a-card">
                <div class="a-card-head">
                    <div>
                        <div class="a-card-title">Informasi Program</div>
                        <div class="a-card-desc">Versi EN ditampilkan sebagai hasil auto translate</div>
                    </div>
                </div>

                <div class="a-card-body">
                    <div class="a-form-group">
                        <label class="a-label">Tahun</label>
                        <input type="text" name="year" class="a-input" value="{{ old('year', $program->year) }}" required>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Judul Program (ID)</label>
                        <input type="text" name="title" class="a-input" value="{{ old('title', $trId->title ?? '') }}" required>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Judul Program (EN - Auto)</label>
                        <input type="text" class="a-input" value="{{ $trEn->title ?? '-' }}" readonly>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Ringkasan (ID)</label>
                        <textarea name="summary" class="a-textarea" rows="4">{{ old('summary', $trId->summary ?? '') }}</textarea>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Konten Lengkap (ID)</label>
                        <textarea name="content" class="a-textarea" rows="10">{{ old('content', $trId->content ?? '') }}</textarea>
                    </div>

                    <div class="a-form-group">
                        <label class="a-label">Urutan Tampil</label>
                        <input type="number" name="sort_order" class="a-input" value="{{ old('sort_order', $program->sort_order) }}" min="0">
                    </div>

                    <div class="a-form-group" style="margin-bottom:0">
                        <label style="display:flex;align-items:center;gap:8px;font-weight:600">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $program->is_active) ? 'checked' : '' }}>
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
                        <div class="a-card-title">Featured Image</div>
                        <div class="a-card-desc">Ganti gambar utama jika diperlukan</div>
                    </div>
                </div>

                <div class="a-card-body">
                    @if($program->featured_image)
                        <div class="a-form-group">
                            <img src="{{ asset($program->featured_image) }}"
                                 alt="Featured"
                                 style="width:100%;max-height:250px;object-fit:cover;border-radius:12px;border:1px solid var(--line);">
                        </div>
                    @endif

                    <div class="a-form-group" style="margin-bottom:0">
                        <label class="a-label">Ganti Featured Image</label>
                        <input type="file" name="featured_image" class="a-input" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                </div>
            </div>

            <div class="a-card">
                <div class="a-card-head">
                    <div>
                        <div class="a-card-title">Tambah Galeri Baru</div>
                        <div class="a-card-desc">Upload banyak foto sekaligus</div>
                    </div>
                </div>

                <div class="a-card-body">
                    <div class="a-form-group" style="margin-bottom:0">
                        <input type="file" name="gallery_images[]" class="a-input" accept=".jpg,.jpeg,.png,.webp" multiple>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px">
                <button type="submit" class="a-btn a-btn--primary">Update Program</button>
                <a href="{{ route('admin.tjsl.index') }}" class="a-btn a-btn--secondary">Kembali</a>
            </div>
        </div>
    </div>
</form>

@if($program->images->count())
    <div class="a-card" style="margin-top:20px">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Galeri TJSL</div>
                <div class="a-card-desc">{{ $program->images->count() }} foto dokumentasi</div>
            </div>
        </div>

        <div class="a-card-body">
            <div class="tjsl-gallery-grid">
                @foreach($program->images as $image)
                    <div class="tjsl-gallery-item">
                        <img src="{{ asset($image->image_path) }}" alt="TJSL image">
                        <div class="tjsl-gallery-item-body">
                            <div style="font-size:12px;color:var(--text3)">
                                Urutan: {{ $image->sort_order }}
                            </div>

                            @if(Route::has('admin.tjsl.images.destroy'))
                                <form action="{{ route('admin.tjsl.images.destroy', [$program, $image]) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="a-btn a-btn--danger a-btn--sm" style="width:100%">
                                        Hapus Foto
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@endsection