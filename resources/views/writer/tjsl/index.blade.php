@extends('layouts.writer')

@section('title', 'Writer TJSL')

@section('content')
<style>
    .wt-page {
        max-width: 1180px;
    }

    .wt-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .wt-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 900;
        color: #64748b;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .wt-breadcrumb span:first-child {
        color: #173f08;
    }

    .wt-breadcrumb-sep {
        color: #94a3b8;
    }

    .wt-title {
        margin: 0;
        font-size: 30px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.04em;
        line-height: 1.15;
    }

    .wt-subtitle {
        margin-top: 7px;
        font-size: 14px;
        color: #64748b;
        line-height: 1.75;
        max-width: 760px;
    }

    .wt-head-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .wt-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 20px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
    }

    .wt-filter-card {
        margin-bottom: 16px;
    }

    .wt-filter {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .wt-filter-left {
        display: flex;
        align-items: end;
        gap: 12px;
        flex-wrap: wrap;
        min-width: 0;
    }

    .wt-field {
        display: grid;
        gap: 7px;
    }

    .wt-label {
        display: block;
        font-size: 12px;
        font-weight: 900;
        color: #334155;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .wt-search-wrap {
        position: relative;
        width: min(380px, 100%);
    }

    .wt-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        display: inline-flex;
    }

    .wt-search-icon svg {
        width: 17px;
        height: 17px;
        stroke: currentColor;
    }

    .wt-search {
        width: 100%;
        min-height: 48px;
        border: 1px solid #d8dee7;
        border-radius: 16px;
        padding: 0 15px 0 42px;
        font: inherit;
        font-size: 14px;
        color: #111827;
        background: #fff;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .wt-search::placeholder {
        color: #94a3b8;
    }

    .wt-search:focus {
        border-color: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .09);
        background: #fbfdfb;
    }

    /* =========================
       CUSTOM STATUS DROPDOWN
    ========================= */
    .wt-dropdown {
        position: relative;
        width: 240px;
        z-index: 20;
    }

    .wt-dropdown-toggle {
        width: 100%;
        min-height: 48px;
        border: 1px solid #d8dee7;
        border-radius: 16px;
        background: #ffffff;
        color: #111827;
        padding: 0 46px 0 15px;
        font: inherit;
        font-size: 14px;
        font-weight: 900;
        text-align: left;
        cursor: pointer;
        outline: none;
        position: relative;
        transition:
            border-color .18s ease,
            box-shadow .18s ease,
            background .18s ease,
            color .18s ease;
    }

    .wt-dropdown-toggle:hover {
        border-color: rgba(23, 63, 8, .35);
        background: #fbfdfb;
    }

    .wt-dropdown-toggle:focus,
    .wt-dropdown.is-open .wt-dropdown-toggle {
        border-color: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .09);
        background: #ffffff;
    }

    .wt-dropdown-toggle::after {
        content: "";
        position: absolute;
        right: 17px;
        top: 50%;
        width: 9px;
        height: 9px;
        border-right: 2px solid #64748b;
        border-bottom: 2px solid #64748b;
        transform: translateY(-68%) rotate(45deg);
        transition: transform .18s ease;
        pointer-events: none;
    }

    .wt-dropdown.is-open .wt-dropdown-toggle::after {
        transform: translateY(-35%) rotate(225deg);
    }

    .wt-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        padding: 7px;
        border-radius: 16px;
        border: 1px solid #dbe3ea;
        background: #ffffff;
        box-shadow:
            0 18px 38px rgba(15, 23, 42, .14),
            0 2px 8px rgba(15, 23, 42, .06);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-6px) scale(.98);
        transform-origin: top;
        transition:
            opacity .16s ease,
            visibility .16s ease,
            transform .16s ease;
        overflow: hidden;
    }

    .wt-dropdown.is-open .wt-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .wt-dropdown-option {
        width: 100%;
        min-height: 42px;
        border: 0;
        border-radius: 12px;
        background: transparent;
        color: #111827;
        padding: 0 12px;
        font: inherit;
        font-size: 14px;
        font-weight: 800;
        text-align: left;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        transition:
            background .14s ease,
            color .14s ease;
    }

    .wt-dropdown-option:hover {
        background: #eef6eb;
        color: #173f08;
    }

    .wt-dropdown-option.is-active {
        background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(23, 63, 8, .18);
    }

    .wt-dropdown-option.is-active::after {
        content: "✓";
        font-size: 13px;
        font-weight: 900;
    }

    .wt-dropdown-option + .wt-dropdown-option {
        margin-top: 3px;
    }

    .wt-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 0 15px;
        border-radius: 14px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #111827;
        font-size: 13px;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        transition:
            transform .16s ease,
            background .16s ease,
            border-color .16s ease,
            color .16s ease,
            box-shadow .16s ease;
        white-space: nowrap;
        line-height: 1;
    }

    .wt-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        flex-shrink: 0;
    }

    .wt-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, .08);
    }

    .wt-btn--primary {
        background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
        border-color: #173f08;
        color: #fff;
        box-shadow: 0 10px 22px rgba(23, 63, 8, .16);
    }

    .wt-btn--primary:hover {
        background: linear-gradient(135deg, #102d06 0%, #173f08 100%);
        border-color: #102d06;
        color: #fff;
    }

    .wt-btn--light {
        background: #f8fafc;
        color: #334155;
        border-color: #e2e8f0;
    }

    .wt-btn--light:hover {
        background: #eef6eb;
        color: #173f08;
        border-color: rgba(23, 63, 8, .25);
    }

    .wt-btn--edit {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .wt-btn--edit:hover {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }

    .wt-btn--preview {
        background: #f8fafc;
        color: #475569;
        border-color: #e2e8f0;
    }

    .wt-btn--preview:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .wt-btn--wa {
        background: #ecfdf5;
        color: #047857;
        border-color: #a7f3d0;
    }

    .wt-btn--wa:hover {
        background: #d1fae5;
        color: #065f46;
        border-color: #6ee7b7;
    }

    .wt-btn--success {
        background: #ecfdf3;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .wt-btn--success:hover {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .wt-btn--warning {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    .wt-btn--warning:hover {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .wt-btn--danger {
        background: #fff1f2;
        color: #be123c;
        border-color: #fecdd3;
    }

    .wt-btn--danger:hover {
        background: #ffe4e6;
        color: #9f1239;
        border-color: #fda4af;
    }

    .wt-btn--sm {
        min-height: 36px;
        padding: 0 12px;
        border-radius: 12px;
        font-size: 12px;
    }

    .wt-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .wt-card-title {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.03em;
    }

    .wt-card-desc {
        margin-top: 4px;
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
    }

    .wt-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        background: #fff;
    }

    .wt-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1080px;
        background: #fff;
    }

    .wt-table th {
        padding: 14px 15px;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .04em;
        text-transform: uppercase;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .wt-table td {
        padding: 15px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: top;
        font-size: 14px;
        color: #111827;
    }

    .wt-table tbody tr {
        transition: background .16s ease;
    }

    .wt-table tbody tr:hover {
        background: #fbfdfb;
    }

    .wt-table tbody tr:last-child td {
        border-bottom: none;
    }

    .wt-thumb {
        width: 68px;
        height: 50px;
        object-fit: cover;
        border-radius: 13px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        display: block;
    }

    .wt-thumb-empty {
        width: 68px;
        height: 50px;
        border-radius: 13px;
        background: #f3f4f6;
        border: 1px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 900;
        color: #64748b;
        text-align: center;
    }

    .wt-year {
        font-weight: 900;
        color: #111827;
    }

    .wt-program-title {
        font-weight: 900;
        line-height: 1.45;
        color: #0f172a;
        margin-bottom: 5px;
        max-width: 380px;
    }

    .wt-program-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        line-height: 1.55;
        max-width: 420px;
    }

    .wt-program-sub--warning {
        color: #b45309;
    }

    .wt-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 11px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .wt-badge--gray {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }

    .wt-badge--green {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .wt-badge--orange {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    .wt-date {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.65;
    }

    .wt-date strong {
        color: #334155;
        font-weight: 900;
    }

    .wt-actions {
        display: flex;
        gap: 7px;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 300px;
        margin: 0 auto;
    }

    .wt-actions form {
        margin: 0;
    }

    .wt-empty {
        padding: 48px 20px;
        text-align: center;
        color: #64748b;
    }

    .wt-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 22px;
        background: #eef6eb;
        color: #173f08;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        box-shadow: inset 0 0 0 1px rgba(23, 63, 8, .08);
    }

    .wt-empty-icon svg {
        width: 30px;
        height: 30px;
        stroke: currentColor;
    }

    .wt-empty-title {
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .wt-empty-desc {
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 16px;
    }

    .wt-pagination {
        margin-top: 16px;
    }

    @media (max-width: 760px) {
        .wt-head {
            display: grid;
            gap: 16px;
        }

        .wt-title {
            font-size: 24px;
        }

        .wt-head-actions,
        .wt-btn {
            width: 100%;
        }

        .wt-card {
            padding: 14px;
            border-radius: 18px;
        }

        .wt-filter {
            align-items: stretch;
            gap: 12px;
        }

        .wt-filter-left,
        .wt-field,
        .wt-search-wrap,
        .wt-dropdown {
            width: 100%;
        }

        .wt-actions {
            max-width: none;
            width: 100%;
        }

        .wt-actions .wt-btn,
        .wt-actions form {
            width: 100%;
        }

        .wt-actions form .wt-btn {
            width: 100%;
        }

        .wt-dropdown-menu {
            position: fixed;
            left: 16px;
            right: 16px;
            width: auto;
            top: auto;
            transform: none;
            margin-top: 8px;
        }

        .wt-dropdown.is-open .wt-dropdown-menu {
            transform: none;
        }
    }
</style>

<div class="wt-page">
    <div class="wt-head">
        <div>
            <div class="wt-breadcrumb">
                <span>Writer</span>
                <span class="wt-breadcrumb-sep">›</span>
                <span>TJSL</span>
            </div>

            <h1 class="wt-title">Program TJSL Saya</h1>

            <p class="wt-subtitle">
                Kelola draft, preview, kirim link preview ke reviewer, dan publish TJSL secara mandiri.
            </p>
        </div>

        <div class="wt-head-actions">
            <a href="{{ route('writer.tjsl.create') }}" class="wt-btn wt-btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
                Tambah TJSL
            </a>
        </div>
    </div>

    <div class="wt-card wt-filter-card">
        <form method="GET" action="{{ route('writer.tjsl.index') }}" class="wt-filter">
            <div class="wt-filter-left">
                <div class="wt-field">
                    <label class="wt-label">Search</label>

                    <div class="wt-search-wrap">
                        <span class="wt-search-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <circle cx="11" cy="11" r="7"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                        </span>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            class="wt-search"
                            placeholder="Cari judul / tahun"
                        >
                    </div>
                </div>

                <div class="wt-field">
                    <label class="wt-label">Status</label>

                    @php
                        $statusOptions = ['' => 'Semua Status'] + ($statuses ?? []);
                        $currentStatus = $status ?? '';
                        $currentStatusLabel = $statusOptions[$currentStatus] ?? 'Semua Status';
                    @endphp

                    <div class="wt-dropdown" data-custom-dropdown>
                        <input type="hidden" name="status" value="{{ $currentStatus }}" data-dropdown-input>

                        <button type="button" class="wt-dropdown-toggle" data-dropdown-toggle>
                            {{ $currentStatusLabel }}
                        </button>

                        <div class="wt-dropdown-menu" data-dropdown-menu>
                            @foreach($statusOptions as $key => $label)
                                <button
                                    type="button"
                                    class="wt-dropdown-option {{ (string) $currentStatus === (string) $key ? 'is-active' : '' }}"
                                    data-dropdown-option
                                    data-value="{{ $key }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="wt-head-actions">
                <button type="submit" class="wt-btn wt-btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 3H2l8 9.5V19l4 2v-8.5L22 3Z"/>
                    </svg>
                    Filter
                </button>

                <a href="{{ route('writer.tjsl.index') }}" class="wt-btn wt-btn--light">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 3-6.7"/>
                        <path d="M3 4v6h6"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="wt-card">
        <div class="wt-card-head">
            <div>
                <div class="wt-card-title">Daftar TJSL</div>
                <div class="wt-card-desc">Total {{ $programs->total() }} program</div>
            </div>
        </div>

        <div class="wt-table-wrap">
            <table class="wt-table">
                <thead>
                    <tr>
                        <th width="48">#</th>
                        <th width="90">Gambar</th>
                        <th width="90">Tahun</th>
                        <th>Judul</th>
                        <th width="140">Status</th>
                        <th width="165">Tanggal</th>
                        <th width="300" style="text-align:center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($programs as $program)
                        @php
                            $trId = $program->translations->firstWhere('locale', 'id');
                            $trEn = $program->translations->firstWhere('locale', 'en');

                            $badgeClass = match($program->status) {
                                'draft' => 'wt-badge--gray',
                                'published' => 'wt-badge--green',
                                default => 'wt-badge--orange',
                            };
                        @endphp

                        <tr>
                            <td>{{ $programs->firstItem() + $loop->index }}</td>

                            <td>
                                @if($program->featured_image)
                                    <img src="{{ asset($program->featured_image) }}" alt="TJSL" class="wt-thumb">
                                @else
                                    <div class="wt-thumb-empty">No Image</div>
                                @endif
                            </td>

                            <td>
                                <div class="wt-year">{{ $program->year }}</div>
                            </td>

                            <td>
                                <div class="wt-program-title">{{ $trId->title ?? '-' }}</div>

                                @if($trEn?->title)
                                    <div class="wt-program-sub">
                                        EN otomatis: {{ $trEn->title }}
                                    </div>
                                @else
                                    <div class="wt-program-sub wt-program-sub--warning">
                                        EN belum tersedia
                                    </div>
                                @endif

                                <div class="wt-program-sub">
                                    Galeri: {{ $program->images->count() }} foto
                                </div>
                            </td>

                            <td>
                                <span class="wt-badge {{ $badgeClass }}">
                                    {{ $program->status_label }}
                                </span>
                            </td>

                            <td>
                                <div class="wt-date">
                                    Dibuat:<br>
                                    <strong>{{ $program->created_at?->format('d M Y H:i') ?? '-' }}</strong>
                                </div>

                                <div class="wt-date" style="margin-top:6px">
                                    Publish:<br>
                                    <strong>{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</strong>
                                </div>
                            </td>

                            <td style="text-align:center">
                                <div class="wt-actions">
                                    <a href="{{ route('writer.tjsl.show', $program) }}" class="wt-btn wt-btn--light wt-btn--sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                            <path d="M14 2v6h6"/>
                                            <path d="M8 13h8"/>
                                            <path d="M8 17h5"/>
                                        </svg>
                                        Detail
                                    </a>

                                    <a href="{{ route('writer.tjsl.preview', $program) }}" class="wt-btn wt-btn--preview wt-btn--sm" target="_blank">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Preview
                                    </a>

                                    <a href="{{ route('writer.tjsl.edit', $program) }}" class="wt-btn wt-btn--edit wt-btn--sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"/>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <a href="{{ route('writer.tjsl.send-preview-whatsapp', $program) }}" class="wt-btn wt-btn--wa wt-btn--sm" target="_blank">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 11.5a8.38 8.38 0 0 1-1.24 4.38A8.5 8.5 0 0 1 12 20.5a8.38 8.38 0 0 1-4.38-1.24L3 20l.76-4.62A8.38 8.38 0 0 1 2.5 11.5a8.5 8.5 0 0 1 17 0Z"/>
                                        </svg>
                                        Kirim WA
                                    </a>

                                    @if($program->status === 'draft')
                                        <form
                                            method="POST"
                                            action="{{ route('writer.tjsl.publish', $program) }}"
                                            class="js-confirm-submit"
                                            data-title="Publish TJSL ini?"
                                            data-text="Program TJSL akan tampil di website publik."
                                            data-confirm="Ya, Publish"
                                            data-type="publish"
                                            data-icon="question"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="wt-btn wt-btn--success wt-btn--sm">
                                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 6 9 17l-5-5"/>
                                                </svg>
                                                Publish
                                            </button>
                                        </form>
                                    @endif

                                    @if($program->status === 'published')
                                        <form
                                            method="POST"
                                            action="{{ route('writer.tjsl.unpublish', $program) }}"
                                            class="js-confirm-submit"
                                            data-title="Unpublish TJSL ini?"
                                            data-text="Program TJSL akan disembunyikan dari website publik."
                                            data-confirm="Ya, Unpublish"
                                            data-type="unpublish"
                                            data-icon="warning"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="wt-btn wt-btn--warning wt-btn--sm">
                                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 3l18 18"/>
                                                    <path d="M10.6 10.6A3 3 0 0 0 14 14"/>
                                                    <path d="M9.9 4.25A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a18.3 18.3 0 0 1-3.1 4.5"/>
                                                    <path d="M6.6 6.6C3.7 8.6 2 12 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.4-.95"/>
                                                </svg>
                                                Unpublish
                                            </button>
                                        </form>
                                    @endif

                                    <form
                                        method="POST"
                                        action="{{ route('writer.tjsl.destroy', $program) }}"
                                        class="js-delete-form"
                                        data-title="Hapus program TJSL ini?"
                                        data-text="Program TJSL yang dihapus tidak dapat dikembalikan."
                                        data-confirm="Ya, Hapus"
                                        data-type="delete"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="wt-btn wt-btn--danger wt-btn--sm">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4h8v2"/>
                                                <path d="M19 6l-1 16H6L5 6"/>
                                                <path d="M10 11v6"/>
                                                <path d="M14 11v6"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="wt-empty">
                                    <div class="wt-empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                                            <path d="M9 12l2 2 4-5"/>
                                        </svg>
                                    </div>

                                    <div class="wt-empty-title">Belum ada program TJSL</div>

                                    <div class="wt-empty-desc">
                                        Mulai dengan membuat draft TJSL baru.
                                    </div>

                                    <a href="{{ route('writer.tjsl.create') }}" class="wt-btn wt-btn--primary">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 5v14"/>
                                            <path d="M5 12h14"/>
                                        </svg>
                                        Tambah TJSL
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($programs, 'links'))
            <div class="wt-pagination w-pagination">
                {{ $programs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>
(function () {
    document.querySelectorAll('[data-custom-dropdown]').forEach(function (dropdown) {
        const toggle = dropdown.querySelector('[data-dropdown-toggle]');
        const input = dropdown.querySelector('[data-dropdown-input]');
        const options = dropdown.querySelectorAll('[data-dropdown-option]');

        if (!toggle || !input || !options.length) {
            return;
        }

        toggle.addEventListener('click', function (event) {
            event.preventDefault();

            document.querySelectorAll('[data-custom-dropdown].is-open').forEach(function (openDropdown) {
                if (openDropdown !== dropdown) {
                    openDropdown.classList.remove('is-open');
                }
            });

            dropdown.classList.toggle('is-open');
        });

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                const value = option.getAttribute('data-value') || '';
                const label = option.textContent.trim();

                input.value = value;
                toggle.textContent = label;

                options.forEach(function (item) {
                    item.classList.remove('is-active');
                });

                option.classList.add('is-active');
                dropdown.classList.remove('is-open');
            });
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('[data-custom-dropdown]')) {
            document.querySelectorAll('[data-custom-dropdown].is-open').forEach(function (dropdown) {
                dropdown.classList.remove('is-open');
            });
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[data-custom-dropdown].is-open').forEach(function (dropdown) {
                dropdown.classList.remove('is-open');
            });
        }
    });
})();
</script>
@endsection