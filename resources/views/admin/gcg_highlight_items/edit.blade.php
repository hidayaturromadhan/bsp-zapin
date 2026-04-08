@extends('layouts.admin')

@section('title', 'Edit Highlight GCG')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <a href="{{ route('admin.gcg-highlight-items.index') }}" style="color:var(--text3)">GCG Highlight</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Edit</span>
        </div>
        <h1 class="a-page-title">Edit Highlight GCG</h1>
        <p class="a-page-desc">Cukup ubah Label Indonesia. Versi English akan diperbarui otomatis.</p>
    </div>

    <a href="{{ route('admin.gcg-highlight-items.index') }}" class="a-btn a-btn--secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali
    </a>
</div>

@if ($errors->any())
    <div class="a-alert a-alert--error">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
        <ul style="margin:0;padding-left:16px">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.gcg-highlight-items.update', $gcgHighlightItem) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Informasi Highlight</div>
                <div class="a-card-desc">Label English tidak perlu diinput manual.</div>
            </div>
        </div>

        <div class="a-card-body">
            <div class="a-form-group">
                <label class="a-label">
                    Label Indonesia
                    <span style="color:#dc2626">*</span>
                </label>
                <input
                    type="text"
                    name="label_id"
                    class="a-input"
                    value="{{ old('label_id', $gcgHighlightItem->label_id) }}"
                    required
                >
            </div>

            <div class="a-form-group">
                <label class="a-label">Preview English Saat Ini</label>
                <input
                    type="text"
                    class="a-input"
                    value="{{ $gcgHighlightItem->label_en }}"
                    disabled
                >
            </div>

            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Urutan</label>
                <input
                    type="number"
                    name="sort_order"
                    class="a-input"
                    value="{{ old('sort_order', $gcgHighlightItem->sort_order) }}"
                    min="0"
                >
            </div>
        </div>
    </div>

    <div class="a-card">
        <div class="a-card-head">
            <div class="a-card-title">⚙️ Pengaturan</div>
        </div>

        <div class="a-card-body">
            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    {{ old('is_active', $gcgHighlightItem->is_active) ? 'checked' : '' }}
                    style="width:18px;height:18px;accent-color:var(--g500)"
                >
                <span class="a-label" style="margin:0">Aktifkan highlight</span>
            </label>
        </div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button type="submit" class="a-btn a-btn--primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
            Update Highlight
        </button>

        <a href="{{ route('admin.gcg-highlight-items.index') }}" class="a-btn a-btn--secondary">
            Batal
        </a>
    </div>
</form>

@endsection