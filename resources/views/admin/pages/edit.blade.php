@extends('layouts.admin')

@section('content')
<div class="container" style="max-width:980px;">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <h1 style="margin:0;">Edit Halaman</h1>
            <div style="margin-top:6px; color:#6b7280; font-size:14px;">
                Editor cukup mengubah konten Bahasa Indonesia. Sistem akan membuat versi English otomatis saat disimpan.
            </div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('admin.pages.versions', $page) }}">Versions</a>

            @if($tId?->slug)
                <a target="_blank" href="{{ route('page.show', ['locale' => 'id', 'slug' => $tId->slug]) }}">Preview ID</a>
            @endif

            @if($tEn?->slug)
                <a target="_blank" href="{{ route('page.show', ['locale' => 'en', 'slug' => $tEn->slug]) }}">Preview EN</a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="margin:12px 0; color:green;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div style="margin:12px 0; color:#b00020;">
            {{ $errors->first() }}
        </div>
    @endif

    @if($page->cover_image)
        <div style="margin:10px 0;">
            <img src="{{ asset($page->cover_image) }}" style="width:100%; max-height:280px; object-fit:cover; border-radius:12px;">
        </div>
    @endif

    <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display:flex; gap:18px; flex-wrap:wrap;">
            <div style="flex:1; min-width:320px; border:1px solid #eee; padding:14px; border-radius:10px;">
                <h3 style="margin-top:0;">Bahasa Indonesia (ID)</h3>

                <div style="margin:10px 0;">
                    <label>Title (ID) *</label>
                    <input type="text" name="id_title" value="{{ old('id_title', $tId->title) }}" required style="width:100%;">
                </div>

                <div style="margin:10px 0;">
                    <label>Slug (ID) *</label>
                    <input type="text" name="id_slug" value="{{ old('id_slug', $tId->slug) }}" required style="width:100%;">
                    <small>contoh: operasional, hubungan-investor</small>
                </div>

                <div style="margin:10px 0;">
                    <label>Content (ID)</label>
                    <textarea name="id_content" rows="14" style="width:100%;">{{ old('id_content', $tId->content) }}</textarea>
                </div>
            </div>

            <div style="flex:1; min-width:320px; border:1px solid #eee; padding:14px; border-radius:10px; background:#fafafa;">
                <h3 style="margin-top:0;">English (EN)</h3>
                <div style="margin-bottom:12px; padding:10px 12px; border:1px solid #dbeafe; background:#eff6ff; color:#1d4ed8; border-radius:8px; font-size:13px;">
                    Kolom English dibuat otomatis dari Bahasa Indonesia setelah tombol update disimpan.
                </div>

                <div style="margin:10px 0;">
                    <label>Title (EN)</label>
                    <input
                        type="text"
                        value="{{ $tEn->title }}"
                        readonly
                        style="width:100%; background:#f3f4f6; color:#374151;"
                    >
                </div>

                <div style="margin:10px 0;">
                    <label>Slug (EN)</label>
                    <input
                        type="text"
                        value="{{ $tEn->slug }}"
                        readonly
                        style="width:100%; background:#f3f4f6; color:#374151;"
                    >
                    <small>otomatis dibuat dari hasil title English</small>
                </div>

                <div style="margin:10px 0;">
                    <label>Content (EN)</label>
                    <textarea
                        rows="14"
                        readonly
                        style="width:100%; background:#f3f4f6; color:#374151;"
                    >{{ $tEn->content }}</textarea>
                </div>
            </div>
        </div>

        <hr style="margin:18px 0;">

        <div style="margin:10px 0;">
            <label>Cover Image (Global, opsional)</label>
            <input type="file" name="cover_image">
        </div>

        <div style="margin:10px 0;">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                Aktif
            </label>
        </div>

        <button type="submit">Update</button>
        <a href="{{ route('admin.pages.index') }}" style="margin-left:10px;">Kembali</a>
    </form>
</div>
@endsection