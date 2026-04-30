@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Update Status Laporan WBS</h2>

    <div class="wbs-grid wbs-grid-2">
        <div class="wbs-card">
            <h3 class="wbs-card-title">Data Laporan</h3>

            <div class="wbs-meta-grid">
                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">No. Laporan</div>
                    <div class="wbs-meta-item-value">{{ $report->report_number }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Pelapor</div>
                    <div class="wbs-meta-item-value">
                        {{ $report->user->name ?? '-' }}<br>
                        <span style="color:#64748b;">{{ $report->user->email ?? '-' }}</span>
                    </div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Kategori</div>
                    <div class="wbs-meta-item-value">{{ $report->category_label }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Judul</div>
                    <div class="wbs-meta-item-value">{{ $report->title }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Pokok Masalah</div>
                    <div class="wbs-meta-item-value">{{ $report->description }}</div>
                </div>
            </div>
        </div>

        <div class="wbs-card">
            <h3 class="wbs-card-title">Status & Catatan Admin</h3>

        <form action="{{ route('wbs.admin.reports.update', $report->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="status">Status</label>

                <div class="wbs-custom-select">
                    <select name="status" id="status" required>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $report->status) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <span class="wbs-select-chevron" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="admin_notes">Catatan Admin</label>
                <textarea name="admin_notes" id="admin_notes" class="textarea">{{ old('admin_notes', $report->admin_notes) }}</textarea>
            </div>

            <div class="form-group">
                <label for="follow_up_result">Hasil Tindak Lanjut</label>
                <textarea name="follow_up_result" id="follow_up_result" class="textarea">{{ old('follow_up_result', $report->follow_up_result) }}</textarea>
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button type="submit" class="wbs-btn wbs-btn-primary">Simpan Perubahan</button>
                <a href="{{ route('wbs.admin.reports.show', $report->id) }}" class="wbs-btn wbs-btn-light">Kembali</a>
            </div>
        </form>
        </div>
    </div>
@endsection