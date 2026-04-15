@extends('layouts.admin')

@section('content')
<style>
    .admin-page-wrap {
        padding: 28px 32px;
    }

    .admin-page-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .admin-page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.02em;
    }

    .admin-page-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.6;
    }

    .admin-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
        overflow: hidden;
    }

    .admin-alert-success {
        margin-bottom: 16px;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid #bbf7d0;
        background: #ecfdf3;
        color: #166534;
        font-size: 14px;
        font-weight: 600;
    }

    .admin-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 920px;
    }

    .admin-table thead th {
        text-align: left;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: #6b7280;
        background: #f9fafb;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .admin-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
        font-size: 14px;
        color: #111827;
    }

    .admin-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .admin-table tbody tr {
        transition: background .18s ease;
    }

    .admin-table tbody tr:hover {
        background: #fafcfb;
    }

    .admin-cell-main {
        font-weight: 700;
        color: #111827;
        line-height: 1.45;
    }

    .admin-cell-muted {
        color: #6b7280;
    }

    .admin-slug {
        display: inline-block;
        max-width: 220px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.4;
        word-break: break-all;
    }

    .admin-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .admin-badge--active {
        background: #ecfdf3;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .admin-badge--inactive {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }

    .admin-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .admin-action-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 12px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: all .18s ease;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .admin-action-link--edit {
        background: #eef5eb;
        color: #173f08;
        border-color: #dbe8d5;
    }

    .admin-action-link--edit:hover {
        background: #e2efdd;
        transform: translateY(-1px);
    }

    .admin-action-link--versions {
        background: #f3f4f6;
        color: #374151;
        border-color: #e5e7eb;
    }

    .admin-action-link--versions:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }

    .admin-action-link--view {
        background: #fff;
        color: #1f2937;
        border-color: #d1d5db;
    }

    .admin-action-link--view:hover {
        background: #f9fafb;
        transform: translateY(-1px);
    }

    .admin-pagination {
        padding: 16px 18px 18px;
        border-top: 1px solid #f1f5f9;
        background: #fff;
    }

    @media (max-width: 768px) {
        .admin-page-wrap {
            padding: 20px 16px;
        }

        .admin-page-title {
            font-size: 21px;
        }

        .admin-table thead th,
        .admin-table tbody td {
            padding: 14px 12px;
        }
    }
</style>

<div class="admin-page-wrap">
    <div class="admin-page-head">
        <div>
            <h1 class="admin-page-title">Halaman</h1>
            <p class="admin-page-subtitle">
                Kelola halaman bilingual, slug, status aktif, dan akses cepat ke versi publik.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul (ID)</th>
                        <th>Judul (EN)</th>
                        <th>Slug (ID)</th>
                        <th>Slug (EN)</th>
                        <th>Active</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $p)
                        @php
                            $tId = $p->translations->firstWhere('locale', 'id');
                            $tEn = $p->translations->firstWhere('locale', 'en');
                        @endphp
                        <tr>
                            <td>
                                <div class="admin-cell-main">{{ $tId?->title ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="admin-cell-main">{{ $tEn?->title ?? '-' }}</div>
                            </td>
                            <td>
                                @if($tId?->slug)
                                    <span class="admin-slug">{{ $tId->slug }}</span>
                                @else
                                    <span class="admin-cell-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($tEn?->slug)
                                    <span class="admin-slug">{{ $tEn->slug }}</span>
                                @else
                                    <span class="admin-cell-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->is_active)
                                    <span class="admin-badge admin-badge--active">Ya</span>
                                @else
                                    <span class="admin-badge admin-badge--inactive">Tidak</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.pages.edit', $p) }}" class="admin-action-link admin-action-link--edit">
                                        Edit
                                    </a>

                                    <a href="{{ route('admin.pages.versions', $p) }}" class="admin-action-link admin-action-link--versions">
                                        Versions
                                    </a>

                                    @if($tId?->slug)
                                        <a
                                            href="{{ route('page.show', ['locale' => 'id', 'slug' => $tId->slug]) }}"
                                            target="_blank"
                                            class="admin-action-link admin-action-link--view"
                                        >
                                            Lihat ID
                                        </a>
                                    @endif

                                    @if($tEn?->slug)
                                        <a
                                            href="{{ route('page.show', ['locale' => 'en', 'slug' => $tEn->slug]) }}"
                                            target="_blank"
                                            class="admin-action-link admin-action-link--view"
                                        >
                                            Lihat EN
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-cell-muted" style="text-align:center; padding: 28px 16px;">
                                Belum ada data halaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection