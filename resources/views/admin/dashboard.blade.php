@extends('layouts.admin')

@section('content')
<style>
.dashboard-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin-bottom:24px;
}
.card {
    background:#fff;
    border-radius:16px;
    padding:20px;
    border:1px solid #e5e7eb;
}
.card h3 {
    margin:0;
    font-size:14px;
    color:#6b7280;
}
.card .value {
    font-size:28px;
    font-weight:800;
    margin-top:6px;
}
.section {
    margin-top:30px;
}
.section h2 {
    font-size:20px;
    margin-bottom:14px;
}
.table {
    width:100%;
    border-collapse:collapse;
}
.table th, .table td {
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:14px;
}
.badge {
    padding:4px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}
.badge.green { background:#e6f4ea; color:#2e7d32; }
.badge.yellow { background:#fff7e0; color:#a16207; }
.badge.red { background:#fdecea; color:#b91c1c; }
</style>

<h1>Dashboard Admin</h1>

<div class="dashboard-grid">
    <div class="card">
        <h3>Total News</h3>
        <div class="value">{{ $totalNews }}</div>
    </div>
    <div class="card">
        <h3>Published</h3>
        <div class="value">{{ $published }}</div>
    </div>
    <div class="card">
        <h3>In Review</h3>
        <div class="value">{{ $inReview }}</div>
    </div>
    <div class="card">
        <h3>Rejected</h3>
        <div class="value">{{ $rejected }}</div>
    </div>
</div>

<div class="section">
    <h2>Aktivitas Terakhir</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Berita</th>
            <th>Action</th>
            <th>User</th>
            <th>Waktu</th>
        </tr>
        </thead>
        <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ optional($log->news)->getTranslationByLocale('id')->title ?? '-' }}</td>
                <td>
                    <span class="badge {{ $log->action == 'approved' ? 'green' : ($log->action == 'rejected' ? 'red' : 'yellow') }}">
                        {{ strtoupper($log->action) }}
                    </span>
                </td>
                <td>{{ $log->user->name ?? '-' }}</td>
                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Tidak ada data</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection