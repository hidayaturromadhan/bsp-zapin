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

    .admin-create-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        background: #173f08;
        border: 1px solid #173f08;
        box-shadow: 0 8px 18px rgba(23, 63, 8, .16);
        transition: all .18s ease;
        white-space: nowrap;
    }

    .admin-create-btn:hover {
        background: #21560e;
        border-color: #21560e;
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(23, 63, 8, .22);
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
        min-width: 860px;
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
        vertical-align: middle;
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

    .admin-preview {
        width: 150px;
        height: 78px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background: #f3f4f6;
        box-shadow: 0 4px 10px rgba(15, 23, 42, .04);
    }

    .admin-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .admin-cell-main {
        font-weight: 700;
        color: #111827;
        line-height: 1.45;
    }

    .admin-cell-muted {
        color: #6b7280;
    }

    .admin-order-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        min-height: 30px;
        padding: 0 10px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #374151;
        font-size: 12px;
        font-weight: 800;
        border: 1px solid #e5e7eb;
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
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .admin-action-link,
    .admin-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        transition: all .18s ease;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .admin-action-link {
        text-decoration: none;
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

    .admin-action-btn {
        background: #fff1f2;
        color: #b42318;
        border-color: #fecdd3;
        cursor: pointer;
    }

    .admin-action-btn:hover {
        background: #ffe4e6;
        transform: translateY(-1px);
    }

    .admin-inline-form {
        display: inline;
        margin: 0;
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

        .admin-preview {
            width: 128px;
            height: 68px;
        }
    }
</style>

<div class="admin-page-wrap">
    <div class="admin-page-head">
        <div>
            <h1 class="admin-page-title">Slider</h1>
            <p class="admin-page-subtitle">
                Kelola gambar slider, urutan tampil, dan status aktif untuk halaman utama.
            </p>
        </div>

        <a href="{{ route('admin.sliders.create') }}" class="admin-create-btn">
            + Tambah Slider
        </a>
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
                        <th>Preview</th>
                        <th>Title</th>
                        <th>Order</th>
                        <th>Active</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $s)
                        <tr>
                            <td style="width: 180px;">
                                <div class="admin-preview">
                                    <img src="{{ asset($s->image_path) }}" alt="{{ $s->title ?: 'Slider preview' }}">
                                </div>
                            </td>

                            <td>
                                <div class="admin-cell-main">{{ $s->title ?: '-' }}</div>
                            </td>

                            <td>
                                <span class="admin-order-badge">{{ $s->sort_order }}</span>
                            </td>

                            <td>
                                @if($s->is_active)
                                    <span class="admin-badge admin-badge--active">Ya</span>
                                @else
                                    <span class="admin-badge admin-badge--inactive">Tidak</span>
                                @endif
                            </td>

                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.sliders.edit', $s) }}" class="admin-action-link admin-action-link--edit">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.sliders.destroy', $s) }}" class="admin-inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="admin-action-btn"
                                            onclick="return confirm('Hapus slider ini?')"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-cell-muted" style="text-align:center; padding: 28px 16px;">
                                Belum ada data slider.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            {{ $sliders->links('vendor.pagination.admin') }}
        </div>
    </div>
</div>
@endsection