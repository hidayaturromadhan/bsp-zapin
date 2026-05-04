@extends('layouts.reviewer')

@section('title', 'Preview Berita')

@section('content')

@include('shared.news.preview-content', [
    'newsItem' => $newsItem,
    'translation' => $translation,
    'locale' => $locale,
    'backUrl' => route('reviewer.news.show', $newsItem),
    'backLabel' => 'Kembali ke Detail Reviewer',
    'panelLabel' => 'Reviewer Preview',
])

@endsection