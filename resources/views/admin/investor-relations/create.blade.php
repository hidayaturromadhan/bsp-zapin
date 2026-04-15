@extends('layouts.admin')

@section('title', 'Tambah Dokumen Investor')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Hubungan Investor</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Tambah Dokumen</span>
        </div>
        <h1 class="a-page-title">Tambah Dokumen Investor</h1>
        <p class="a-page-desc">Upload laporan tahunan / dokumen investor relations</p>
    </div>
</div>

<form action="{{ route('admin.investor-relations.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Informasi Dokumen</div>
                <div class="a-card-desc">Data ID akan diterjemahkan otomatis ke EN</div>
            </div>
        </div>

        <div class="a-card-body">
            <div class="a-form-group">
                <label class="a-label">Judul Dokumen</label>
                <input type="text" name="title" class="a-input" value="{{ old('title') }}" required>
            </div>

            <div class="a-form-group">
                <label class="a-label">Ringkasan <span class="a-label-hint">(opsional)</span></label>
                <textarea name="summary" class="a-textarea">{{ old('summary') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="a-form-group">
                    <label class="a-label">Tahun</label>
                    <input type="text" name="year" class="a-input" value="{{ old('year') }}" placeholder="2024">
                </div>

                <div class="a-form-group">
                    <label class="a-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="a-input" value="{{ old('sort_order', 0) }}" min="0">
                </div>
            </div>

            <div class="a-form-group">
                <label class="a-label">File PDF</label>
                <input type="file" name="file" class="a-input" accept=".pdf" required>
            </div>

            <div class="a-form-group">
                <label class="a-label">Cover Manual <span class="a-label-hint">(opsional)</span></label>
                <input type="file" name="cover" class="a-input" accept=".jpg,.jpeg,.png,.webp">
                <div style="font-size:12px;color:var(--text3);margin-top:6px">
                    Jika kosong, cover akan dibuat otomatis dari halaman pertama PDF.
                </div>
            </div>

            <div class="a-form-group" style="margin-bottom:0">
                <label style="display:flex;align-items:center;gap:8px;font-weight:600">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    Aktifkan dokumen ini
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:10px">
        <button type="submit" class="a-btn a-btn--primary">Simpan Dokumen</button>
        <a href="{{ route('admin.investor-relations.index') }}" class="a-btn a-btn--secondary">Kembali</a>
    </div>
</form>

@endsection