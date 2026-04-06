@extends('layouts.admin')

@section('content')
<style>
    .news-version-page { max-width: 1200px; }
    .news-version-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; overflow:hidden; box-shadow:0 8px 20px rgba(15,23,42,.04); }
    .news-version-table-wrap { overflow-x:auto; }
    .news-version-table { width:100%; border-collapse:collapse; min-width:980px; }
    .news-version-table th, .news-version-table td { padding:14px 12px; border-bottom:1px solid #e5e7eb; text-align:left; vertical-align:top; }
    .news-version-table th { background:#f8fafc; color:#111827; font-size:13px; font-weight:800; }
    .news-version-table tr:last-child td { border-bottom:none; }
</style>

<div class="news-version-page">
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h1 style="margin:0; font-size:24px; font-weight:800; color:#111827;">Versions News #{{ $news->id }}</h1>
            <div style="margin-top:6px; font-size:14px; color:#6b7280;">
                Restore per bundle untuk mengembalikan pengaturan global, Indonesia, dan English otomatis.
            </div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('admin.news.edit', $news) }}" style="display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700;">
                Kembali ke Edit
            </a>
            <a href="{{ route('admin.news.index') }}" style="display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700;">
                List News
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; font-size:14px; font-weight:600; background:#eef8ee; color:#17603a; border:1px solid #cfe9d3;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom:16px; padding:14px 16px; background:#fff; border:1px solid #e5e7eb; border-radius:14px; color:#475467; font-size:14px; line-height:1.7;">
        Klik <strong>Restore Bundle</strong> untuk mengembalikan satu versi berita lengkap:
        <strong>pengaturan global + terjemahan Indonesia + terjemahan English</strong>.
    </div>

    <div class="news-version-card">
        <div class="news-version-table-wrap">
            <table class="news-version-table">
                <thead>
                    <tr>
                        <th style="width:240px;">Bundle ID</th>
                        <th>Preview Isi Bundle</th>
                        <th style="width:180px;">Dibuat</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bundles as $bundleItem)
                        @php
                            $bundleId = $bundleItem->bundle_id;
                            $rows = $versions->get($bundleId, collect());

                            $global = $rows->firstWhere('locale', null);
                            $idRow = $rows->firstWhere('locale', 'id');
                            $enRow = $rows->firstWhere('locale', 'en');

                            $g = $global?->payload ?? [];
                            $idPayload = $idRow?->payload ?? [];
                            $enPayload = $enRow?->payload ?? [];

                            $createdAt = optional($rows->sortByDesc('created_at')->first())->created_at;
                        @endphp

                        <tr>
                            <td style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size:12px; color:#334155; word-break:break-all;">
                                {{ $bundleId }}
                            </td>

                            <td style="font-size:13px; color:#374151; line-height:1.7;">
                                <div style="margin-bottom:8px;">
                                    <strong>Global:</strong>
                                    status={{ $g['status'] ?? '-' }},
                                    visible={{ isset($g['is_visible']) ? ($g['is_visible'] ? 'true' : 'false') : '-' }},
                                    published_at={{ $g['published_at'] ?? '-' }}
                                </div>

                                <div>
                                    <strong>ID:</strong>
                                    {{ $idPayload['title'] ?? '-' }}
                                    <span style="color:#6b7280;">/ {{ $idPayload['slug'] ?? '-' }}</span>
                                </div>

                                <div>
                                    <strong>EN:</strong>
                                    {{ $enPayload['title'] ?? '-' }}
                                    <span style="color:#6b7280;">/ {{ $enPayload['slug'] ?? '-' }}</span>
                                </div>

                                <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
                                    @if(!empty($idPayload['slug']))
                                        <a target="_blank" href="{{ route('news.show', ['locale' => 'id', 'slug' => $idPayload['slug']]) }}" style="color:#173f08; text-decoration:none; font-weight:700;">
                                            Preview ID
                                        </a>
                                    @endif

                                    @if(!empty($enPayload['slug']))
                                        <a target="_blank" href="{{ route('news.show', ['locale' => 'en', 'slug' => $enPayload['slug']]) }}" style="color:#173f08; text-decoration:none; font-weight:700;">
                                            Preview EN
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <td>{{ $createdAt ?? '-' }}</td>

                            <td>
                                <form method="POST" action="{{ route('admin.news.bundles.restore', ['news' => $news->id, 'bundle' => $bundleId]) }}">
                                    @csrf
                                    <button type="submit" style="min-height:38px; padding:0 14px; border:none; border-radius:10px; background:#173f08; color:#fff; font:inherit; font-size:13px; font-weight:700; cursor:pointer;" onclick="return confirm('Restore bundle ini? (GLOBAL + ID + EN)')">
                                        Restore Bundle
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#6b7280; padding:28px 16px;">
                                Belum ada versi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:18px; display:flex; justify-content:center;">
        {{ $bundles->links() }}
    </div>
</div>
@endsection