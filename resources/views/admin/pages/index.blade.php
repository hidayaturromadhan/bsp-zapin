@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Halaman</h1>

    @if(session('success'))
        <div style="margin:10px 0; color:green;">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th>Judul (ID)</th>
                <th>Judul (EN)</th>
                <th>Slug (ID)</th>
                <th>Slug (EN)</th>
                <th>Active</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pages as $p)
                @php
                    $tId = $p->translations->firstWhere('locale', 'id');
                    $tEn = $p->translations->firstWhere('locale', 'en');
                @endphp
                <tr>
                    <td>{{ $tId?->title ?? '-' }}</td>
                    <td>{{ $tEn?->title ?? '-' }}</td>
                    <td>{{ $tId?->slug ?? '-' }}</td>
                    <td>{{ $tEn?->slug ?? '-' }}</td>
                    <td>{{ $p->is_active ? 'Ya' : 'Tidak' }}</td>
                    <td>
                        <a href="{{ route('admin.pages.edit', $p) }}">Edit</a>
                        <a href="{{ route('admin.pages.versions', $p) }}" style="margin-left:8px;">Versions</a>

                        @if($tId?->slug)
                            <a href="{{ route('page.show', ['locale' => 'id', 'slug' => $tId->slug]) }}"
                               target="_blank" style="margin-left:8px;">Lihat ID</a>
                        @endif

                        @if($tEn?->slug)
                            <a href="{{ route('page.show', ['locale' => 'en', 'slug' => $tEn->slug]) }}"
                               target="_blank" style="margin-left:8px;">Lihat EN</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:12px;">
        {{ $pages->links() }}
    </div>
</div>
@endsection