@extends('layouts.writer')

@section('title', 'Preview TJSL')

@section('content')

@include('shared.tjsl.preview-content', [
    'program' => $program,
    'translation' => $translation,
    'locale' => $locale,
    'backUrl' => route('writer.tjsl.show', $program),
    'backLabel' => 'Kembali ke Detail Writer',
    'panelLabel' => 'Writer Preview',
])

@endsection