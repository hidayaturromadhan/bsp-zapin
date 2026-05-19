@extends('layouts.admin')

@section('title', 'Investor Highlight')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Investor Highlight</span>
        </div>
        <h1 class="a-page-title">Investor Highlight</h1>
        <p class="a-page-desc">Kelola highlight pill pada hero Hubungan Investor</p>
    </div>

    <a href="{{ route('admin.investor-highlight-items.create') }}" class="a-btn a-btn--primary">
        Tambah Highlight
    </a>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Highlight</div>
            <div class="a-card-desc">Total {{ $items->total() }} item</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th width="48">#</th>
                    <th>Label ID</th>
                    <th>Label EN</th>
                    <th width="90" style="text-align:center">Urutan</th>
                    <th width="100" style="text-align:center">Status</th>
                    <th width="130" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $items->firstItem() + $loop->index }}</td>
                        <td style="font-weight:600">{{ $item->label_id }}</td>
                        <td>{{ $item->label_en }}</td>
                        <td style="text-align:center">{{ $item->sort_order }}</td>
                        <td style="text-align:center">
                            @if($item->is_active)
                                <span class="a-badge a-badge--green">Aktif</span>
                            @else
                                <span class="a-badge a-badge--red">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center">
                                <a href="{{ route('admin.investor-highlight-items.edit', $item) }}"
                                   class="a-btn a-btn--secondary a-btn--sm">Edit</a>

                                <form action="{{ route('admin.investor-highlight-items.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus item ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="a-btn a-btn--danger a-btn--sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="a-empty">
                                <div class="a-empty-title">Belum ada highlight</div>
                                <div class="a-empty-desc">Tambahkan highlight untuk hero Hubungan Investor</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px">
    {{ $items->links('vendor.pagination.admin') }}
</div>

@endsection