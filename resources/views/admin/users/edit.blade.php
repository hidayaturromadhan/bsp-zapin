@extends('layouts.admin')

@section('title', 'Edit Akun')

@section('content')

{{-- Page Header --}}
<div class="a-page-head">
    <div>
        <div class="a-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="a-breadcrumb-sep">›</span>
            <a href="{{ route('admin.users.index') }}">Manajemen User</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Edit Akun</span>
        </div>
        <h1 class="a-page-title">Edit Akun</h1>
        <p class="a-page-desc">Perbarui data akun, role, status akses, dan reset password user.</p>
    </div>

    <a href="{{ route('admin.users.index') }}" class="a-btn a-btn--secondary">
        ← Kembali
    </a>
</div>

{{-- User Summary Card --}}
<div class="a-card">
    <div class="a-card-body">
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <div style="
                width:52px;height:52px;border-radius:14px;flex-shrink:0;
                background:var(--g800);color:#fff;
                display:flex;align-items:center;justify-content:center;
                font-size:20px;font-weight:800;overflow:hidden;">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>

            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span style="font-size:17px;font-weight:800;color:var(--text);">{{ $user->name }}</span>
                    @if(auth()->id() === $user->id)
                        <span class="a-badge a-badge--blue">Akun Anda</span>
                    @endif
                    @if($user->is_active)
                        <span class="a-badge a-badge--green">● Aktif</span>
                    @else
                        <span class="a-badge a-badge--red">● Nonaktif</span>
                    @endif
                </div>
                <div style="font-size:13px;color:var(--text3);margin-top:3px;">{{ $user->email }}</div>
                <div style="font-size:13px;color:var(--text2);margin-top:4px;">
                    Role saat ini:
                    <strong style="color:var(--text);">{{ $user->roleLabel() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Form --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Data Akun</div>
            <div class="a-card-desc">Perubahan role dan status akan memengaruhi akses user ke sistem.</div>
        </div>
    </div>

    <div class="a-card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">

                {{-- Name --}}
                <div class="a-form-group">
                    <label class="a-label" for="name">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $user->name) }}"
                           class="a-input" required>
                    @error('name')
                        <p style="margin-top:5px;font-size:12.5px;color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="a-form-group">
                    <label class="a-label" for="email">Email <span style="color:#dc2626;">*</span></label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $user->email) }}"
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
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if(auth()->id() === $user->id)
                        <p style="margin-top:5px;font-size:12px;color:#b45309;">
                            Anda tidak bisa mengubah role akun yang sedang digunakan.
                        </p>
                    @endif
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
                                   @checked(old('is_active', $user->is_active ? '1' : '0') == '1')
                                   @disabled(auth()->id() === $user->id)>
                            <span style="font-size:13.5px;font-weight:600;color:var(--text2);">
                                Akun aktif dan dapat login
                            </span>
                        </label>
                    </div>
                    @if(auth()->id() === $user->id)
                        <input type="hidden" name="is_active" value="1">
                        <p style="margin-top:5px;font-size:12px;color:#b45309;">
                            Anda tidak bisa menonaktifkan akun yang sedang digunakan.
                        </p>
                    @endif
                </div>
            </div>

            <div class="a-alert a-alert--warn" style="margin-top:4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                    <path d="M12 3 2 20h20L12 3z"/><path d="M12 9v4"/><path d="M12 17h.01"/>
                </svg>
                <span>Jika akun dinonaktifkan, session aktif user akan diputus dan user tidak dapat login hingga akun diaktifkan kembali.</span>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                <a href="{{ route('admin.users.index') }}" class="a-btn a-btn--secondary">Batal</a>
                <button type="submit" class="a-btn a-btn--primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- Reset Password --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Reset Password</div>
            <div class="a-card-desc">Gunakan jika user lupa password atau perlu diganti oleh admin. Session aktif akan diputus.</div>
        </div>
    </div>

    <div class="a-card-body">
        <form method="POST"
              action="{{ route('admin.users.reset-password', $user) }}"
              onsubmit="return confirm('Reset password akun ini? User akan diminta login ulang.')">
            @csrf
            @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="a-form-group" style="margin-bottom:0;">
                    <label class="a-label" for="password">
                        Password Baru <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="password" name="password" id="password"
                           placeholder="Minimal 8 karakter"
                           class="a-input">
                    @error('password')
                        <p style="margin-top:5px;font-size:12.5px;color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="a-form-group" style="margin-bottom:0;">
                    <label class="a-label" for="password_confirmation">
                        Konfirmasi Password <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Ulangi password baru"
                           class="a-input">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-top:20px;">
                <button type="submit" class="a-btn a-btn--danger"
                        style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 2H3v16h5v4l4-4h5l4-4V2zM11 11V7m0 8h.01"/>
                    </svg>
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection