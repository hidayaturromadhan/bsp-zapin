@extends('layouts.admin')

@section('title', 'Tambah Investor Highlight')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Investor Highlight</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Tambah</span>
        </div>
        <h1 class="a-page-title">Tambah Investor Highlight</h1>
    </div>
</div>

<form action="{{ route('admin.investor-highlight-items.store') }}" method="POST">
    @csrf

    <div class="a-card">
        <div class="a-card-body">
            <div class="a-form-group">
                <label class="a-label">Label ID</label>
                <input type="text" name="label_id" class="a-input" value="{{ old('label_id') }}" required>
            </div>

            <div class="a-form-group">
                <label class="a-label">Urutan</label>
                <input type="number" name="sort_order" class="a-input" value="{{ old('sort_order', 0) }}" min="0">
            </div>

            <div class="a-form-group" style="margin-bottom:0">
                <label style="display:flex;align-items:center;gap:8px;font-weight:600">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    Aktif
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:10px">
        <button type="submit" class="a-btn a-btn--primary">Simpan</button>
        <a href="{{ route('admin.investor-highlight-items.index') }}" class="a-btn a-btn--secondary">Kembali</a>
    </div>
</form>

@endsection