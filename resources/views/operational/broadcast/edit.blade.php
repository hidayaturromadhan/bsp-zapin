@extends('layouts.operational')

@section('title', 'Edit Broadcast TV')

@section('content')
    <div class="op-page-head">
        <div>
            <div class="op-breadcrumb">
                <span>Operational</span>
                <span class="op-breadcrumb-sep">/</span>
                <a href="{{ route('operational.broadcast.index') }}">Broadcast TV</a>
                <span class="op-breadcrumb-sep">/</span>
                <span>Edit</span>
            </div>
            <h1 class="op-page-title">Edit Broadcast TV</h1>
            <p class="op-page-desc">Perbarui item running text sesuai kebutuhan.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="op-alert op-alert--danger">
            <div>
                <strong>Terjadi kesalahan.</strong>
                <div style="margin-top:6px;">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="op-card">
        <div class="op-card-head">
            <div>
                <h2 class="op-card-title">Form Edit Broadcast</h2>
                <p class="op-card-desc">Perbarui data broadcast lalu simpan.</p>
            </div>
        </div>

        <div class="op-card-body">
            <form method="POST" action="{{ route('operational.broadcast.update', $record) }}" class="op-form-grid">
                @csrf
                @method('PUT')

                <div class="op-field">
                    <label class="op-label">Label</label>
                    <input type="text" name="label" class="op-input" value="{{ old('label', $record->label) }}" placeholder="Contoh: Safety">
                </div>

                <div class="op-field">
                    <label class="op-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="op-input" value="{{ old('sort_order', $record->sort_order) }}" min="0">
                </div>

                <div class="op-field full">
                    <label class="op-label">Isi Broadcast</label>
                    <textarea name="message" class="op-textarea" placeholder="Masukkan isi pesan broadcast...">{{ old('message', $record->message) }}</textarea>
                </div>

                <div class="op-field">
                    <label class="op-label">Mulai Tampil</label>
                    <input type="datetime-local" name="starts_at" class="op-input" value="{{ old('starts_at', optional($record->starts_at)->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="op-field">
                    <label class="op-label">Selesai Tampil</label>
                    <input type="datetime-local" name="ends_at" class="op-input" value="{{ old('ends_at', optional($record->ends_at)->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="op-field full">
                    <label style="display:flex; align-items:center; gap:10px; font-weight:700;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $record->is_active) ? 'checked' : '' }}>
                        Aktifkan broadcast ini
                    </label>
                </div>

                <div class="op-field full">
                    <div class="op-actions">
                        <button type="submit" class="op-btn op-btn--primary">Update Broadcast</button>
                        <a href="{{ route('operational.broadcast.index') }}" class="op-btn op-btn--soft">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection