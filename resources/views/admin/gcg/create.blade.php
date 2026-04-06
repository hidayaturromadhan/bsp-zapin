@extends('layouts.admin')

@section('title', 'Tambah Kategori GCG')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <a href="{{ route('admin.gcg.index') }}" style="color:var(--text3)">GCG</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Tambah Kategori</span>
        </div>
        <h1 class="a-page-title">Tambah Kategori GCG</h1>
        <p class="a-page-desc">Terjemahan ke Bahasa Inggris otomatis via DeepL</p>
    </div>
    <a href="{{ route('admin.gcg.index') }}" class="a-btn a-btn--secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali
    </a>
</div>

@if($errors->any())
    <div class="a-alert a-alert--error">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
        <ul style="margin:0;padding-left:16px">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.gcg.store') }}" method="POST">
    @csrf

    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Informasi Kategori</div>
                <div class="a-card-desc">Isi dalam Bahasa Indonesia, EN otomatis diterjemahkan</div>
            </div>
        </div>
        <div class="a-card-body">
            <div class="a-form-group">
                <label class="a-label">
                    Nama Kategori
                    <span style="color:#dc2626">*</span>
                </label>
                <input type="text" name="name" class="a-input"
                       value="{{ old('name') }}"
                       placeholder="Contoh: Kebijakan Tata Kelola"
                       required>
            </div>
            <div class="a-form-group" style="margin-bottom:0">
                <label class="a-label">
                    Deskripsi
                    <span class="a-label-hint">(opsional)</span>
                </label>
                <textarea name="description" class="a-textarea" rows="5"
                          placeholder="Tuliskan deskripsi singkat kategori ini...">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div class="a-card">
        <div class="a-card-head">
            <div class="a-card-title">⚙️ Pengaturan</div>
        </div>
        <div class="a-card-body">
            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       style="width:18px;height:18px;accent-color:var(--g500)">
                <span class="a-label" style="margin:0">Tampilkan di website</span>
            </label>
        </div>
    </div>

    <div style="display:flex;gap:10px">
        <button type="submit" class="a-btn a-btn--primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
            Simpan Kategori
        </button>
        <a href="{{ route('admin.gcg.index') }}" class="a-btn a-btn--secondary">Batal</a>
    </div>
</form>

@endsection