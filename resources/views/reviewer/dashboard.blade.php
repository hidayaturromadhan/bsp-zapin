@extends('layouts.reviewer')

@section('content')
<style>
    .rd-page {
        max-width: 1280px;
    }

    .rd-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .rd-title {
        margin: 0;
        font-size: 34px;
        line-height: 1.1;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.03em;
    }

    .rd-subtitle {
        margin: 8px 0 0;
        font-size: 15px;
        color: #6b7280;
        line-height: 1.7;
        max-width: 780px;
    }

    .rd-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0 16px;
        border-radius: 12px;
        background: #173f08;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        transition: .18s ease;
    }

    .rd-action:hover {
        background: #21560e;
    }

    .rd-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .rd-card {
        background: #fff;
        border-radius: 18px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
    }

    .rd-card-title {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
        font-weight: 700;
    }

    .rd-card-value {
        margin-top: 8px;
        font-size: 30px;
        line-height: 1;
        font-weight: 800;
        color: #111827;
    }

    .rd-card-help {
        margin-top: 10px;
        font-size: 12px;
        line-height: 1.7;
        color: #6b7280;
    }

    .rd-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(320px, .8fr);
        gap: 20px;
    }

    .rd-panel {
        background: #fff;
        border-radius: 18px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
    }

    .rd-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .rd-panel-title {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: #111827;
    }

    .rd-panel-link {
        font-size: 13px;
        font-weight: 700;
        color: #173f08;
        text-decoration: none;
    }

    .rd-panel-link:hover {
        text-decoration: underline;
    }

    .rd-list {
        display: grid;
        gap: 14px;
    }

    .rd-item {
        padding: 14px 0;
        border-bottom: 1px solid #eef2f7;
    }

    .rd-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .rd-item-title {
        font-size: 17px;
        font-weight: 800;
        line-height: 1.45;
        color: #111827;
    }

    .rd-item-meta {
        margin-top: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        font-size: 13px;
        color: #6b7280;
    }

    .rd-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
    }

    .rd-badge.blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .rd-badge.green {
        background: #e9f8ee;
        color: #17603a;
    }

    .rd-badge.red {
        background: #fee2e2;
        color: #b42318;
    }

    .rd-empty {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.7;
    }

    .rd-stats-list {
        display: grid;
        gap: 14px;
    }

    .rd-stat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #eef2f7;
    }

    .rd-stat-label {
        font-size: 14px;
        font-weight: 700;
        color: #374151;
    }

    .rd-stat-value {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
    }

    .rd-quick-actions {
        display: grid;
        gap: 10px;
    }

    .rd-quick-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 14px;
        border-radius: 12px;
        background: #fff;
        color: #173f08;
        font-size: 14px;
        font-weight: 700;
        border: 1px solid #dce7d8;
        text-decoration: none;
        transition: .18s ease;
    }

    .rd-quick-btn:hover {
        background: #f4faf2;
    }

    @media (max-width: 980px) {
        .rd-grid {
            grid-template-columns: 1fr;
        }

        .rd-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="rd-page">
    <div class="rd-head">
        <div>
            <h1 class="rd-title">Reviewer Dashboard</h1>
            <p class="rd-subtitle">
                Tinjau antrian review, lakukan revisi bila diperlukan, lalu approve atau reject berita
                berdasarkan jadwal publish yang sudah kamu tentukan sendiri.
            </p>
        </div>

        <a href="{{ route('reviewer.news.index') }}" class="rd-action">
            Buka Review Queue
        </a>
    </div>

    <div class="rd-grid">
        <div class="rd-card">
            <p class="rd-card-title">Pending Review</p>
            <div class="rd-card-value">{{ $pending }}</div>
            <div class="rd-card-help">
                Berita yang sedang menunggu peninjauan reviewer.
            </div>
        </div>

        <div class="rd-card">
            <p class="rd-card-title">Approved Today</p>
            <div class="rd-card-value">{{ $approvedToday }}</div>
            <div class="rd-card-help">
                Jumlah berita yang kamu setujui hari ini.
            </div>
        </div>

        <div class="rd-card">
            <p class="rd-card-title">Rejected</p>
            <div class="rd-card-value">{{ $rejected }}</div>
            <div class="rd-card-help">
                Total berita yang dikembalikan ke penulis.
            </div>
        </div>
    </div>

    <div class="rd-layout">
        <div class="rd-panel">
            <div class="rd-panel-head">
                <h2 class="rd-panel-title">Queue Terbaru</h2>
                <a href="{{ route('reviewer.news.index') }}" class="rd-panel-link">Lihat semua</a>
            </div>

            <div class="rd-list">
                @forelse($pendingNews as $news)
                    @php
                        $translation = $news->getTranslationByLocale('id');
                    @endphp

                    <div class="rd-item">
                        <div class="rd-item-title">
                            {{ $translation?->title ?? 'Tanpa Judul' }}
                        </div>

                        <div class="rd-item-meta">
                            <span class="rd-badge blue">IN REVIEW</span>

                            @if($news->author)
                                <span>Penulis: {{ $news->author->name }}</span>
                            @endif

                            @if($news->created_at)
                                <span>Masuk {{ $news->created_at->format('d M Y H:i') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rd-empty">
                        Tidak ada antrian review saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rd-panel">
            <div class="rd-panel-head">
                <h2 class="rd-panel-title">Ringkasan Review</h2>
            </div>

            <div class="rd-stats-list">
                <div class="rd-stat-row">
                    <span class="rd-stat-label">Pending</span>
                    <span class="rd-stat-value">{{ $pending }}</span>
                </div>

                <div class="rd-stat-row">
                    <span class="rd-stat-label">Approved Today</span>
                    <span class="rd-stat-value">{{ $approvedToday }}</span>
                </div>

                <div class="rd-stat-row">
                    <span class="rd-stat-label">Rejected</span>
                    <span class="rd-stat-value">{{ $rejected }}</span>
                </div>
            </div>

            <div style="height:18px;"></div>

            <div class="rd-panel-head" style="margin-bottom:12px;">
                <h2 class="rd-panel-title" style="font-size:18px;">Quick Actions</h2>
            </div>

            <div class="rd-quick-actions">
                <a href="{{ route('reviewer.news.index') }}" class="rd-quick-btn">
                    Buka Review Queue
                </a>

                <a href="{{ route('reviewer.news.index', ['status' => 'rejected']) }}" class="rd-quick-btn">
                    Lihat Rejected
                </a>

                <a href="{{ route('reviewer.news.index', ['status' => 'published']) }}" class="rd-quick-btn">
                    Lihat Published
                </a>
            </div>
        </div>
    </div>
</div>
@endsection