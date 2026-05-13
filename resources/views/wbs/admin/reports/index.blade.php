@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Monitoring Laporan WBS</h2>

    <style>
        .wbs-admin-filter-card {
            margin-bottom: 18px;
            overflow: visible;
        }

        .wbs-filter-grid {
            display: grid;
            grid-template-columns: minmax(220px, 1.3fr) minmax(170px, .8fr) minmax(190px, .9fr) minmax(150px, .7fr) minmax(130px, .6fr);
            gap: 14px;
            align-items: end;
            position: relative;
            z-index: 20;
        }

        .wbs-filter-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            position: relative;
            z-index: 5;
        }

        .wbs-filter-actions-left,
        .wbs-filter-actions-right {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .wbs-field {
            min-width: 0;
            position: relative;
        }

        .wbs-field label {
            display: block;
            margin-bottom: 7px;
            font-size: 12.5px;
            font-weight: 800;
            color: var(--text-secondary);
        }

        /* ============================================================
           CUSTOM SELECT FILTER - STYLE SEPERTI GAMBAR
        ============================================================ */
        .wbs-filter-select {
            position: relative;
            width: 100%;
            z-index: 1;
        }

        .wbs-filter-select.is-open {
            z-index: 999;
        }

        .wbs-filter-select select {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            pointer-events: none;
        }

        .wbs-filter-select-button {
            width: 100%;
            min-height: 54px;
            border: 1.5px solid var(--border-strong);
            border-radius: 18px;
            background: var(--input-bg);
            color: var(--text-primary);
            padding: 0 52px 0 18px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            outline: none;
            display: flex;
            align-items: center;
            text-align: left;
            position: relative;
            box-shadow: 0 1px 0 rgba(15, 23, 42, .02);
            transition:
                border-color var(--dur) var(--ease),
                box-shadow var(--dur) var(--ease),
                background var(--dur) var(--ease);
        }

        .wbs-filter-select-button:hover {
            border-color: var(--brand);
            background: var(--input-bg);
        }

        .wbs-filter-select-button:focus,
        .wbs-filter-select.is-open .wbs-filter-select-button {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px var(--brand-glow);
            background: var(--input-bg);
        }

        .wbs-filter-select.is-open .wbs-filter-select-button {
            border-radius: 18px;
        }

        .wbs-filter-select-text {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            min-width: 0;
            color: var(--text-primary);
        }

        .wbs-filter-select-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            width: 18px;
            height: 18px;
            transform: translateY(-50%);
            color: var(--brand);
            pointer-events: none;
            transition:
                color var(--dur) var(--ease),
                transform var(--dur) var(--ease);
        }

        .wbs-filter-select.is-open .wbs-filter-select-icon {
            color: var(--brand);
            transform: translateY(-50%) rotate(180deg);
        }

        .wbs-filter-select-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            max-height: 320px;
            overflow-y: auto;
            padding: 8px 0;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: var(--surface);
            box-shadow: 0 22px 55px rgba(15, 23, 42, .14);
            display: none;
            z-index: 9999;
            scrollbar-width: thin;
            scrollbar-color: #8b8b8b transparent;
        }

        .wbs-filter-select.is-open .wbs-filter-select-menu {
            display: block;
            animation: wbsSelectDown .14s ease both;
        }

        @keyframes wbsSelectDown {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .wbs-filter-select-menu::-webkit-scrollbar {
            width: 8px;
        }

        .wbs-filter-select-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .wbs-filter-select-menu::-webkit-scrollbar-thumb {
            background: #8b8b8b;
            border-radius: 999px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .wbs-filter-select-menu::-webkit-scrollbar-thumb:hover {
            background: #6f6f6f;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .wbs-filter-select-option {
            width: 100%;
            min-height: 48px;
            padding: 0 18px;
            border: 0;
            background: transparent;
            color: var(--text-primary);
            font: inherit;
            font-size: 15px;
            font-weight: 500;
            line-height: 1.45;
            cursor: pointer;
            display: flex;
            align-items: center;
            text-align: left;
            transition:
                background var(--dur) var(--ease),
                color var(--dur) var(--ease);
        }

        .wbs-filter-select-option:hover {
            background: var(--surface-hover);
            color: var(--text-primary);
        }

        .wbs-filter-select-option.is-selected {
            background: var(--brand);
            color: #ffffff;
            font-weight: 700;
        }

        .wbs-filter-select-option.is-selected:hover {
            background: var(--brand);
            color: #ffffff;
        }

        [data-theme="dark"] .wbs-filter-select-button {
            background: var(--input-bg);
        }

        [data-theme="dark"] .wbs-filter-select-menu {
            background: var(--surface);
            border-color: var(--border-strong);
            box-shadow: 0 24px 70px rgba(0,0,0,.55);
        }

        [data-theme="dark"] .wbs-filter-select-option:hover {
            background: var(--surface-hover);
        }

        .wbs-pagination {
            margin-top: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .wbs-pagination-info {
            color: var(--text-muted);
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
            font-weight: 800;
            text-decoration: none;
            border: 1px solid var(--border);
            transition: background var(--dur) var(--ease), color var(--dur) var(--ease), border-color var(--dur) var(--ease), transform var(--dur) var(--ease);
        }

        .wbs-pagination-link {
            background: var(--surface);
            color: var(--text-secondary);
        }

        .wbs-pagination-link:hover {
            background: var(--surface-hover);
            color: var(--text-primary);
            transform: translateY(-1px);
        }

        .wbs-pagination-active {
            background: var(--brand);
            color: #ffffff;
            border-color: var(--brand);
        }

        .wbs-pagination-disabled {
            background: var(--surface-alt);
            color: var(--text-muted);
            cursor: not-allowed;
        }

        .wbs-report-title-cell {
            min-width: 220px;
        }

        .wbs-report-title {
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.45;
        }

        .wbs-report-number {
            font-weight: 800;
            color: var(--brand);
            white-space: nowrap;
        }

        .wbs-report-user {
            font-weight: 800;
            color: var(--text-primary);
        }

        .wbs-report-email {
            display: block;
            margin-top: 3px;
            color: var(--text-muted);
            font-size: 12.5px;
            word-break: break-word;
        }

        .wbs-table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            white-space: nowrap;
        }

        .wbs-empty-state {
            padding: 44px 20px;
            text-align: center;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .wbs-empty-state strong {
            display: block;
            margin-bottom: 6px;
            color: var(--text-primary);
            font-size: 17px;
        }

        @media (max-width: 1180px) {
            .wbs-filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 720px) {
            .wbs-filter-grid {
                grid-template-columns: 1fr;
            }

            .wbs-filter-select-menu {
                max-height: 260px;
            }

            .wbs-filter-actions {
                align-items: stretch;
            }

            .wbs-filter-actions-left,
            .wbs-filter-actions-right,
            .wbs-filter-actions .wbs-btn {
                width: 100%;
            }

            .wbs-filter-actions-left,
            .wbs-filter-actions-right {
                display: grid;
                grid-template-columns: 1fr;
            }

            .wbs-table-actions .wbs-btn {
                width: 100%;
            }

            .wbs-pagination {
                justify-content: center;
                text-align: center;
            }

            .wbs-pagination-info,
            .wbs-pagination-links {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="wbs-card wbs-admin-filter-card">
        <form method="GET" action="{{ route('wbs.admin.reports.index') }}">
            <div class="wbs-filter-grid">
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

                <div class="wbs-field">
                    <label for="status">Status</label>
                    <div class="wbs-filter-select" data-wbs-filter-select>
                        <select name="status" id="status">
                            <option value="">Semua Status</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" class="wbs-filter-select-button" aria-haspopup="listbox" aria-expanded="false">
                            <span class="wbs-filter-select-text">Semua Status</span>
                            <svg class="wbs-filter-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>

                        <div class="wbs-filter-select-menu" role="listbox"></div>
                    </div>
                </div>

                <div class="wbs-field">
                    <label for="category">Kategori</label>
                    <div class="wbs-filter-select" data-wbs-filter-select>
                        <select name="category" id="category">
                            <option value="">Semua Kategori</option>
                            @foreach($categoryOptions as $value => $label)
                                <option value="{{ $value }}" {{ ($filters['category'] ?? '') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" class="wbs-filter-select-button" aria-haspopup="listbox" aria-expanded="false">
                            <span class="wbs-filter-select-text">Semua Kategori</span>
                            <svg class="wbs-filter-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>

                        <div class="wbs-filter-select-menu" role="listbox"></div>
                    </div>
                </div>

                <div class="wbs-field">
                    <label for="month">Bulan</label>
                    <div class="wbs-filter-select" data-wbs-filter-select>
                        <select name="month" id="month">
                            <option value="">Semua Bulan</option>
                            @foreach($monthOptions as $value => $label)
                                <option value="{{ $value }}" {{ (string) ($filters['month'] ?? '') === (string) $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" class="wbs-filter-select-button" aria-haspopup="listbox" aria-expanded="false">
                            <span class="wbs-filter-select-text">Semua Bulan</span>
                            <svg class="wbs-filter-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>

                        <div class="wbs-filter-select-menu" role="listbox"></div>
                    </div>
                </div>

                <div class="wbs-field">
                    <label for="year">Tahun</label>
                    <div class="wbs-filter-select" data-wbs-filter-select>
                        <select name="year" id="year">
                            <option value="">Semua Tahun</option>
                            @foreach($yearOptions as $value => $label)
                                <option value="{{ $value }}" {{ (string) ($filters['year'] ?? '') === (string) $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" class="wbs-filter-select-button" aria-haspopup="listbox" aria-expanded="false">
                            <span class="wbs-filter-select-text">Semua Tahun</span>
                            <svg class="wbs-filter-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>

                        <div class="wbs-filter-select-menu" role="listbox"></div>
                    </div>
                </div>
            </div>

            <div class="wbs-filter-actions">
                <div class="wbs-filter-actions-left">
                    <button type="submit" class="wbs-btn wbs-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="M21 21l-4.35-4.35"></path>
                        </svg>
                        Filter
                    </button>

                    <a href="{{ route('wbs.admin.reports.index') }}" class="wbs-btn wbs-btn-light">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <path d="M3 12a9 9 0 1 0 3-6.7"></path>
                            <path d="M3 4v5h5"></path>
                        </svg>
                        Reset
                    </a>
                </div>

                <div class="wbs-filter-actions-right">
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
    </div>

    <div class="wbs-card">
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
                                <td>
                                    <span class="wbs-report-number">
                                        {{ $report->report_number }}
                                    </span>
                                </td>

                                <td>
                                    <span class="wbs-report-user">
                                        {{ $report->user->name ?? '-' }}
                                    </span>
                                    <span class="wbs-report-email">
                                        {{ $report->user->email ?? '-' }}
                                    </span>
                                </td>

                                <td>{{ $report->category_label }}</td>

                                <td class="wbs-report-title-cell">
                                    <div class="wbs-report-title">
                                        {{ $report->title }}
                                    </div>
                                </td>

                                <td>
                                    <span class="wbs-badge">
                                        {{ $report->status_label }}
                                    </span>
                                </td>

                                <td>{{ $report->attachments_count }}</td>

                                <td>
                                    {{ optional($report->submitted_at)->format('d-m-Y H:i') ?? '-' }}
                                </td>

                                <td>
                                    <div class="wbs-table-actions">
                                        <a href="{{ route('wbs.admin.reports.show', $report->id) }}" class="wbs-btn wbs-btn-light">
                                            Detail
                                        </a>

                                        <a href="{{ route('wbs.admin.reports.edit', $report->id) }}" class="wbs-btn wbs-btn-primary">
                                            Update Status
                                        </a>
                                    </div>
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
            <div class="wbs-empty-state">
                <strong>Belum ada laporan</strong>
                Tidak ada data laporan yang cocok dengan filter saat ini.
            </div>
        @endif
    </div>

    <script>
        (function () {
            const selects = document.querySelectorAll('[data-wbs-filter-select]');

            function closeAllSelects(except = null) {
                selects.forEach(function (wrapper) {
                    if (wrapper === except) {
                        return;
                    }

                    wrapper.classList.remove('is-open');

                    const button = wrapper.querySelector('.wbs-filter-select-button');
                    if (button) {
                        button.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            selects.forEach(function (wrapper) {
                const nativeSelect = wrapper.querySelector('select');
                const button = wrapper.querySelector('.wbs-filter-select-button');
                const text = wrapper.querySelector('.wbs-filter-select-text');
                const menu = wrapper.querySelector('.wbs-filter-select-menu');

                if (!nativeSelect || !button || !text || !menu) {
                    return;
                }

                function syncSelectedText() {
                    const selectedOption = nativeSelect.options[nativeSelect.selectedIndex];

                    if (selectedOption) {
                        text.textContent = selectedOption.textContent.trim();
                    }
                }

                function buildOptions() {
                    menu.innerHTML = '';

                    Array.from(nativeSelect.options).forEach(function (option) {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'wbs-filter-select-option';
                        item.textContent = option.textContent.trim();
                        item.dataset.value = option.value;
                        item.setAttribute('role', 'option');

                        if (option.selected) {
                            item.classList.add('is-selected');
                            item.setAttribute('aria-selected', 'true');
                        } else {
                            item.setAttribute('aria-selected', 'false');
                        }

                        item.addEventListener('click', function () {
                            nativeSelect.value = option.value;

                            menu.querySelectorAll('.wbs-filter-select-option').forEach(function (el) {
                                el.classList.remove('is-selected');
                                el.setAttribute('aria-selected', 'false');
                            });

                            item.classList.add('is-selected');
                            item.setAttribute('aria-selected', 'true');

                            syncSelectedText();

                            nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));

                            wrapper.classList.remove('is-open');
                            button.setAttribute('aria-expanded', 'false');
                        });

                        menu.appendChild(item);
                    });
                }

                syncSelectedText();
                buildOptions();

                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const willOpen = !wrapper.classList.contains('is-open');

                    closeAllSelects(wrapper);

                    wrapper.classList.toggle('is-open', willOpen);
                    button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                });

                nativeSelect.addEventListener('change', function () {
                    syncSelectedText();
                    buildOptions();
                });
            });

            document.addEventListener('click', function () {
                closeAllSelects();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeAllSelects();
                }
            });
        })();
    </script>
@endsection