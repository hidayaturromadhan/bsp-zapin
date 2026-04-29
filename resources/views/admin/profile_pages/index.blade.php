@extends('layouts.admin')

@section('title', 'Kelola Halaman Profil')

@section('content')
<style>
    .pp-wrap { max-width: 1180px; }
    .pp-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .pp-title { margin:0; font-size:30px; font-weight:800; color:#111827; letter-spacing:-.03em; }
    .pp-subtitle { margin-top:6px; font-size:14px; color:#6b7280; line-height:1.7; }
    .pp-card { background:#fff; border:1px solid #e5e7eb; border-radius:18px; overflow:hidden; box-shadow:0 10px 24px rgba(15,23,42,.04); }
    .pp-table { width:100%; border-collapse:collapse; }
    .pp-table th, .pp-table td { padding:14px 16px; border-bottom:1px solid #eef2f7; text-align:left; vertical-align:top; }
    .pp-table th { background:#f8fafc; color:#111827; font-size:13px; font-weight:800; }
    .pp-badge { display:inline-flex; align-items:center; min-height:28px; padding:0 10px; border-radius:999px; background:#eef5eb; color:#173f08; font-size:12px; font-weight:800; }
    .pp-btn { display:inline-flex; align-items:center; justify-content:center; min-height:38px; padding:0 14px; border-radius:10px; background:#173f08; color:#fff; text-decoration:none; font-size:13px; font-weight:700; }
</style>

<div class="pp-wrap">
    <div class="pp-head">
        <div>
            <h1 class="pp-title">Kelola Halaman Profil</h1>
            <div class="pp-subtitle">Controller khusus profile. Saat ini mendukung Tentang Kami, Visi &amp; Misi, Sejarah, Pemegang Saham, Struktur Organisasi, dan HSE custom.</div>
        </div>
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#eef8ee;color:#17603a;border:1px solid #cfe9d3;font-size:14px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="pp-card">
        <table class="pp-table">
            <thead>
                <tr>
                    <th style="width:60px;">No</th>
                    <th>Judul ID</th>
                    <th>Slug ID</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $index => $page)
                    @php
                        $tId = $page->translations->firstWhere('locale', 'id');
                        $template = match($tId?->slug) {
                            'tentang-kami' => 'Tentang Kami Custom',
                            'visi-misi' => 'Visi & Misi Custom',
                            'sejarah' => 'Sejarah Custom',
                            'pemegang-saham' => 'Pemegang Saham Custom',
                            'struktur-organisasi' => 'Struktur Organisasi Custom',
                            'health-safety-environment', 'hse' => 'HSE Custom',
                            default => 'Generic Profile',
                        };
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $tId?->title ?? '-' }}</td>
                        <td>{{ $tId?->slug ?? '-' }}</td>
                        <td><span class="pp-badge">{{ $template }}</span></td>
                        <td>{{ $page->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td>
                            <a href="{{ route('admin.profile-pages.edit', $page->id) }}" class="pp-btn">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#6b7280;">Belum ada halaman profil.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
