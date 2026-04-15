@extends('layouts.admin')

@section('title', 'Edit Dokumen Investor')

@section('content')

@php
    $trId = $document->translations->firstWhere('locale', 'id');
    $trEn = $document->translations->firstWhere('locale', 'en');
@endphp

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Hubungan Investor</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Edit Dokumen</span>
        </div>
        <h1 class="a-page-title">Edit Dokumen Investor</h1>
        <p class="a-page-desc">Perbarui judul, tahun, cover, dan status dokumen</p>
    </div>
</div>

<form action="{{ route('admin.investor-relations.update', $document) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Informasi Dokumen</div>
                <div class="a-card-desc">Bahasa EN diperbarui otomatis dari data ID</div>
            </div>
        </div>

        <div class="a-card-body">
            <div class="a-form-group">
                <label class="a-label">Judul Dokumen (ID)</label>
                <input type="text" name="title" class="a-input" value="{{ old('title', $trId->title ?? '') }}" required>
            </div>

            <div class="a-form-group">
                <label class="a-label">Ringkasan (ID)</label>
                <textarea name="summary" class="a-textarea">{{ old('summary', $trId->summary ?? '') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="a-form-group">
                    <label class="a-label">Tahun</label>
                    <input type="text" name="year" class="a-input" value="{{ old('year', $document->year) }}">
                </div>

                <div class="a-form-group">
                    <label class="a-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="a-input" value="{{ old('sort_order', $document->sort_order) }}" min="0">
                </div>
            </div>

            <div class="a-form-group">
                <label class="a-label">Judul EN (Auto)</label>
                <input type="text" class="a-input" value="{{ $trEn->title ?? '-' }}" readonly>
            </div>

            <div class="a-form-group">
                <label class="a-label">Ganti Cover</label>
                <input type="file" name="cover" class="a-input" accept=".jpg,.jpeg,.png,.webp">
            </div>

            @if($document->cover)
                <div class="a-form-group">
                    <label class="a-label">Cover Saat Ini</label>
                    <img src="{{ asset('images/investor-relations/' . $document->cover) }}"
                         alt="Cover"
                         style="width:180px;border-radius:12px;border:1px solid var(--line)">
                </div>
            @endif

            <div class="a-form-group" style="margin-bottom:0">
                <label style="display:flex;align-items:center;gap:8px;font-weight:600">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $document->is_active) ? 'checked' : '' }}>
                    Aktifkan dokumen ini
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:10px">
        <button type="submit" class="a-btn a-btn--primary">Update Dokumen</button>
        <a href="{{ route('admin.investor-relations.index') }}" class="a-btn a-btn--secondary">Kembali</a>
    </div>
</form>

@endsection