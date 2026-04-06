@extends('layouts.admin')

@section('content')
<div class="container" style="max-width:1150px;">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap; margin-bottom:18px;">
        <div>
            <h1 style="margin:0; font-size:26px; font-weight:800;">Kelola Pelanggan & Mitra Bisnis</h1>
            <p style="margin:6px 0 0; color:#6b7280;">Kelola logo yang ditampilkan pada homepage perusahaan.</p>
        </div>

        <a href="{{ route('admin.partners.create') }}"
           style="display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 16px; border-radius:10px; background:#173f08; color:#fff; text-decoration:none; font-weight:700;">
            + Tambah Data
        </a>
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; background:#eef8ee; color:#17603a; border:1px solid #cfe9d3;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; background:#fff1f2; color:#b42318; border:1px solid #fecdd3;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.partners.index') }}" style="margin-bottom:16px;">
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <input
                type="text"
                name="q"
                value="{{ $q }}"
                placeholder="Cari nama..."
                style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #d1d5db; border-radius:10px;"
            >

            <select name="category"
                    style="min-width:220px; padding:10px 12px; border:1px solid #d1d5db; border-radius:10px; background:#fff;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}" {{ $category === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    style="padding:10px 16px; border-radius:10px; border:1px solid #d1d5db; background:#fff; cursor:pointer; font-weight:700;">
                Cari
            </button>
        </div>
    </form>

    <div style="margin-bottom:14px; padding:14px 16px; border-radius:12px; background:#f8fafc; border:1px solid #e5e7eb; color:#475467; font-size:13px; line-height:1.6;">
        Urutan tampilan di homepage diatur otomatis per kategori. Admin cukup memilih kategori, mengisi nama, mengunggah logo, lalu aktifkan data jika ingin ditampilkan.
    </div>

    <div style="overflow:auto; background:#fff; border:1px solid #e5e7eb; border-radius:16px;">
        <table style="width:100%; border-collapse:collapse; min-width:920px;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:14px; text-align:left; border-bottom:1px solid #e5e7eb;">Logo</th>
                    <th style="padding:14px; text-align:left; border-bottom:1px solid #e5e7eb;">Nama</th>
                    <th style="padding:14px; text-align:left; border-bottom:1px solid #e5e7eb;">Kategori</th>
                    <th style="padding:14px; text-align:left; border-bottom:1px solid #e5e7eb;">Website</th>
                    <th style="padding:14px; text-align:center; border-bottom:1px solid #e5e7eb;">Status</th>
                    <th style="padding:14px; text-align:right; border-bottom:1px solid #e5e7eb;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $partner)
                    <tr>
                        <td style="padding:14px; border-bottom:1px solid #f1f5f9;">
                            @if($partner->logo_path)
                                <img src="{{ asset($partner->logo_path) }}"
                                     alt="{{ $partner->name }}"
                                     style="width:100px; height:56px; object-fit:contain; background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:6px;">
                            @else
                                <span style="color:#9ca3af;">Tidak ada logo</span>
                            @endif
                        </td>

                        <td style="padding:14px; border-bottom:1px solid #f1f5f9; font-weight:700;">
                            {{ $partner->name }}
                        </td>

                        <td style="padding:14px; border-bottom:1px solid #f1f5f9;">
                            @if($partner->category === \App\Models\Partner::CATEGORY_CUSTOMER)
                                <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:#ecfdf3; color:#027a48; font-size:12px; font-weight:700;">
                                    Pelanggan
                                </span>
                            @else
                                <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:#eff6ff; color:#1d4ed8; font-size:12px; font-weight:700;">
                                    Mitra Bisnis
                                </span>
                            @endif
                        </td>

                        <td style="padding:14px; border-bottom:1px solid #f1f5f9;">
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer">
                                    {{ $partner->website_url }}
                                </a>
                            @else
                                <span style="color:#9ca3af;">-</span>
                            @endif
                        </td>

                        <td style="padding:14px; border-bottom:1px solid #f1f5f9; text-align:center;">
                            @if($partner->is_active)
                                <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:#eef8ee; color:#17603a; font-size:12px; font-weight:700;">
                                    Aktif
                                </span>
                            @else
                                <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:#f3f4f6; color:#6b7280; font-size:12px; font-weight:700;">
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <td style="padding:14px; border-bottom:1px solid #f1f5f9;">
                            <div style="display:flex; justify-content:flex-end; gap:8px;">
                                <a href="{{ route('admin.partners.edit', $partner) }}"
                                   style="padding:8px 12px; border-radius:8px; border:1px solid #d1d5db; text-decoration:none; color:#111827;">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="padding:8px 12px; border-radius:8px; border:1px solid #fecaca; background:#fff1f2; color:#b42318; cursor:pointer;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:24px; text-align:center; color:#6b7280;">
                            Belum ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">
        {{ $partners->links() }}
    </div>
</div>
@endsection