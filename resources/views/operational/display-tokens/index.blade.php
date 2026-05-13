@extends('layouts.operational')

@section('title', 'Display Token')

@section('content')
<div class="op-page-head">
    <div>
        <div class="op-breadcrumb">
            <span>Operational</span>
            <span>›</span>
            <span>Display Token</span>
        </div>

        <h1 class="op-page-title">Token Public Display TV</h1>
        <p class="op-page-desc">
            Kelola akses public display TV tanpa login menggunakan token aman.
        </p>
    </div>

    <a href="{{ route('operational.tv') }}" target="_blank" class="op-btn op-btn--soft">
        Buka TV Login
    </a>
</div>

@if(session('success'))
    <div class="op-alert op-alert--success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="op-alert op-alert--danger">
        <div>
            <div style="font-weight:800;margin-bottom:4px;">Terjadi kesalahan validasi.</div>
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="op-card" style="margin-bottom:20px;">
    <div class="op-card-head">
        <div>
            <h2 class="op-card-title">Buat Token Baru</h2>
            <div class="op-card-desc">
                Token ini akan digunakan untuk membuka display TV secara publik namun tetap aman.
            </div>
        </div>
    </div>

    <div class="op-card-body">
        <form method="POST" action="{{ route('operational.display-tokens.store') }}" class="op-form-grid">
            @csrf

            <div class="op-field">
                <label class="op-label">Nama Token</label>
                <input
                    type="text"
                    name="name"
                    class="op-input"
                    value="{{ old('name', 'Display TV Operational') }}"
                    placeholder="Contoh: Display TV Lobby"
                    required
                >
            </div>

            <div class="op-field">
                <label class="op-label">Tanggal Kedaluwarsa</label>
                <input
                    type="datetime-local"
                    name="expired_at"
                    class="op-input"
                    value="{{ old('expired_at') }}"
                >
                <div class="op-help">
                    Kosongkan jika token tidak memiliki masa kedaluwarsa.
                </div>
            </div>

            <div class="op-field full" style="display:flex;justify-content:flex-end;">
                <button type="submit" class="op-btn op-btn--primary">
                    Buat Token
                </button>
            </div>
        </form>
    </div>
</div>

<div class="op-card">
    <div class="op-card-head">
        <div>
            <h2 class="op-card-title">Daftar Token Saat Ini</h2>
            <div class="op-card-desc">
                Reset token akan membuat link lama tidak bisa digunakan lagi.
            </div>
        </div>
    </div>

    <div class="op-card-body">
        @if($tokens->count())
            <div class="op-table-wrap">
                <table class="op-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Public URL</th>
                            <th>Kedaluwarsa</th>
                            <th>Terakhir Diakses</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tokens as $token)
                            <tr>
                                <td>
                                    <form method="POST" action="{{ route('operational.display-tokens.update', $token->id) }}" id="update-token-{{ $token->id }}">
                                        @csrf
                                        @method('PUT')

                                        <input
                                            type="text"
                                            name="name"
                                            class="op-input"
                                            value="{{ old('name', $token->name) }}"
                                            required
                                            style="min-width:180px;"
                                        >

                                        <input
                                            type="hidden"
                                            name="is_active"
                                            value="{{ $token->is_active ? 1 : 0 }}"
                                        >
                                    </form>
                                </td>

                                <td>
                                    @if($token->status_label === 'Aktif')
                                        <span class="op-badge" style="background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;">
                                            Aktif
                                        </span>
                                    @elseif($token->status_label === 'Kedaluwarsa')
                                        <span class="op-badge" style="background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;">
                                            Kedaluwarsa
                                        </span>
                                    @else
                                        <span class="op-badge" style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div style="display:grid;gap:8px;min-width:280px;">
                                        <input
                                            type="text"
                                            class="op-input js-token-url"
                                            value="{{ $token->public_url }}"
                                            readonly
                                        >

                                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                            <button type="button" class="op-btn op-btn-xs op-btn--soft js-copy-token">
                                                Copy Link
                                            </button>

                                            <a href="{{ $token->public_url }}" target="_blank" class="op-btn op-btn-xs op-btn--primary">
                                                Buka
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <input
                                        type="datetime-local"
                                        name="expired_at"
                                        form="update-token-{{ $token->id }}"
                                        class="op-input"
                                        value="{{ $token->expired_at ? $token->expired_at->format('Y-m-d\TH:i') : '' }}"
                                        style="min-width:190px;"
                                    >
                                </td>

                                <td>
                                    {{ $token->last_accessed_at ? $token->last_accessed_at->format('d-m-Y H:i') : '-' }}
                                </td>

                                <td>
                                    <div class="op-actions" style="align-items:flex-start;">
                                        <button
                                            type="submit"
                                            form="update-token-{{ $token->id }}"
                                            class="op-btn op-btn-xs op-btn-edit"
                                        >
                                            Simpan
                                        </button>

                                        @if($token->is_active)
                                            <form method="POST" action="{{ route('operational.display-tokens.deactivate', $token->id) }}">
                                                @csrf
                                                <button type="submit" class="op-btn op-btn-xs op-btn--soft">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('operational.display-tokens.activate', $token->id) }}">
                                                @csrf
                                                <button type="submit" class="op-btn op-btn-xs op-btn--primary">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif

                                        <form
                                            method="POST"
                                            action="{{ route('operational.display-tokens.reset', $token->id) }}"
                                            onsubmit="return confirm('Yakin ingin reset token? Link lama tidak akan bisa digunakan lagi.')"
                                        >
                                            @csrf
                                            <button type="submit" class="op-btn op-btn-xs op-btn-edit">
                                                Reset Token
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('operational.display-tokens.destroy', $token->id) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus token ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="op-btn op-btn-xs op-btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="op-empty">
                <div class="op-empty-title">Belum ada token display.</div>
                <div>Silakan buat token baru untuk akses public display TV.</div>
            </div>
        @endif
    </div>
</div>

<script>
document.querySelectorAll('.js-copy-token').forEach(function (button) {
    button.addEventListener('click', function () {
        const wrapper = button.closest('td');
        const input = wrapper ? wrapper.querySelector('.js-token-url') : null;

        if (!input) {
            return;
        }

        input.select();
        input.setSelectionRange(0, 99999);

        navigator.clipboard.writeText(input.value).then(function () {
            const oldText = button.textContent;
            button.textContent = 'Tersalin';

            setTimeout(function () {
                button.textContent = oldText;
            }, 1300);
        }).catch(function () {
            document.execCommand('copy');
            const oldText = button.textContent;
            button.textContent = 'Tersalin';

            setTimeout(function () {
                button.textContent = oldText;
            }, 1300);
        });
    });
});
</script>
@endsection