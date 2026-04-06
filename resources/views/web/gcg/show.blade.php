@extends('layouts.app')

@section('title', $translation->title)

@section('content')

<style>
.gcg-show { display: flex; flex-direction: column; gap: 20px; }

.gcg-show h1 { font-size: 26px; font-weight: 700; }

.doc-list { display: flex; flex-direction: column; gap: 12px; }

.doc-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border: 1px solid #e5e7eb;
    padding: 14px 18px;
    border-radius: 10px;
}

.doc-title { font-weight: 500; }

.doc-download {
    font-size: 13px;
    padding: 6px 12px;
    background: #2f7d32;
    color: #fff;
    border-radius: 6px;
}
</style>

<div class="gcg-show">

    <h1>{{ $translation->title }}</h1>

    <div class="doc-list">
        @forelse($category->activeDocuments as $doc)
            @php
                $dt = $doc->translations->firstWhere('locale', $locale)
                    ?? $doc->translations->first();
            @endphp

            <div class="doc-item">
                <div class="doc-title">{{ $dt->title }}</div>
                <a href="{{ route('gcg.download', $doc->id) }}" class="doc-download">
                    Download
                </a>
            </div>
        @empty
            <p>Tidak ada dokumen</p>
        @endforelse
    </div>

</div>

@endsection