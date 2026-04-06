@extends('layouts.admin')

@section('content')
<div class="news-form-page">
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h1 style="margin:0; font-size:24px; font-weight:800; color:#111827;">Tambah Berita</h1>
            <div style="margin-top:6px; font-size:14px; color:#6b7280;">
                Editor cukup isi Bahasa Indonesia. Sistem akan membuat versi English otomatis.
            </div>
        </div>

        <a href="{{ route('admin.news.index') }}" style="display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 16px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700;">
            Kembali ke List
        </a>
    </div>

    @if($errors->any())
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; font-size:14px; font-weight:600; background:#fff1f2; color:#b42318; border:1px solid #fecdd3;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.news._form', ['submitLabel' => 'Simpan Berita'])
    </form>
</div>
@endsection