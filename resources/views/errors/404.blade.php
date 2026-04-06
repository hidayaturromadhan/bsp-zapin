@extends('layouts.app')

@section('content')
<div style="text-align:center; padding:80px 20px;">
    <h1 style="font-size:64px; margin:0;">404</h1>
    <h2 style="margin:10px 0;">
        {{ request()->segment(1) === 'en'
            ? 'Page not found'
            : 'Halaman tidak ditemukan' }}
    </h2>

    <p style="color:#6b7280;">
        {{ request()->segment(1) === 'en'
            ? 'The page you are looking for does not exist.'
            : 'Halaman yang Anda cari tidak tersedia.' }}
    </p>

    <a href="{{ route('web.home', ['locale' => request()->segment(1) ?? 'id']) }}"
       style="display:inline-block; margin-top:20px; padding:10px 20px; background:#173f08; color:#fff; border-radius:8px;">
        {{ request()->segment(1) === 'en' ? 'Back to Home' : 'Kembali ke Beranda' }}
    </a>
</div>
@endsection