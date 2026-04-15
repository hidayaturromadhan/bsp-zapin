@extends('layouts.admin')

@section('title', 'News Logs')

@section('content')

@php
    $tId = $news->translations->firstWhere('locale', 'id');
@endphp

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <a href="{{ route('admin.news.index') }}" style="color:var(--text3)">News</a>
            <span class="a-breadcrumb-sep">›</span>
            <a href="{{ route('admin.news.show', $news) }}" style="color:var(--text3)">Detail</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Logs</span>
        </div>
        <h1 class="a-page-title">Audit Logs News</h1>
        <p class="a-page-desc">{{ $tId->title ?? '-' }}</p>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('admin.news.show', $news) }}" class="a-btn a-btn--secondary">Kembali ke Detail</a>
        <a href="{{ route('admin.news.index') }}" class="a-btn a-btn--secondary">List News</a>
    </div>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Riwayat Aktivitas</div>
            <div class="a-card-desc">Mencatat perubahan dan proses review berita</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th width="70">#</th>
                    <th width="180">Waktu</th>
                    <th width="160">User</th>
                    <th width="140">Action</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news->logs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->created_at ? $log->created_at->format('d M Y H:i:s') : '-' }}</td>
                        <td>{{ $log->user->name ?? '-' }}</td>
                        <td>
                            <span class="a-badge a-badge--gray">{{ $log->action }}</span>
                        </td>
                        <td>{{ $log->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="a-empty">
                                <div class="a-empty-title">Belum ada logs</div>
                                <div class="a-empty-desc">Audit log untuk berita ini belum tersedia.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection