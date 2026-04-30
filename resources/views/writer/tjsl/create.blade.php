@extends('layouts.writer')

@section('title', 'Tambah TJSL')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Writer</span>
            <span class="a-breadcrumb-sep">›</span>
            <a href="{{ route('writer.tjsl.index') }}">TJSL</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Tambah</span>
        </div>
        <h1 class="a-page-title">Tambah Program TJSL</h1>
        <p class="a-page-desc">Buat draft program TJSL. Input cukup Bahasa Indonesia, English dibuat otomatis oleh DeepL.</p>
    </div>

    <a href="{{ route('writer.tjsl.index') }}" class="a-btn a-btn--secondary">Kembali</a>
</div>

@include('writer.tjsl.partials.form', [
    'program' => $program,
    'action' => route('writer.tjsl.store'),
    'method' => 'POST',
])

@endsection