@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Monitoring Laporan WBS</h2>

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
    </style>

    <div class="wbs-card">
        <form method="GET" action="{{ route('wbs.admin.reports.index') }}">
            <div class="wbs-toolbar">
                <div class="wbs-toolbar-left">

                    {{-- Search --}}
                    <div class="wbs-field">
                        <label for="search">Cari</label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="wbs-input"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="No laporan / judul / nama / email"
                        >
                    </div>

                    {{-- Status --}}
                    <div class="wbs-field">
                        <label for="status">Status</label>
                        <div class="wbs-custom-select">
                            <select name="status" id="status">
                                <option value="">Semua Status</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>
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

                    {{-- Kategori --}}
                    <div class="wbs-field">
                        <label for="category">Kategori</label>
                        <div class="wbs-custom-select">
                            <select name="category" id="category">
                                <option value="">Semua Kategori</option>
                                @foreach($categoryOptions as $value => $label)
                                    <option value="{{ $value }}" {{ ($filters['category'] ?? '') === $value ? 'selected' : '' }}>
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

                    <div class="wbs-field">
                        <label>&nbsp;</label>
                        <button type="submit" class="wbs-btn wbs-btn-light">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="M21 21l-4.35-4.35"></path>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>

                <div class="wbs-toolbar-right">
                    <a href="{{ route('wbs.admin.reports.export-filtered-pdf', request()->query()) }}" class="wbs-btn wbs-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export PDF Filter
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
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Lampiran</th>
                            <th>Dikirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->report_number }}</td>
                                <td>
                                    {{ $report->user->name ?? '-' }}<br>
                                    <small style="color:#64748b;">{{ $report->user->email ?? '-' }}</small>
                                </td>
                                <td>{{ $report->category_label }}</td>
                                <td>{{ $report->title }}</td>
                                <td><span class="wbs-badge">{{ $report->status_label }}</span></td>
                                <td>{{ $report->attachments_count }}</td>
                                <td>{{ optional($report->submitted_at)->format('d-m-Y H:i') ?? '-' }}</td>
                                <td style="white-space:nowrap;">
                                    <a href="{{ route('wbs.admin.reports.show', $report->id) }}" class="wbs-btn wbs-btn-light">Detail</a>
                                    <a href="{{ route('wbs.admin.reports.edit', $report->id) }}" class="wbs-btn wbs-btn-primary">Update Status</a>
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