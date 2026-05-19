@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')

{{-- Page Header --}}
<div class="a-page-head">
    <div>
        <div class="a-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Manajemen User</span>
        </div>
        <h1 class="a-page-title">Manajemen User</h1>
        <p class="a-page-desc">Kelola akun pengguna, role akses, status aktif, dan reset password.</p>
    </div>

    <a href="{{ route('admin.users.create') }}" class="a-btn a-btn--primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Akun
    </a>
</div>

{{-- Summary Stats --}}
<div class="a-stats-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Total User</span>
            <div class="a-stat-icon" style="background:#e8f5e9; color:var(--g700);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value">{{ $users->total() }}</div>
        <div class="a-stat-sub">dari semua halaman</div>
    </div>

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Halaman Ini</span>
            <div class="a-stat-icon" style="background:#eff6ff; color:#1d4ed8;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value">{{ $users->count() }}</div>
        <div class="a-stat-sub">ditampilkan sekarang</div>
    </div>

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Filter Aktif</span>
            <div class="a-stat-icon" style="background:#fffbeb; color:#b45309;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value">{{ ($search ? 1 : 0) + ($selectedRole ? 1 : 0) + ($selectedStatus ? 1 : 0) }}</div>
        <div class="a-stat-sub">kriteria pencarian</div>
    </div>
</div>

{{-- Filter Card --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Filter User</div>
            <div class="a-card-desc">Cari berdasarkan nama, email, role, atau status akun.</div>
        </div>
        @if($search || $selectedRole || $selectedStatus)
            <a href="{{ route('admin.users.index') }}" class="a-btn a-btn--secondary a-btn--sm">
                Reset Filter
            </a>
        @endif
    </div>

    <div class="a-card-body">
        <form method="GET" action="{{ route('admin.users.index') }}"
              style="display:grid; grid-template-columns: 1fr 180px 160px auto; gap:12px; align-items:flex-end;">

            <div class="a-form-group" style="margin:0;">
                <label class="a-label" for="search">Cari User</label>
                <div style="position:relative;">
                    <input type="text" name="search" id="search"
                           value="{{ $search }}"
                           placeholder="Nama atau email..."
                           class="a-input"
                           style="padding-left:36px;">
                    <svg style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text3);pointer-events:none;"
                         width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
            </div>

            <div class="a-form-group" style="margin:0;">
                <label class="a-label" for="role">Role</label>
                <select name="role" id="role" class="a-select">
                    <option value="">Semua Role</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" @selected($selectedRole === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="a-form-group" style="margin:0;">
                <label class="a-label" for="status">Status</label>
                <select name="status" id="status" class="a-select">
                    <option value="">Semua Status</option>
                    <option value="active"   @selected($selectedStatus === 'active')>Aktif</option>
                    <option value="inactive" @selected($selectedStatus === 'inactive')>Nonaktif</option>
                </select>
            </div>

            <div style="margin-bottom:0;">
                <button type="submit" class="a-btn a-btn--primary" style="width:100%;">Terapkan</button>
            </div>
        </form>

        @if($search || $selectedRole || $selectedStatus)
            <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;margin-top:14px;padding-top:14px;border-top:1px solid var(--line2);">
                <span style="font-size:11.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text3);">
                    Aktif:
                </span>
                @if($search)
                    <span class="a-badge a-badge--blue">Cari: {{ $search }}</span>
                @endif
                @if($selectedRole)
                    <span class="a-badge" style="background:#f3e8ff;color:#6b21a8;">Role: {{ $roles[$selectedRole] ?? $selectedRole }}</span>
                @endif
                @if($selectedStatus)
                    <span class="a-badge a-badge--yellow">Status: {{ $selectedStatus === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- Users Table --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Akun</div>
            <div class="a-card-desc">Menampilkan {{ $users->count() }} dari {{ $users->total() }} user.</div>
        </div>
        <a href="{{ route('admin.users.create') }}" class="a-btn a-btn--primary a-btn--sm">
            + Tambah Akun
        </a>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Login Terakhir</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        {{-- User info --}}
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;min-width:240px;">
                                <div style="
                                    width:38px;height:38px;border-radius:10px;flex-shrink:0;
                                    background:var(--g800);color:#fff;
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:14px;font-weight:800;overflow:hidden;">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div style="min-width:0;">
                                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                        <span style="font-weight:700;color:var(--text);font-size:13.5px;">{{ $user->name }}</span>
                                        @if(auth()->id() === $user->id)
                                            <span class="a-badge a-badge--blue" style="padding:1px 7px;font-size:10.5px;">Anda</span>
                                        @endif
                                    </div>
                                    <div style="font-size:12.5px;color:var(--text3);margin-top:2px;">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Role --}}
                        <td>
                            <span class="a-badge a-badge--gray">{{ $user->roleLabel() }}</span>
                        </td>

                        {{-- Status --}}
                        <td>
                            @if($user->is_active)
                                <span class="a-badge a-badge--green">● Aktif</span>
                            @else
                                <span class="a-badge a-badge--red">● Nonaktif</span>
                            @endif
                        </td>

                        {{-- Last login --}}
                        <td style="font-size:13px;color:var(--text2);white-space:nowrap;">
                            @if($user->active_login_at)
                                {{ $user->active_login_at->format('d M Y H:i') }}
                            @else
                                <span style="color:var(--text3);">Belum ada</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:nowrap;">
                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="a-btn a-btn--secondary a-btn--sm">Edit</a>

                                @if(auth()->id() !== $user->id)
                                    {{-- Toggle status --}}
                                    <form method="POST"
                                          action="{{ route('admin.users.toggle-status', $user) }}"
                                          onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan akun ini?' : 'Aktifkan kembali akun ini?' }}')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="a-btn a-btn--sm {{ $user->is_active ? 'a-btn--danger' : '' }}"
                                                style="{{ !$user->is_active ? 'background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;' : '' }}">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    {{-- Hapus --}}
                                    <form method="POST"
                                          action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Hapus akun ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="a-btn a-btn--secondary a-btn--sm"
                                                style="color:#991b1b;">Hapus</button>
                                    </form>
                                @else
                                    <span class="a-badge a-badge--gray" style="padding:6px 10px;">Akun aktif</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="a-empty">
                                <div class="a-empty-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                </div>
                                <div class="a-empty-title">Belum ada user ditemukan</div>
                                <p class="a-empty-desc">
                                    Tidak ada user yang sesuai filter. Coba ubah kriteria pencarian atau tambah akun baru.
                                </p>
                                <a href="{{ route('admin.users.create') }}" class="a-btn a-btn--primary">
                                    + Tambah Akun
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div style="padding:14px 20px;border-top:1px solid var(--line2);background:var(--line2);">
            {{ $users->links('vendor.pagination.admin') }}
        </div>
    @endif
</div>

@endsection