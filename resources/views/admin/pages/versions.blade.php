@extends('layouts.admin')

@section('content')
<div class="container" style="max-width:1100px;">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <h1 style="margin:0;">Page Bundles: #{{ $page->id }}</h1>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('admin.pages.edit', $page) }}">← Kembali ke Edit</a>
            <a href="{{ route('admin.pages.index') }}">List Pages</a>
        </div>
    </div>

    @if(session('success'))
        <div style="margin:12px 0; color:green;">{{ session('success') }}</div>
    @endif

    <p style="color:#666; margin-top:10px;">
        Klik <b>Restore Bundle</b> untuk mengembalikan <b>GLOBAL + ID + EN</b> sekaligus.
    </p>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:10px;">
        <thead>
            <tr>
                <th style="width:240px;">Bundle</th>
                <th>Preview (ID/EN)</th>
                <th style="width:180px;">Created</th>
                <th style="width:120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bundles as $bundleRow)
                @php
                    $bundleId = $bundleRow->bundle_id;
                    $rows = $versions->get($bundleId, collect());

                    $global = $rows->firstWhere('locale', null);
                    $idRow = $rows->firstWhere('locale', 'id');
                    $enRow = $rows->firstWhere('locale', 'en');

                    $idPayload = $idRow?->payload ?? [];
                    $enPayload = $enRow?->payload ?? [];

                    $createdAt = optional($rows->sortByDesc('created_at')->first())->created_at;
                @endphp

                <tr>
                    <td style="font-family:monospace; font-size:12px;">
                        {{ $bundleId }}
                    </td>

                    <td>
                        <div style="font-size:13px;">
                            <div>
                                <b>ID:</b> {{ $idPayload['title'] ?? '-' }}
                                <span style="color:#666;">/ {{ $idPayload['slug'] ?? '-' }}</span>
                            </div>

                            <div>
                                <b>EN:</b> {{ $enPayload['title'] ?? '-' }}
                                <span style="color:#666;">/ {{ $enPayload['slug'] ?? '-' }}</span>
                            </div>

                            <div style="margin-top:6px; font-size:12px;">
                                @if(!empty($idPayload['slug']))
                                    <a target="_blank" href="{{ route('page.show', ['locale' => 'id', 'slug' => $idPayload['slug']]) }}">
                                        Preview ID
                                    </a>
                                @endif

                                @if(!empty($enPayload['slug']))
                                    <span style="margin:0 8px;">|</span>
                                    <a target="_blank" href="{{ route('page.show', ['locale' => 'en', 'slug' => $enPayload['slug']]) }}">
                                        Preview EN
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td>{{ $createdAt }}</td>

                    <td>
                        <form method="POST" action="{{ route('admin.pages.bundles.restore', ['page' => $page->id, 'bundle' => $bundleId]) }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Restore bundle ini? (GLOBAL+ID+EN)')">
                                Restore Bundle
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada versi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;">
        {{ $bundles->links() }}
    </div>
</div>
@endsection