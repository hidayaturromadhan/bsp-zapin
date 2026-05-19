@extends('layouts.admin')

@section('title', 'Tambah Akun')

@section('content')

{{-- Page Header --}}
<div class="a-page-head">
    <div>
        <div class="a-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="a-breadcrumb-sep">›</span>
            <a href="{{ route('admin.users.index') }}">Manajemen User</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Tambah Akun</span>
        </div>
        <h1 class="a-page-title">Tambah Akun</h1>
        <p class="a-page-desc">Buat akun baru untuk admin, operasional, writer, reviewer, WBS, atau pelapor.</p>
    </div>

    <a href="{{ route('admin.users.index') }}" class="a-btn a-btn--secondary">
        ← Kembali
    </a>
</div>

{{-- Form Card --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Data Akun Baru</div>
            <div class="a-card-desc">Semua field wajib diisi. Akun otomatis dianggap terverifikasi email.</div>
        </div>
    </div>

    <div class="a-card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">

                {{-- Name --}}
                <div class="a-form-group">
                    <label class="a-label" for="name">
                        Nama Lengkap <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}"
                           placeholder="Contoh: Hidayatur Romadhan"
                           class="a-input" required>
                    @error('name')
                        <p style="margin-top:5px;font-size:12.5px;color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="a-form-group">
                    <label class="a-label" for="email">
                        Email <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           class="a-input" required>
                    @error('email')
                        <p style="margin-top:5px;font-size:12.5px;color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="a-form-group">
                    <label class="a-label" for="role">
                        Role <span style="color:#dc2626;">*</span>
                    </label>
                    <select name="role" id="role" class="a-select" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p style="margin-top:5px;font-size:12.5px;color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="a-form-group">
                    <label class="a-label" for="is_active">Status Akun</label>
                    <div style="
                        display:flex;align-items:center;
                        border:1px solid var(--line);border-radius:var(--r);
                        background:var(--line2);padding:10px 14px;min-height:42px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   style="width:16px;height:16px;accent-color:var(--g500);"
                                   @checked(old('is_active', '1') == '1')>
                            <span style="font-size:13.5px;font-weight:600;color:var(--text2);">
                                Aktifkan akun setelah dibuat
                            </span>
                        </label>
                    </div>
                    @error('is_active')
                        <p style="margin-top:5px;font-size:12.5px;color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="a-form-group">
                    <label class="a-label" for="password">
                        Password <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="password" name="password" id="password"
                           placeholder="Minimal 8 karakter"
                           class="a-input" required>
                    @error('password')
                        <p style="margin-top:5px;font-size:12.5px;color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="a-form-group">
                    <label class="a-label" for="password_confirmation">
                        Konfirmasi Password <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Ulangi password"
                           class="a-input" required>
                </div>
            </div>

            <div class="a-alert a-alert--info" style="margin-top:4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                    <circle cx="12" cy="12" r="9"/><path d="M12 10v6"/><path d="M12 7h.01"/>
                </svg>
                <span>Akun yang dibuat melalui halaman ini otomatis dianggap terverifikasi email. Password akan di-hash otomatis oleh Laravel.</span>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                <a href="{{ route('admin.users.index') }}" class="a-btn a-btn--secondary">Batal</a>
                <button type="submit" class="a-btn a-btn--primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

@endsection