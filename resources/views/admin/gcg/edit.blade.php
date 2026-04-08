@extends('layouts.admin')

@section('title', 'Edit Kategori GCG')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <a href="{{ route('admin.gcg.index') }}" style="color:var(--text3)">GCG</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Edit Kategori</span>
        </div>
        <h1 class="a-page-title">Edit Kategori GCG</h1>
        <p class="a-page-desc">Kelola kategori, dokumen, dan cover dokumen.</p>
    </div>

    <a href="{{ route('admin.gcg.index') }}" class="a-btn a-btn--secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali
    </a>
</div>

@if(session('success'))
    <div class="a-alert a-alert--success">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="a-alert a-alert--error">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
        <ul style="margin:0;padding-left:16px">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- =========================
    FORM EDIT KATEGORI
========================= --}}
<form action="{{ route('admin.gcg.update', $gcg) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Informasi Kategori</div>
                <div class="a-card-desc">Perubahan Bahasa Inggris akan diperbarui otomatis.</div>
            </div>
        </div>

        <div class="a-card-body">
            <div class="a-form-group">
                <label class="a-label">
                    Nama Kategori
                    <span style="color:#dc2626">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    class="a-input"
                    value="{{ old('name', $translationId->name ?? '') }}"
                    placeholder="Contoh: Kebijakan Tata Kelola"
                    required
                >
            </div>

            <div class="a-form-group" style="margin-bottom:18px">
                <label class="a-label">
                    Deskripsi
                    <span class="a-label-hint">(opsional)</span>
                </label>
                <textarea
                    name="description"
                    class="a-textarea"
                    rows="5"
                    placeholder="Tuliskan deskripsi singkat kategori ini..."
                >{{ old('description', $translationId->description ?? '') }}</textarea>
            </div>

            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    {{ old('is_active', $gcg->is_active) ? 'checked' : '' }}
                    style="width:18px;height:18px;accent-color:var(--g500)"
                >
                <span class="a-label" style="margin:0">Tampilkan di website</span>
            </label>
        </div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px">
        <button type="submit" class="a-btn a-btn--primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
            Update Kategori
        </button>

        <a href="{{ route('admin.gcg.index') }}" class="a-btn a-btn--secondary">
            Batal
        </a>
    </div>
</form>

{{-- =========================
    FORM UPLOAD DOKUMEN
========================= --}}
<form action="{{ route('admin.gcg.documents.store', $gcg) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Upload Dokumen Baru</div>
                <div class="a-card-desc">Upload PDF. Cover bisa manual atau otomatis dibuat dari halaman pertama PDF.</div>
            </div>
        </div>

        <div class="a-card-body">
            <div class="a-form-group">
                <label class="a-label">
                    Judul Dokumen
                    <span style="color:#dc2626">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    class="a-input"
                    value="{{ old('title') }}"
                    placeholder="Contoh: Pedoman Tata Kelola Perusahaan"
                    required
                >
            </div>

            <div class="a-form-group">
                <label class="a-label">
                    File Dokumen
                    <span style="color:#dc2626">*</span>
                </label>
                <input
                    type="file"
                    name="file"
                    class="a-input"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                    required
                >
            </div>

            <div class="a-form-group">
                <label class="a-label">
                    Cover
                    <span class="a-label-hint">(opsional)</span>
                </label>
                <input
                    type="file"
                    name="cover"
                    class="a-input"
                    accept=".jpg,.jpeg,.png,.webp"
                >
            </div>

            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:18px">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}
                    style="width:18px;height:18px;accent-color:var(--g500)"
                >
                <span class="a-label" style="margin:0">Dokumen aktif</span>
            </label>

            <div>
                <button type="submit" class="a-btn a-btn--primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Upload Dokumen
                </button>
            </div>
        </div>
    </div>
</form>

{{-- =========================
    LIST DOKUMEN
========================= --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Dokumen</div>
            <div class="a-card-desc">Kelola judul, status, cover, dan hapus dokumen.</div>
        </div>
    </div>

    <div class="a-card-body">

        @forelse($gcg->documents as $doc)
            @php
                $docTrId = $doc->translations->firstWhere('locale', 'id') ?? $doc->translations->first();
                $coverUrl = $doc->cover ? asset('images/gcg/' . $doc->cover) : null;
                $fileUrl = asset('documents/gcg/' . $doc->file_path);
                $fileType = strtoupper($doc->file_type ?? 'FILE');
                $fileSizeMb = $doc->file_size ? number_format($doc->file_size / 1024 / 1024, 2) . ' MB' : '-';
            @endphp

            <div class="a-card" style="margin-bottom:18px;border:1px solid var(--line2);box-shadow:none;">
                <div class="a-card-body">
                    <div style="display:grid;grid-template-columns:220px 1fr;gap:22px;align-items:start;">
                        
                        {{-- COVER --}}
                        <div>
                            <div style="
                                width:100%;
                                aspect-ratio:3/4;
                                border-radius:14px;
                                overflow:hidden;
                                border:1px solid var(--line);
                                background:var(--line2);
                                display:flex;
                                align-items:center;
                                justify-content:center;
                            ">
                                @if($coverUrl)
                                    <img src="{{ $coverUrl }}" alt="{{ $docTrId->title ?? 'Cover dokumen' }}"
                                         style="width:100%;height:100%;object-fit:cover;display:block;">
                                @else
                                    <div style="padding:16px;text-align:center;color:var(--text3);">
                                        <div style="
                                            width:54px;height:54px;border-radius:14px;
                                            background:#204712;color:#fff;
                                            display:flex;align-items:center;justify-content:center;
                                            font-weight:800;font-size:13px;letter-spacing:.08em;
                                            margin:0 auto 10px;
                                        ">
                                            {{ $fileType }}
                                        </div>
                                        <div style="font-size:12.5px;line-height:1.5;">
                                            Belum ada cover
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
                                <span class="a-badge {{ $doc->is_active ? 'a-badge--green' : 'a-badge--red' }}">
                                    {{ $doc->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <span class="a-badge a-badge--blue">{{ $fileType }}</span>
                                <span class="a-badge a-badge--gray">{{ $fileSizeMb }}</span>
                            </div>

                            <div style="margin-top:10px;">
                                <a href="{{ $fileUrl }}" target="_blank" class="a-btn a-btn--secondary a-btn--sm">
                                    Lihat File
                                </a>
                            </div>
                        </div>

                        {{-- FORM UPDATE DOKUMEN --}}
                        <div>
                            <form action="{{ route('admin.gcg.documents.update', [$gcg, $doc]) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="a-form-group">
                                    <label class="a-label">
                                        Judul Dokumen
                                        <span style="color:#dc2626">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="title"
                                        class="a-input"
                                        value="{{ old('title', $docTrId->title ?? '') }}"
                                        required
                                    >
                                </div>

                                <div class="a-form-group">
                                    <label class="a-label">
                                        Ganti Cover
                                        <span class="a-label-hint">(opsional)</span>
                                    </label>
                                    <input
                                        type="file"
                                        name="cover"
                                        class="a-input"
                                        accept=".jpg,.jpeg,.png,.webp"
                                    >
                                </div>

                                <div class="a-form-group">
                                    <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer">
                                        <input
                                            type="checkbox"
                                            name="is_active"
                                            value="1"
                                            {{ old('is_active', $doc->is_active) ? 'checked' : '' }}
                                            style="width:18px;height:18px;accent-color:var(--g500)"
                                        >
                                        <span class="a-label" style="margin:0">Dokumen aktif</span>
                                    </label>
                                </div>

                                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                                    <button type="submit" class="a-btn a-btn--primary a-btn--sm">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                            <polyline points="17 21 17 13 7 13 7 21"/>
                                            <polyline points="7 3 7 8 15 8"/>
                                        </svg>
                                        Update Dokumen
                                    </button>
                            </form>

                                    <form action="{{ route('admin.gcg.documents.destroy', [$gcg, $doc]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="a-btn a-btn--danger a-btn--sm">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                <path d="M10 11v6"/>
                                                <path d="M14 11v6"/>
                                                <path d="M9 6V4h6v2"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="a-empty">
                <div class="a-empty-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div class="a-empty-title">Belum ada dokumen</div>
                <div class="a-empty-desc">Upload dokumen pertama untuk kategori GCG ini.</div>
            </div>
        @endforelse

    </div>
</div>

@endsection