@extends('layouts.writer')

@section('title', 'Detail TJSL')

@section('content')

@php
    $trId = $program->translations->firstWhere('locale', 'id');
    $trEn = $program->translations->firstWhere('locale', 'en');

    $badgeClass = match($program->status) {
        'draft' => 'a-badge--gray',
        'published' => 'a-badge--green',
        default => 'a-badge--gray',
    };
@endphp

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Writer</span>
            <span class="a-breadcrumb-sep">›</span>
            <a href="{{ route('writer.tjsl.index') }}">TJSL</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Detail</span>
        </div>
        <h1 class="a-page-title">Detail Program TJSL</h1>
        <p class="a-page-desc">Preview, kirim link ke reviewer via WhatsApp, dan publish dilakukan oleh writer.</p>
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('writer.tjsl.index') }}" class="a-btn a-btn--secondary">Kembali</a>
        <a href="{{ route('writer.tjsl.preview', $program) }}" class="a-btn a-btn--light" target="_blank">Preview</a>
        <a href="{{ route('writer.tjsl.send-preview-whatsapp', $program) }}" class="a-btn a-btn--secondary" target="_blank">Kirim WA Reviewer</a>
        <a href="{{ route('writer.tjsl.edit', $program) }}" class="a-btn a-btn--primary">Edit</a>
    </div>
</div>

@if(session('success'))
    <div class="a-alert a-alert--success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="a-alert a-alert--danger">{{ session('error') }}</div>
@endif

<div class="a-card" style="margin-bottom:16px">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Status Publikasi</div>
            <div class="a-card-desc">Data draft tidak tampil di publik. Data published tampil di halaman publik TJSL.</div>
        </div>

        <span class="a-badge {{ $badgeClass }}">{{ $program->status_label }}</span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px">
        <div>
            <div style="font-size:12px;color:#6b7280">Dibuat Oleh</div>
            <div style="font-weight:700">{{ $program->author?->name ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:12px;color:#6b7280">Dibuat</div>
            <div style="font-weight:700">{{ $program->created_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:12px;color:#6b7280">Diupdate</div>
            <div style="font-weight:700">{{ $program->updated_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:12px;color:#6b7280">Dipublish</div>
            <div style="font-weight:700">{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>
    </div>

    <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap">
        @if($program->status === 'draft')
            <form method="POST" action="{{ route('writer.tjsl.publish', $program) }}" onsubmit="return confirm('Publish TJSL ini ke website publik?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="a-btn a-btn--primary">Publish Sekarang</button>
            </form>
        @endif

        @if($program->status === 'published')
            <form method="POST" action="{{ route('writer.tjsl.unpublish', $program) }}" onsubmit="return confirm('Tarik TJSL ini dari website publik?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="a-btn a-btn--secondary">Unpublish</button>
            </form>
        @endif

        <form method="POST" action="{{ route('writer.tjsl.destroy', $program) }}" onsubmit="return confirm('Hapus program TJSL ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="a-btn a-btn--danger">Hapus</button>
        </form>
    </div>
</div>

<div class="a-card" style="margin-bottom:16px">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Konten Bahasa Indonesia</div>
            <div class="a-card-desc">Konten utama yang diinput oleh writer.</div>
        </div>

        <span class="a-badge a-badge--green">Source</span>
    </div>

    <h2 style="font-size:22px;font-weight:800;margin-bottom:8px">{{ $trId->title ?? '-' }}</h2>
    <p style="color:#6b7280;margin-bottom:16px">{{ $trId->summary ?? '-' }}</p>
    <div style="line-height:1.8;color:#374151">
        {!! nl2br(e($trId->content ?? '-')) !!}
    </div>
</div>

<div class="a-card" style="margin-bottom:16px">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Preview English Otomatis</div>
            <div class="a-card-desc">Hasil terjemahan otomatis dari DeepL.</div>
        </div>

        <span class="a-badge a-badge--blue">Auto Translate</span>
    </div>

    <h2 style="font-size:22px;font-weight:800;margin-bottom:8px">{{ $trEn->title ?? '-' }}</h2>
    <p style="color:#6b7280;margin-bottom:16px">{{ $trEn->summary ?? '-' }}</p>
    <div style="line-height:1.8;color:#374151">
        {!! nl2br(e($trEn->content ?? '-')) !!}
    </div>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Galeri</div>
            <div class="a-card-desc">{{ $program->images->count() }} dokumentasi</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px">
        @forelse($program->images as $image)
            <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;background:#fff">
                <img src="{{ asset($image->image_path) }}" alt="Galeri TJSL" style="width:100%;height:130px;object-fit:cover">
                <div style="padding:10px;font-size:12px;color:#6b7280">
                    {{ $image->caption ?: 'Tanpa caption' }}
                </div>
            </div>
        @empty
            <div style="color:#6b7280">Belum ada gambar galeri.</div>
        @endforelse
    </div>
</div>

@endsection