@extends('layouts.admin')

@section('title', 'Monitoring TJSL')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>TJSL</span>
        </div>
        <h1 class="a-page-title">Monitoring TJSL</h1>
        <p class="a-page-desc">Admin hanya dapat memantau aktivitas TJSL yang dikelola writer dan reviewer.</p>
    </div>
</div>

@if(session('success'))
    <div class="a-alert a-alert--success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="a-alert a-alert--danger">{{ session('error') }}</div>
@endif

<style>
    .tjsl-hero {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        background: linear-gradient(135deg, #0b3d05 0%, #14520b 48%, #0f2f08 100%);
        padding: 24px;
        margin-bottom: 18px;
        box-shadow: 0 14px 34px rgba(15, 47, 8, .18);
        color: #fff;
    }

    .tjsl-hero::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -70px;
        top: -90px;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        filter: blur(4px);
    }

    .tjsl-hero::after {
        content: "";
        position: absolute;
        width: 160px;
        height: 160px;
        left: 36%;
        bottom: -95px;
        border-radius: 999px;
        background: rgba(134, 239, 172, .14);
        filter: blur(2px);
    }

    .tjsl-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 18px;
    }

    .tjsl-hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.16);
        font-size: 12px;
        font-weight: 800;
        color: #d9f99d;
        margin-bottom: 12px;
    }

    .tjsl-hero-title {
        font-size: 26px;
        font-weight: 900;
        letter-spacing: -0.03em;
        margin: 0;
    }

    .tjsl-hero-desc {
        margin: 8px 0 0;
        max-width: 680px;
        color: rgba(255,255,255,.78);
        font-size: 14px;
        line-height: 1.7;
    }

    .tjsl-hero-info {
        flex-shrink: 0;
        text-align: right;
    }

    .tjsl-hero-number {
        font-size: 34px;
        font-weight: 950;
        line-height: 1;
    }

    .tjsl-hero-label {
        margin-top: 4px;
        font-size: 12px;
        font-weight: 700;
        color: rgba(255,255,255,.68);
    }

    .tjsl-summary-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .tjsl-summary-card {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, .95);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
        padding: 16px;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .tjsl-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 30px rgba(15, 23, 42, .09);
        border-color: rgba(22, 101, 52, .22);
    }

    .tjsl-summary-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .tjsl-summary-label {
        font-size: 12px;
        font-weight: 800;
        color: #64748b;
    }

    .tjsl-summary-icon {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tjsl-summary-value {
        margin-top: 12px;
        font-size: 28px;
        line-height: 1;
        font-weight: 950;
        letter-spacing: -0.04em;
        color: #0f172a;
    }

    .tjsl-summary-sub {
        margin-top: 7px;
        font-size: 11.5px;
        font-weight: 700;
        color: #94a3b8;
    }

    .tjsl-filter-card {
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, .95);
        box-shadow: 0 12px 28px rgba(15, 23, 42, .06);
        padding: 18px;
        margin-bottom: 18px;
    }

    .tjsl-filter-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 14px;
    }

    .tjsl-filter-title {
        font-size: 16px;
        font-weight: 900;
        color: #0f172a;
    }

    .tjsl-filter-desc {
        margin-top: 3px;
        font-size: 12.5px;
        color: #64748b;
    }

    .tjsl-filter-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 260px auto auto;
        gap: 12px;
        align-items: end;
    }

    .tjsl-table-card {
        overflow: hidden;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, .95);
        box-shadow: 0 12px 32px rgba(15, 23, 42, .07);
    }

    .tjsl-table-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        padding: 20px 22px;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
    }

    .tjsl-table-title {
        font-size: 17px;
        font-weight: 950;
        color: #0f172a;
    }

    .tjsl-table-desc {
        margin-top: 4px;
        font-size: 13px;
        color: #64748b;
    }

    .tjsl-table-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #dcfce7;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
    }

    .tjsl-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .tjsl-table {
        width: 100%;
        min-width: 1180px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .tjsl-table thead th {
        background: #f8fafc;
        padding: 14px 16px;
        text-align: left;
        font-size: 11.5px;
        font-weight: 900;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .tjsl-table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
    }

    .tjsl-table tbody tr {
        transition: background .18s ease;
    }

    .tjsl-table tbody tr:hover {
        background: #f8fcf8;
    }

    .tjsl-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .tjsl-no {
        font-weight: 800;
        color: #64748b;
    }

    .tjsl-image {
        width: 74px;
        height: 54px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 16px rgba(15, 23, 42, .08);
    }

    .tjsl-no-image {
        width: 74px;
        height: 54px;
        border-radius: 14px;
        background: #f1f5f9;
        border: 1px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10.5px;
        font-weight: 800;
        color: #94a3b8;
    }

    .tjsl-year {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 62px;
        padding: 8px 10px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 900;
        color: #334155;
    }

    .tjsl-title {
        max-width: 330px;
        font-size: 14.5px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.55;
    }

    .tjsl-lang {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .tjsl-lang--ok {
        color: #2563eb;
    }

    .tjsl-lang--warn {
        color: #b45309;
    }

    .tjsl-user-name {
        font-size: 13.5px;
        font-weight: 900;
        color: #0f172a;
    }

    .tjsl-user-email {
        margin-top: 4px;
        font-size: 12px;
        color: #64748b;
        word-break: break-word;
    }

    .tjsl-activity {
        font-size: 12.3px;
        color: #64748b;
        line-height: 1.65;
    }

    .tjsl-activity strong {
        color: #334155;
        font-weight: 850;
    }

    .tjsl-action {
        text-align: center;
    }

    .tjsl-action .a-btn {
        border-radius: 14px;
        font-weight: 900;
        box-shadow: 0 8px 18px rgba(15, 63, 8, .14);
    }

    .tjsl-pagination {
        margin-top: 16px;
    }

    .tjsl-empty-wrap {
        padding: 44px 20px;
    }

    @media (max-width: 1200px) {
        .tjsl-summary-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .tjsl-filter-form {
            grid-template-columns: 1fr 240px auto auto;
        }
    }

    @media (max-width: 900px) {
        .tjsl-hero-inner {
            align-items: flex-start;
            flex-direction: column;
        }

        .tjsl-hero-info {
            text-align: left;
        }

        .tjsl-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .tjsl-filter-form {
            grid-template-columns: 1fr;
        }

        .tjsl-filter-card .a-btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 560px) {
        .tjsl-summary-grid {
            grid-template-columns: 1fr;
        }

        .tjsl-table-head {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>


<div class="tjsl-filter-card">
    <div class="tjsl-filter-head">
        <div>
            <div class="tjsl-filter-title">Filter Data TJSL</div>
            <div class="tjsl-filter-desc">Gunakan pencarian untuk menemukan program berdasarkan judul, tahun, writer, atau reviewer.</div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.tjsl.index') }}" class="tjsl-filter-form">
        <div>
            <label class="a-label">Search</label>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   class="a-input"
                   placeholder="Cari judul / tahun / writer / reviewer">
        </div>

        <div>
            <label class="a-label">Status</label>
            <select name="status" class="a-input">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="a-btn a-btn--secondary">
            Filter
        </button>

        <a href="{{ route('admin.tjsl.index') }}" class="a-btn a-btn--light">
            Reset
        </a>
    </form>
</div>

<div class="tjsl-table-card">
    <div class="tjsl-table-head">
        <div>
            <div class="tjsl-table-title">Daftar Aktivitas TJSL</div>
            <div class="tjsl-table-desc">Total {{ $programs->total() }} program yang tercatat pada sistem.</div>
        </div>
    </div>

    <div class="tjsl-table-wrap">
        <table class="tjsl-table">
            <thead>
                <tr>
                    <th width="56">#</th>
                    <th width="110">Gambar</th>
                    <th width="100">Tahun</th>
                    <th>Judul</th>
                    <th width="180">Writer</th>
                    <th width="180">Reviewer</th>
                    <th width="140">Status</th>
                    <th width="180">Aktivitas</th>
                    <th width="120" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                    @php
                        $trId = $program->translations->firstWhere('locale', 'id');
                        $trEn = $program->translations->firstWhere('locale', 'en');

                        $badgeClass = match($program->status) {
                            'draft' => 'a-badge--gray',
                            'submitted' => 'a-badge--blue',
                            'revision' => 'a-badge--orange',
                            'approved' => 'a-badge--green',
                            'rejected' => 'a-badge--red',
                            'published' => 'a-badge--green',
                            default => 'a-badge--gray',
                        };
                    @endphp

                    <tr>
                        <td>
                            <span class="tjsl-no">{{ $programs->firstItem() + $loop->index }}</span>
                        </td>

                        <td>
                            @if($program->featured_image)
                                <img src="{{ asset($program->featured_image) }}" alt="TJSL" class="tjsl-image">
                            @else
                                <div class="tjsl-no-image">
                                    No Image
                                </div>
                            @endif
                        </td>

                        <td>
                            <span class="tjsl-year">{{ $program->year }}</span>
                        </td>

                        <td>
                            <div class="tjsl-title">{{ $trId->title ?? '-' }}</div>

                            @if($trEn?->title)
                                <div class="tjsl-lang tjsl-lang--ok">
                                    <span style="width:7px;height:7px;border-radius:50%;background:#2563eb;display:inline-block;"></span>
                                    EN otomatis tersedia
                                </div>
                            @else
                                <div class="tjsl-lang tjsl-lang--warn">
                                    <span style="width:7px;height:7px;border-radius:50%;background:#b45309;display:inline-block;"></span>
                                    EN belum tersedia
                                </div>
                            @endif
                        </td>

                        <td>
                            <div class="tjsl-user-name">{{ $program->author?->name ?? '-' }}</div>
                            <div class="tjsl-user-email">{{ $program->author?->email ?? '-' }}</div>
                        </td>

                        <td>
                            <div class="tjsl-user-name">{{ $program->reviewer?->name ?? '-' }}</div>
                            <div class="tjsl-user-email">{{ $program->reviewer?->email ?? '-' }}</div>
                        </td>

                        <td>
                            <span class="a-badge {{ $badgeClass }}">{{ $program->status_label }}</span>
                        </td>

                        <td>
                            <div class="tjsl-activity">
                                Submit:
                                <strong>{{ $program->submitted_at?->format('d M Y H:i') ?? '-' }}</strong><br>
                                Review:
                                <strong>{{ $program->reviewed_at?->format('d M Y H:i') ?? '-' }}</strong><br>
                                Publish:
                                <strong>{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</strong>
                            </div>
                        </td>

                        <td class="tjsl-action">
                            <a href="{{ route('admin.tjsl.show', $program) }}" class="a-btn a-btn--primary a-btn--sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="tjsl-empty-wrap">
                                <div class="a-empty">
                                    <div class="a-empty-title">Belum ada aktivitas TJSL</div>
                                    <div class="a-empty-desc">Aktivitas writer dan reviewer akan tampil di sini.</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="tjsl-pagination">
    {{ $programs->links('vendor.pagination.admin') }}
</div>

@endsection