@extends('layouts.operational')

@section('title', 'Edit Data Flow Gas')

@section('content')
    <div class="op-page-head">
        <div>
            <div class="op-breadcrumb">
                <span>Operational</span>
                <span class="op-breadcrumb-sep">›</span>
                <span>Flow Gas</span>
                <span class="op-breadcrumb-sep">›</span>
                <span>Edit</span>
            </div>
            <h1 class="op-page-title">Edit Data Flow Gas</h1>
            <p class="op-page-desc">
                Perbarui data harian flow gas tanpa mengubah struktur utama sistem operasional.
            </p>
        </div>
    </div>

    @if($errors->any())
        <div class="op-alert op-alert--danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="flex-shrink:0;margin-top:2px">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>
                <div style="font-weight:800; margin-bottom:4px;">Terjadi kesalahan validasi.</div>
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li style="margin:3px 0;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="op-card">
        <div class="op-card-head">
            <div>
                <h2 class="op-card-title">Form Edit Data</h2>
                <div class="op-card-desc">Sesuaikan data harian berdasarkan hasil update lapangan atau koreksi operasional.</div>
            </div>
        </div>
        <div class="op-card-body">
            <form method="POST" action="{{ route('operational.flow-gas.update', $record->id) }}">
                @csrf
                @method('PUT')

                <div class="op-form-grid">
                    <div class="op-field">
                        <label class="op-label">Kategori</label>
                        <select name="flow_gas_category_id" class="op-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string) old('flow_gas_category_id', $record->flow_gas_category_id) === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="op-field">
                        <label class="op-label">Tanggal</label>
                        <input
                            type="date"
                            name="record_date"
                            class="op-input"
                            value="{{ old('record_date', optional($record->record_date)->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="op-field">
                        <label class="op-label">MSCF</label>
                        <input type="number" step="0.0001" name="mscf" class="op-input" value="{{ old('mscf', $record->mscf) }}">
                        <div class="op-help">Volume gas dalam satuan Thousand Standard Cubic Feet.</div>
                    </div>

                    <div class="op-field">
                        <label class="op-label">MMBTU</label>
                        <input type="number" step="0.0001" name="mmbtu" class="op-input" value="{{ old('mmbtu', $record->mmbtu) }}">
                        <div class="op-help">Nilai energi gas dalam satuan Million British Thermal Unit.</div>
                    </div>

                    <div class="op-field full">
                        <label class="op-label">FIX</label>
                        <input type="number" step="0.0001" name="fix" class="op-input" value="{{ old('fix', $record->fix) }}">
                        <div class="op-help">Nilai fix/final bila dipakai di laporan operasional.</div>
                    </div>

                    <div class="op-field full">
                        <label class="op-label">Catatan</label>
                        <textarea name="notes" class="op-textarea">{{ old('notes', $record->notes) }}</textarea>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;margin-top:20px;">
                    <a href="{{ route('operational.flow-gas.index') }}" class="op-btn op-btn--soft">Kembali</a>
                    <button type="submit" class="op-btn op-btn--primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
