@extends('layouts.operational')

@section('title', 'Tambah Data Crude')

@section('content')
<div class="op-page-head">
    <div>
        <div class="op-breadcrumb">
            <span>Operational</span>
            <span>›</span>
            <span>Crude</span>
            <span>›</span>
            <span>Tambah</span>
        </div>

        <h1 class="op-page-title">Tambah Data Produksi Crude</h1>
        <p class="op-page-desc">
            Input data produksi crude harian berdasarkan Vacuum Truck dan Road Tank.
        </p>
    </div>
</div>

@if($errors->any())
    <div class="op-alert op-alert--danger">
        <div>
            <div style="font-weight:800;margin-bottom:4px;">
                Terjadi kesalahan validasi.
            </div>

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
            <h2 class="op-card-title">Form Input Crude</h2>
            <div class="op-card-desc">
                Isi tanggal, nilai Vacuum Truck, Road Tank, dan catatan jika diperlukan.
            </div>
        </div>
    </div>

    <div class="op-card-body">
        <form method="POST" action="{{ route('operational.crude.store') }}">
            @csrf

            <div class="op-form-grid">
                <div class="op-field">
                    <label class="op-label">Tanggal</label>
                    <input
                        type="date"
                        name="record_date"
                        class="op-input"
                        value="{{ old('record_date', $defaultDate) }}"
                        required
                    >
                </div>

                <div class="op-field">
                    <label class="op-label">Vacuum Truck</label>
                    <input
                        type="number"
                        step="0.0001"
                        min="0"
                        name="vacuum_truck"
                        class="op-input"
                        value="{{ old('vacuum_truck') }}"
                        placeholder="Contoh: 3244.80"
                        required
                    >
                </div>

                <div class="op-field">
                    <label class="op-label">Road Tank</label>
                    <input
                        type="number"
                        step="0.0001"
                        min="0"
                        name="road_tank"
                        class="op-input"
                        value="{{ old('road_tank') }}"
                        placeholder="Contoh: 1487.86"
                        required
                    >
                </div>

                <div class="op-field full">
                    <label class="op-label">Catatan</label>
                    <textarea
                        name="notes"
                        class="op-textarea"
                        placeholder="Tambahkan catatan jika ada..."
                    >{{ old('notes') }}</textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;margin-top:20px;">
                <a href="{{ route('operational.crude.index') }}" class="op-btn op-btn--soft">
                    Batal
                </a>

                <button type="submit" class="op-btn op-btn--primary">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection