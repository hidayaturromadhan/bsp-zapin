@extends('layouts.writer')

@section('content')
<style>
    .wd-head { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap; margin-bottom:22px; }
    .wd-title { margin:0; font-size:34px; font-weight:800; color:#111827; }
    .wd-subtitle { margin:8px 0 0; font-size:15px; color:#6b7280; }
    .wd-action { display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:0 16px; border-radius:12px; background:#173f08; color:#fff; font-weight:700; text-decoration:none; }
    .wd-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:18px; margin-bottom:24px; }
    .wd-card, .wd-panel { background:#fff; border-radius:18px; padding:20px; border:1px solid #e5e7eb; box-shadow:0 10px 24px rgba(15,23,42,.04); }
    .wd-card-title { margin:0; font-size:14px; color:#6b7280; font-weight:700; }
    .wd-card-value { margin-top:8px; font-size:30px; font-weight:800; color:#111827; }
    .wd-panel-head { display:flex; justify-content:space-between; align-items:center; gap:14px; flex-wrap:wrap; margin-bottom:16px; }
    .wd-panel-title { margin:0; font-size:20px; font-weight:800; color:#111827; }
    .wd-panel-link { font-size:13px; font-weight:700; color:#173f08; text-decoration:none; }
    .wd-list { display:grid; gap:14px; }
    .wd-item { padding:14px 0; border-bottom:1px solid #eef2f7; }
    .wd-item:last-child { border-bottom:none; padding-bottom:0; }
    .wd-item-title { font-size:17px; font-weight:800; line-height:1.45; color:#111827; }
    .wd-item-meta { margin-top:8px; display:flex; gap:10px; flex-wrap:wrap; font-size:13px; color:#6b7280; align-items:center; }
    .wd-badge { display:inline-flex; align-items:center; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:800; }
    .wd-badge.yellow { background:#fff7e0; color:#a16207; }
    .wd-badge.green { background:#e6f4ea; color:#17603a; }
    .wd-badge.red { background:#fdecea; color:#b42318; }
    .wd-empty { color:#6b7280; font-size:14px; }
</style>

<div class="wd-head">
    <div>
        <h1 class="wd-title">Writer Dashboard</h1>
        <p class="wd-subtitle">Kelola draft, pantau status review, dan lanjutkan penulisan berita perusahaan.</p>
    </div>

    <a href="{{ route('writer.news.create') }}" class="wd-action">+ Tulis News</a>
</div>

<div class="wd-grid">
    <div class="wd-card">
        <p class="wd-card-title">Draft</p>
        <div class="wd-card-value">{{ $draft }}</div>
    </div>

    <div class="wd-card">
        <p class="wd-card-title">In Review</p>
        <div class="wd-card-value">{{ $inReview }}</div>
    </div>

    <div class="wd-card">
        <p class="wd-card-title">Published</p>
        <div class="wd-card-value">{{ $published }}</div>
    </div>
</div>

<div class="wd-panel">
    <div class="wd-panel-head">
        <h2 class="wd-panel-title">News Saya</h2>
        <a href="{{ route('writer.news.index') }}" class="wd-panel-link">Lihat semua</a>
    </div>

    <div class="wd-list">
        @forelse($myNews as $news)
            @php
                $translation = $news->getTranslationByLocale('id');
                $badgeClass = $news->status === 'published'
                    ? 'green'
                    : ($news->status === 'rejected' ? 'red' : 'yellow');
            @endphp

            <div class="wd-item">
                <div class="wd-item-title">{{ $translation?->title ?? 'Tanpa Judul' }}</div>

                <div class="wd-item-meta">
                    <span class="wd-badge {{ $badgeClass }}">{{ strtoupper($news->status) }}</span>

                    @if($news->updated_at)
                        <span>Update {{ $news->updated_at->format('d M Y H:i') }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="wd-empty">Belum ada berita yang dibuat.</div>
        @endforelse
    </div>
</div>
@endsection