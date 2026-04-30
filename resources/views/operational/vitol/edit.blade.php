@extends('layouts.operational')

@section('title', 'Edit Data VITOL')

@section('content')
<div class="op-page-head">
    <div>
        <div class="op-breadcrumb">
            <span>Operational</span>
            <span>›</span>
            <span>VITOL</span>
            <span>›</span>
            <span>Edit</span>
        </div>
        <h1 class="op-page-title">Edit Data VITOL</h1>
        <p class="op-page-desc">Perbarui data VITOL bulanan.</p>
    </div>
</div>

@if($errors->any())
    <div class="op-alert op-alert--danger">
        <div>
            <div style="font-weight:800;margin-bottom:4px;">Terjadi kesalahan validasi.</div>
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="op-card">
    <div class="op-card-head">
        <div>
            <h2 class="op-card-title">Form Edit VITOL</h2>
            <div class="op-card-desc">Sesuaikan data VITOL bila ada koreksi.</div>
        </div>
    </div>
    <div class="op-card-body">
        <form method="POST" action="{{ route('operational.vitol.update', $record->id) }}">
            @csrf
            @method('PUT')

            <div class="op-form-grid">
                <div class="op-field">
                    <label class="op-label">Tahun</label>
                    <input type="number" name="year" class="op-input" value="{{ old('year', $record->year) }}" required>
                </div>

                <div class="op-field">
                    <label class="op-label">Bulan</label>
                    <select name="month" class="op-select" required>
                        <option value="">Pilih Bulan</option>
                        @foreach($monthOptions as $k => $v)
                            <option value="{{ $k }}" {{ (string)old('month', $record->month) === (string)$k ? 'selected' : '' }}>
                                {{ $v }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="op-field">
                    <label class="op-label">Quantity</label>
                    <input type="number" step="0.0001" name="quantity" class="op-input" value="{{ old('quantity', $record->quantity) }}" required>
                </div>

                <div class="op-field">
                    <label class="op-label">Satuan</label>
                    <select name="unit" class="op-select" required>
                        @foreach($unitOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('unit', $record->unit) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="op-field full">
                    <label class="op-label">Catatan</label>
                    <textarea name="notes" class="op-textarea">{{ old('notes', $record->notes) }}</textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;margin-top:20px;">
                <a href="{{ route('operational.vitol.index') }}" class="op-btn op-btn--soft">Kembali</a>
                <button type="submit" class="op-btn op-btn--primary">Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection