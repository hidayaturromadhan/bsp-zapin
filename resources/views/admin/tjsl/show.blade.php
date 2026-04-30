@extends('layouts.admin')

@section('title', 'Detail Monitoring TJSL')

@section('content')

@php
    $trId = $program->translations->firstWhere('locale', 'id');
    $trEn = $program->translations->firstWhere('locale', 'en');

    $badgeClass = match($program->status) {
        'draft' => 'a-badge--gray',
        'submitted' => 'a-badge--blue',
        'revision' => 'a-badge--orange',
        'approved' => 'a-badge--green',
        'rejected' => 'a-badge--red',
        'published' => 'a-badge--green',
        default => 'a-badge--gray',
    };
@endphp

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <a href="{{ route('admin.tjsl.index') }}">TJSL</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Detail</span>
        </div>
        <h1 class="a-page-title">Detail Monitoring TJSL</h1>
        <p class="a-page-desc">Admin hanya melihat aktivitas TJSL. Konten English dibuat otomatis menggunakan DeepL.</p>
    </div>

    <a href="{{ route('admin.tjsl.index') }}" class="a-btn a-btn--secondary">Kembali</a>
</div>

<div class="a-alert a-alert--info">
    Mode monitoring: admin tidak memiliki tombol tambah, edit, hapus, approve, reject, publish, atau unpublish untuk TJSL.
</div>

<div class="a-card" style="margin-bottom:16px">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Status Aktivitas</div>
            <div class="a-card-desc">Informasi workflow TJSL</div>
        </div>

        <span class="a-badge {{ $badgeClass }}">{{ $program->status_label }}</span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px">
        <div>
            <div style="font-size:12px;color:#6b7280">Writer</div>
            <div style="font-weight:700">{{ $program->author?->name ?? '-' }}</div>
            <div style="font-size:12px;color:#6b7280">{{ $program->author?->email ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:12px;color:#6b7280">Submitted At</div>
            <div style="font-weight:700">{{ $program->submitted_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:12px;color:#6b7280">Reviewer</div>
            <div style="font-weight:700">{{ $program->reviewer?->name ?? '-' }}</div>
            <div style="font-size:12px;color:#6b7280">{{ $program->reviewer?->email ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:12px;color:#6b7280">Published At</div>
            <div style="font-weight:700">{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>
    </div>

    @if($program->review_note)
        <div style="margin-top:14px;background:#fffbeb;border:1px solid #fde68a;color:#92400e;padding:12px;border-radius:12px">
            <strong>Catatan Review:</strong><br>
            {{ $program->review_note }}
        </div>
    @endif
</div>

<div class="a-card" style="margin-bottom:16px">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Informasi Program</div>
            <div class="a-card-desc">Data utama program TJSL</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:220px 1fr;gap:16px;align-items:start">
        <div>
            @if($program->featured_image)
                <img src="{{ asset($program->featured_image) }}" alt="Featured TJSL" style="width:100%;height:150px;object-fit:cover;border-radius:14px;border:1px solid #e5e7eb">
            @else
                <div style="height:150px;border-radius:14px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;color:#6b7280">
                    No Image
                </div>
            @endif
        </div>

        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px">
            <div>
                <div style="font-size:12px;color:#6b7280">Tahun</div>
                <div style="font-weight:800;font-size:20px">{{ $program->year }}</div>
            </div>

            <div>
                <div style="font-size:12px;color:#6b7280">Sort Order</div>
                <div style="font-weight:800;font-size:20px">{{ $program->sort_order }}</div>
            </div>

            <div>
                <div style="font-size:12px;color:#6b7280">Is Active</div>
                <div style="font-weight:800">{{ $program->is_active ? 'Ya' : 'Tidak' }}</div>
            </div>

            <div>
                <div style="font-size:12px;color:#6b7280">Galeri</div>
                <div style="font-weight:800">{{ $program->images->count() }} foto</div>
            </div>
        </div>
    </div>
</div>

<div class="a-card" style="margin-bottom:16px">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Konten Bahasa Indonesia</div>
            <div class="a-card-desc">Konten source yang dibuat oleh writer.</div>
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
            <div class="a-card-title">Galeri Dokumentasi</div>
            <div class="a-card-desc">Foto dokumentasi dari writer.</div>
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