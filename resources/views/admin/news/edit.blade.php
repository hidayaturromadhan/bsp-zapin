@extends('layouts.admin')

@section('content')
<div class="news-form-page">
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h1 style="margin:0; font-size:24px; font-weight:800; color:#111827;">Edit Berita</h1>
            <div style="margin-top:6px; font-size:14px; color:#6b7280;">
                Ubah versi Bahasa Indonesia. Sistem akan memperbarui versi English otomatis.
            </div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('admin.news.versions', $news) }}" style="display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700;">
                Versions
            </a>

            @if($tId?->slug)
                <a target="_blank" href="{{ route('news.show', ['locale' => 'id', 'slug' => $tId->slug]) }}" style="display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700;">
                    Preview ID
                </a>
            @endif

            @if($tEn?->slug)
                <a target="_blank" href="{{ route('news.show', ['locale' => 'en', 'slug' => $tEn->slug]) }}" style="display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700;">
                    Preview EN
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; font-size:14px; font-weight:600; background:#eef8ee; color:#17603a; border:1px solid #cfe9d3;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; font-size:14px; font-weight:600; background:#fff1f2; color:#b42318; border:1px solid #fecdd3;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.news._form', ['submitLabel' => 'Update Berita'])
    </form>
</div>
@endsection