@extends('layouts.writer')

@section('title', 'Preview Berita')

@section('content')

@include('shared.news.preview-content', [
    'newsItem' => $newsItem,
    'translation' => $translation,
    'locale' => $locale,
    'backUrl' => route('writer.news.show', $newsItem),
    'backLabel' => 'Kembali ke Detail Writer',
    'panelLabel' => 'Writer Preview',
])

@endsection