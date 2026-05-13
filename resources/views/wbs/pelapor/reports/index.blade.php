@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Laporan Saya</h2>

    <style>
        .wbs-pagination {
            margin-top: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .wbs-pagination-info {
            color: #64748b;
            font-size: 14px;
        }

        .wbs-pagination-links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .wbs-pagination-link,
        .wbs-pagination-disabled,
        .wbs-pagination-active {
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid #e2e8f0;
        }

        .wbs-pagination-link {
            background: #ffffff;
            color: #334155;
        }

        .wbs-pagination-link:hover {
            background: #f8fafc;
        }

        .wbs-pagination-active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .wbs-pagination-disabled {
            background: #f8fafc;
            color: #94a3b8;
            cursor: not-allowed;
        }

        /*
        |--------------------------------------------------------------------------
        | FIX SELECT STYLE FILTER LAPORAN
        |--------------------------------------------------------------------------
        | Status select dibuat memakai custom select dari layout WBS agar tampilan
        | dropdown tidak menggunakan style bawaan browser.
        |--------------------------------------------------------------------------
        */
        .wbs-filter-select {
            min-width: 255px;
        }

        .wbs-filter-select .wbs-select-display {
            min-height: 44px;
            border-radius: 14px;
            padding: 11px 44px 11px 14px;
            font-size: 14px;
            font-weight: 600;
            background: var(--input-bg);
            border: 1.5px solid var(--border-strong);
            color: var(--text-primary);
        }

        .wbs-filter-select.open .wbs-select-display {
            border-color: var(--brand);
            box-shadow: 0 0 0 3.5px var(--brand-glow);
        }

        .wbs-filter-select .wbs-select-options {
            z-index: 9999;
            top: calc(100% + 8px);
            padding: 8px;
            border-radius: 16px;
            max-height: 290px;
            background: var(--surface);
            border: 1px solid var(--border);
            box-shadow: 0 18px 45px rgba(15, 23, 42, .14);
        }

        .wbs-filter-select .wbs-select-option {
            min-height: 42px;
            display: flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            transition: background .18s ease, color .18s ease;
        }

        .wbs-filter-select .wbs-select-option:hover {
            background: var(--surface-hover);
            color: var(--text-primary);
        }

        .wbs-filter-select .wbs-select-option.selected {
            background: var(--brand);
            color: #ffffff;
        }

        .wbs-filter-select .wbs-select-chevron {
            right: 14px;
        }

        @media (max-width: 768px) {
            .wbs-filter-select {
                min-width: 100%;
                width: 100%;
            }
        }
    </style>

    <div class="wbs-card">
        <form method="GET" action="{{ route('wbs.pelapor.reports.index') }}">
            <div class="wbs-toolbar">
                <div class="wbs-toolbar-left">
                    <div class="wbs-field">
                        <label for="search">Cari</label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="wbs-input"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="No laporan / judul"
                        >
                    </div>

                    <div class="wbs-field">
                        <label for="status">Status</label>

                        <div class="wbs-custom-select wbs-filter-select">
                            <select name="status" id="status">
                                <option value="">Semua Status</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            <span class="wbs-select-chevron" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="wbs-field">
                        <label>&nbsp;</label>
                        <button type="submit" class="wbs-btn wbs-btn-light">Filter</button>
                    </div>
                </div>

                <div class="wbs-toolbar-right">
                    <a href="{{ route('wbs.pelapor.reports.create') }}" class="wbs-btn wbs-btn-primary">
                        Buat Laporan Baru
                    </a>
                </div>
            </div>
        </form>

        @if($reports->count())
            <div class="wbs-table-wrap">
                <table class="wbs-table">
                    <thead>
                        <tr>
                            <th>No. Laporan</th>
                            <th>Kategori</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Lampiran</th>
                            <th>Dikirim</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->report_number }}</td>
                                <td>{{ $report->category_label }}</td>
                                <td>{{ $report->title }}</td>
                                <td>
                                    <span class="wbs-badge">
                                        {{ $report->status_label }}
                                    </span>
                                </td>
                                <td>{{ $report->attachments_count }}</td>
                                <td>{{ optional($report->submitted_at)->format('d-m-Y H:i') ?? '-' }}</td>
                                <td>
                                    @if($report->canBeEditedByPelapor())
                                        <span style="color:#166534; font-weight:700;">Masih bisa diedit</span>
                                    @else
                                        <span style="color:#64748b;">Sudah ditangani admin</span>
                                    @endif
                                </td>
                                <td style="white-space:nowrap;">
                                    <a href="{{ route('wbs.pelapor.reports.show', $report->id) }}" class="wbs-btn wbs-btn-light">
                                        Detail
                                    </a>

                                    @if($report->canBeEditedByPelapor())
                                        <a href="{{ route('wbs.pelapor.reports.edit', $report->id) }}" class="wbs-btn wbs-btn-primary">
                                            Edit
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
                <div class="wbs-pagination">
                    <div class="wbs-pagination-info">
                        Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
                    </div>

                    <div class="wbs-pagination-links">
                        @if($reports->onFirstPage())
                            <span class="wbs-pagination-disabled">‹</span>
                        @else
                            <a href="{{ $reports->previousPageUrl() }}" class="wbs-pagination-link">‹</a>
                        @endif

                        @foreach($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                            @if($page === $reports->currentPage())
                                <span class="wbs-pagination-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="wbs-pagination-link">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($reports->hasMorePages())
                            <a href="{{ $reports->nextPageUrl() }}" class="wbs-pagination-link">›</a>
                        @else
                            <span class="wbs-pagination-disabled">›</span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="wbs-empty">Belum ada laporan.</div>
        @endif
    </div>
@endsection