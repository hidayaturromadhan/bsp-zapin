@extends('layouts.writer')

@section('title', 'News Writer')

@section('content')
<style>
    .wn-page {
        max-width: 1180px;
    }

    .wn-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .wn-title {
        margin: 0;
        font-size: 30px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.04em;
        line-height: 1.15;
    }

    .wn-subtitle {
        margin-top: 7px;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.7;
        max-width: 760px;
    }

    .wn-head-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .wn-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 20px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
    }

    .wn-filter {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 18px;
        padding: 4px 0 2px;
    }

    .wn-filter-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        min-width: 0;
    }

    .wn-search-wrap {
        position: relative;
        width: min(380px, 100%);
    }

    .wn-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        display: inline-flex;
    }

    .wn-search-icon svg {
        width: 17px;
        height: 17px;
        stroke: currentColor;
    }

    .wn-search {
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

    .wn-search::placeholder {
        color: #94a3b8;
    }

    .wn-search:focus {
        border-color: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .09);
        background: #fbfdfb;
    }

    /* =========================
       CUSTOM DROPDOWN STATUS
    ========================= */
    .wn-dropdown {
        position: relative;
        width: 240px;
        z-index: 20;
    }

    .wn-dropdown-toggle {
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

    .wn-dropdown-toggle:hover {
        border-color: rgba(23, 63, 8, .35);
        background: #fbfdfb;
    }

    .wn-dropdown-toggle:focus,
    .wn-dropdown.is-open .wn-dropdown-toggle {
        border-color: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .09);
        background: #ffffff;
    }

    .wn-dropdown-toggle::after {
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

    .wn-dropdown.is-open .wn-dropdown-toggle::after {
        transform: translateY(-35%) rotate(225deg);
    }

    .wn-dropdown-menu {
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

    .wn-dropdown.is-open .wn-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .wn-dropdown-option {
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
            color .14s ease,
            transform .14s ease;
    }

    .wn-dropdown-option:hover {
        background: #eef6eb;
        color: #173f08;
    }

    .wn-dropdown-option.is-active {
        background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(23, 63, 8, .18);
    }

    .wn-dropdown-option.is-active::after {
        content: "✓";
        font-size: 13px;
        font-weight: 900;
    }

    .wn-dropdown-option + .wn-dropdown-option {
        margin-top: 3px;
    }

    .wn-btn {
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

    .wn-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        flex-shrink: 0;
    }

    .wn-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, .08);
    }

    .wn-btn--primary {
        background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
        border-color: #173f08;
        color: #fff;
        box-shadow: 0 10px 22px rgba(23, 63, 8, .16);
    }

    .wn-btn--primary:hover {
        background: linear-gradient(135deg, #102d06 0%, #173f08 100%);
        border-color: #102d06;
        color: #fff;
    }

    .wn-btn--light {
        background: #f8fafc;
        color: #334155;
        border-color: #e2e8f0;
    }

    .wn-btn--light:hover {
        background: #eef6eb;
        color: #173f08;
        border-color: rgba(23, 63, 8, .25);
    }

    .wn-btn--edit {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .wn-btn--edit:hover {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }

    .wn-btn--preview {
        background: #f8fafc;
        color: #475569;
        border-color: #e2e8f0;
    }

    .wn-btn--preview:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .wn-btn--danger {
        background: #fff1f2;
        color: #be123c;
        border-color: #fecdd3;
    }

    .wn-btn--danger:hover {
        background: #ffe4e6;
        color: #9f1239;
        border-color: #fda4af;
    }

    .wn-btn--success {
        background: #ecfdf3;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .wn-btn--success:hover {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .wn-btn--warning {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    .wn-btn--warning:hover {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .wn-btn--sm {
        min-height: 36px;
        padding: 0 12px;
        border-radius: 12px;
        font-size: 12px;
    }

    .wn-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        background: #fff;
    }

    .wn-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 980px;
        background: #fff;
    }

    .wn-table th {
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

    .wn-table td {
        padding: 15px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: top;
        font-size: 14px;
        color: #111827;
    }

    .wn-table tbody tr {
        transition: background .16s ease;
    }

    .wn-table tbody tr:hover {
        background: #fbfdfb;
    }

    .wn-table tbody tr:last-child td {
        border-bottom: none;
    }

    .wn-news-title {
        font-weight: 900;
        line-height: 1.45;
        color: #0f172a;
        margin-bottom: 5px;
    }

    .wn-news-excerpt {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.6;
        max-width: 420px;
    }

    .wn-meta {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.6;
    }

    .wn-badge {
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

    .wn-badge--draft {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }

    .wn-badge--review {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    .wn-badge--published {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .wn-badge--rejected {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .wn-badge--default {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .wn-actions {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        max-width: 280px;
    }

    .wn-actions form {
        margin: 0;
    }

    .wn-empty {
        padding: 52px 20px;
        text-align: center;
        color: #64748b;
    }

    .wn-empty-icon {
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

    .wn-empty-title {
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .wn-empty-desc {
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 16px;
    }

    @media (max-width: 760px) {
        .wn-title {
            font-size: 24px;
        }

        .wn-card {
            padding: 14px;
            border-radius: 18px;
        }

        .wn-filter {
            align-items: stretch;
            gap: 12px;
        }

        .wn-filter-left,
        .wn-head-actions,
        .wn-search-wrap,
        .wn-dropdown {
            width: 100%;
        }

        .wn-btn {
            width: 100%;
        }

        .wn-actions {
            max-width: none;
        }

        .wn-actions .wn-btn,
        .wn-actions form {
            width: 100%;
        }

        .wn-actions form .wn-btn {
            width: 100%;
        }

        .wn-dropdown-menu {
            position: fixed;
            left: 16px;
            right: 16px;
            width: auto;
            top: auto;
            transform: none;
            margin-top: 8px;
        }

        .wn-dropdown.is-open .wn-dropdown-menu {
            transform: none;
        }
    }
</style>

<div class="wn-page">
    <div class="wn-head">
        <div>
            <h1 class="wn-title">News</h1>
            <div class="wn-subtitle">
                Kelola draft berita, susun konten, lalu kirim ke reviewer untuk proses validasi dan publikasi.
            </div>
        </div>

        <div class="wn-head-actions">
            <a href="{{ route('writer.news.create') }}" class="wn-btn wn-btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
                Buat News
            </a>
        </div>
    </div>

    <div class="wn-card">
        <form method="GET" action="{{ route('writer.news.index') }}" class="wn-filter">
            <div class="wn-filter-left">
                <div class="wn-search-wrap">
                    <span class="wn-search-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        class="wn-search"
                        placeholder="Cari judul berita..."
                    >
                </div>

                @php
                    $currentStatus = request('status', '');
                    $statusOptions = [
                        '' => 'Semua Status',
                        'draft' => 'Draft',
                        'pending_review' => 'Pending Review',
                        'published' => 'Published',
                        'rejected' => 'Rejected',
                    ];
                    $currentStatusLabel = $statusOptions[$currentStatus] ?? 'Semua Status';
                @endphp

                <div class="wn-dropdown" data-custom-dropdown>
                    <input type="hidden" name="status" value="{{ $currentStatus }}" data-dropdown-input>

                    <button type="button" class="wn-dropdown-toggle" data-dropdown-toggle>
                        {{ $currentStatusLabel }}
                    </button>

                    <div class="wn-dropdown-menu" data-dropdown-menu>
                        @foreach($statusOptions as $value => $label)
                            <button
                                type="button"
                                class="wn-dropdown-option {{ (string) $currentStatus === (string) $value ? 'is-active' : '' }}"
                                data-dropdown-option
                                data-value="{{ $value }}"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="wn-head-actions">
                <button type="submit" class="wn-btn wn-btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 3H2l8 9.5V19l4 2v-8.5L22 3Z"/>
                    </svg>
                    Filter
                </button>

                <a href="{{ route('writer.news.index') }}" class="wn-btn wn-btn--light">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 3-6.7"/>
                        <path d="M3 4v6h6"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>

        @if($news->count())
            <div class="wn-table-wrap">
                <table class="wn-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Publish Target</th>
                            <th>Diperbarui</th>
                            <th style="width: 280px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($news as $item)
                            @php
                                $translation = method_exists($item, 'getTranslationByLocale')
                                    ? $item->getTranslationByLocale('id')
                                    : ($item->translations->firstWhere('locale', 'id') ?? $item->translations->first());

                                $title = $translation?->title ?? 'Tanpa Judul';
                                $excerpt = $translation?->excerpt ?? '';
                                $status = $item->status ?? 'draft';

                                $badgeClass = match ($status) {
                                    'draft' => 'wn-badge--draft',
                                    'pending_review' => 'wn-badge--review',
                                    'published' => 'wn-badge--published',
                                    'rejected' => 'wn-badge--rejected',
                                    default => 'wn-badge--default',
                                };

                                $statusLabel = match ($status) {
                                    'draft' => 'Draft',
                                    'pending_review' => 'Pending Review',
                                    'published' => 'Published',
                                    'rejected' => 'Rejected',
                                    default => ucfirst(str_replace('_', ' ', $status)),
                                };
                            @endphp

                            <tr>
                                <td>
                                    <div class="wn-news-title">{{ $title }}</div>
                                    @if($excerpt)
                                        <div class="wn-news-excerpt">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($excerpt), 120) }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="wn-meta">
                                        {{ $item->category?->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="wn-badge {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    @if(!$item->is_visible)
                                        <div style="margin-top:6px;">
                                            <span class="wn-badge wn-badge--draft">Hidden</span>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="wn-meta">
                                        {{ $item->published_at?->format('d M Y H:i') ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="wn-meta">
                                        {{ $item->updated_at?->format('d M Y H:i') ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="wn-actions">
                                        <a href="{{ route('writer.news.show', $item) }}" class="wn-btn wn-btn--sm wn-btn--light">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                                <path d="M14 2v6h6"/>
                                                <path d="M8 13h8"/>
                                                <path d="M8 17h5"/>
                                            </svg>
                                            Detail
                                        </a>

                                        <a href="{{ route('writer.news.edit', $item) }}" class="wn-btn wn-btn--sm wn-btn--edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9"/>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <a href="{{ route('writer.news.preview', $item) }}" target="_blank" class="wn-btn wn-btn--sm wn-btn--preview">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Preview
                                        </a>

                                        @if($item->status === 'published')
                                            <form
                                                method="POST"
                                                action="{{ route('writer.news.unpublish', $item) }}"
                                                class="js-confirm-submit"
                                                data-title="Unpublish berita ini?"
                                                data-text="Berita akan disembunyikan dari halaman publik."
                                                data-confirm="Ya, Unpublish"
                                                data-type="unpublish"
                                                data-icon="warning"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="wn-btn wn-btn--sm wn-btn--warning">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 3l18 18"/>
                                                        <path d="M10.6 10.6A3 3 0 0 0 14 14"/>
                                                        <path d="M9.9 4.25A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a18.3 18.3 0 0 1-3.1 4.5"/>
                                                        <path d="M6.6 6.6C3.7 8.6 2 12 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.4-.95"/>
                                                    </svg>
                                                    Unpublish
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                method="POST"
                                                action="{{ route('writer.news.publish', $item) }}"
                                                class="js-confirm-submit"
                                                data-title="Publish berita ini?"
                                                data-text="Pastikan berita sudah siap sebelum dipublikasikan."
                                                data-confirm="Ya, Publish"
                                                data-type="publish"
                                                data-icon="question"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="wn-btn wn-btn--sm wn-btn--success">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M20 6 9 17l-5-5"/>
                                                    </svg>
                                                    Publish
                                                </button>
                                            </form>
                                        @endif

                                        <form
                                            method="POST"
                                            action="{{ route('writer.news.destroy', $item) }}"
                                            class="js-delete-form"
                                            data-title="Hapus berita ini?"
                                            data-text="Berita yang dihapus tidak dapat dikembalikan."
                                            data-confirm="Ya, Hapus"
                                            data-type="delete"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="wn-btn wn-btn--sm wn-btn--danger">
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($news, 'links'))
                <div class="w-pagination">
                    {{ $news->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="wn-empty">
                <div class="wn-empty-icon">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                        <path d="M8 7h8"/>
                        <path d="M8 11h8"/>
                        <path d="M8 15h5"/>
                    </svg>
                </div>
                <div class="wn-empty-title">Belum ada news</div>
                <div class="wn-empty-desc">
                    Buat berita pertama untuk mulai mengisi konten publikasi website.
                </div>
                <a href="{{ route('writer.news.create') }}" class="wn-btn wn-btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14"/>
                        <path d="M5 12h14"/>
                    </svg>
                    Buat News
                </a>
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