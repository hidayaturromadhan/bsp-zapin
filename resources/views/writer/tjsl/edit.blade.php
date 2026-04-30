@extends('layouts.writer')

@section('title', 'Edit TJSL')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Writer</span>
            <span class="a-breadcrumb-sep">›</span>
            <a href="{{ route('writer.tjsl.index') }}">TJSL</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Edit</span>
        </div>
        <h1 class="a-page-title">Edit Program TJSL</h1>
        <p class="a-page-desc">Perubahan Bahasa Indonesia akan otomatis memperbarui versi English menggunakan DeepL.</p>
    </div>

    <a href="{{ route('writer.tjsl.show', $program) }}" class="a-btn a-btn--secondary">Detail</a>
</div>

@include('writer.tjsl.partials.form', [
    'program' => $program,
    'action' => route('writer.tjsl.update', $program),
    'method' => 'PUT',
])

@endsection