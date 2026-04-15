@extends('layouts.reviewer')

@section('content')
<style>
    .rl-page { max-width: 1080px; }
    .rl-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .rl-title { margin:0; font-size:24px; font-weight:800; color:#111827; }
    .rl-subtitle { margin-top:6px; font-size:14px; color:#6b7280; }
    .rl-back {
        display:inline-flex; align-items:center; justify-content:center;
        min-height:40px; padding:0 14px; border-radius:10px;
        border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700;
    }

    .rl-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; box-shadow:0 8px 20px rgba(15,23,42,.04); overflow:hidden; }
    .rl-cover { padding:18px 20px; border-bottom:1px solid #e5e7eb; background:#f8fafc; }
    .rl-cover-title { font-size:20px; font-weight:800; line-height:1.4; color:#111827; }
    .rl-cover-meta { margin-top:8px; font-size:13px; line-height:1.7; color:#6b7280; }

    .rl-timeline { padding:22px 20px; }
    .rl-item { position:relative; padding-left:26px; margin-bottom:22px; }
    .rl-item:last-child { margin-bottom:0; }
    .rl-item::before {
        content:''; position:absolute; left:5px; top:2px; width:10px; height:10px; border-radius:50%; background:#173f08;
    }
    .rl-item::after {
        content:''; position:absolute; left:9px; top:16px; bottom:-18px; width:2px; background:#e5e7eb;
    }
    .rl-item:last-child::after { display:none; }

    .rl-action { font-size:14px; font-weight:800; color:#111827; }
    .rl-meta { margin-top:4px; font-size:12px; color:#6b7280; line-height:1.7; }
    .rl-note { margin-top:8px; padding:10px 12px; border-radius:10px; background:#f8fafc; border:1px solid #e5e7eb; font-size:13px; color:#374151; line-height:1.7; }
    .rl-empty { padding:32px 20px; text-align:center; color:#6b7280; font-size:14px; }
</style>

@php
    $tId = $news->translations->firstWhere('locale', 'id');
    $tEn = $news->translations->firstWhere('locale', 'en');
@endphp

<div class="rl-page">
    <div class="rl-head">
        <div>
            <h1 class="rl-title">Audit Log</h1>
            <div class="rl-subtitle">Riwayat aktivitas berita pada panel reviewer.</div>
        </div>

        <a href="{{ route('reviewer.news.index') }}" class="rl-back">Kembali</a>
    </div>

    <div class="rl-card">
        <div class="rl-cover">
            <div class="rl-cover-title">
                {{ $tId?->title ?? '-' }}
                <span style="color:#9ca3af; font-weight:600;">/</span>
                {{ $tEn?->title ?? '-' }}
            </div>

            <div class="rl-cover-meta">
                ID: /{{ $tId?->slug ?? '-' }}<br>
                EN: /{{ $tEn?->slug ?? '-' }}
            </div>
        </div>

        <div class="rl-timeline">
            @forelse($news->logs as $log)
                <div class="rl-item">
                    <div class="rl-action">{{ ucfirst($log->action) }}</div>
                    <div class="rl-meta">
                        {{ $log->user?->name ?? 'Unknown User' }} • {{ $log->created_at?->format('Y-m-d H:i') ?? '-' }}
                    </div>

                    @if(!empty($log->note))
                        <div class="rl-note">{{ $log->note }}</div>
                    @endif
                </div>
            @empty
                <div class="rl-empty">Belum ada audit log untuk berita ini.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection