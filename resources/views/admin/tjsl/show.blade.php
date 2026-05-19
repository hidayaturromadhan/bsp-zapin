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
        <p class="a-page-desc">
            Admin hanya melihat aktivitas TJSL. Konten English dibuat otomatis menggunakan DeepL.
        </p>
    </div>

    <a href="{{ route('admin.tjsl.index') }}" class="a-btn a-btn--secondary">
        Kembali
    </a>
</div>

<div class="a-alert a-alert--info">
    Mode monitoring: admin tidak memiliki tombol tambah, edit, hapus, approve, reject, publish, atau unpublish untuk TJSL.
</div>

{{-- Status Aktivitas --}}
<div class="a-card" style="margin-bottom:18px;">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Status Aktivitas</div>
            <div class="a-card-desc">Informasi workflow TJSL</div>
        </div>

        <span class="a-badge {{ $badgeClass }}">
            {{ $program->status_label }}
        </span>
    </div>

    <div style="padding:18px 20px 20px;">
        <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;">
            <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;">
                <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Writer</div>
                <div style="font-weight:800;color:#111827;">
                    {{ $program->author?->name ?? '-' }}
                </div>
                <div style="font-size:12px;color:#6b7280;margin-top:3px;">
                    {{ $program->author?->email ?? '-' }}
                </div>
            </div>

            <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;">
                <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Submitted At</div>
                <div style="font-weight:800;color:#111827;">
                    {{ $program->submitted_at?->format('d M Y H:i') ?? '-' }}
                </div>
            </div>

            <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;">
                <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Reviewer</div>
                <div style="font-weight:800;color:#111827;">
                    {{ $program->reviewer?->name ?? '-' }}
                </div>
                <div style="font-size:12px;color:#6b7280;margin-top:3px;">
                    {{ $program->reviewer?->email ?? '-' }}
                </div>
            </div>

            <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;">
                <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Published At</div>
                <div style="font-weight:800;color:#111827;">
                    {{ $program->published_at?->format('d M Y H:i') ?? '-' }}
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-top:14px;">
            <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#fff;">
                <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Reviewed At</div>
                <div style="font-weight:800;color:#111827;">
                    {{ $program->reviewed_at?->format('d M Y H:i') ?? '-' }}
                </div>
            </div>

            <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#fff;">
                <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Rejected At</div>
                <div style="font-weight:800;color:#111827;">
                    {{ $program->rejected_at?->format('d M Y H:i') ?? '-' }}
                </div>
            </div>

            <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#fff;">
                <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Status</div>
                <div style="font-weight:800;color:#111827;">
                    {{ $program->status_label }}
                </div>
            </div>
        </div>

        @if($program->review_note)
            <div style="margin-top:16px;background:#fffbeb;border:1px solid #fde68a;color:#92400e;padding:16px;border-radius:14px;line-height:1.7;">
                <strong>Catatan Review:</strong><br>
                {{ $program->review_note }}
            </div>
        @endif
    </div>
</div>

{{-- Informasi Program --}}
<div class="a-card" style="margin-bottom:18px;">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Informasi Program</div>
            <div class="a-card-desc">Data utama program TJSL</div>
        </div>
    </div>

    <div style="padding:18px 20px 20px;">
        <div style="display:grid;grid-template-columns:260px 1fr;gap:20px;align-items:start;">
            <div>
                @if($program->featured_image)
                    <img src="{{ asset($program->featured_image) }}"
                         alt="Featured TJSL"
                         style="width:100%;height:175px;object-fit:cover;border-radius:16px;border:1px solid #e5e7eb;">
                @else
                    <div style="height:175px;border-radius:16px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;color:#6b7280;border:1px solid #e5e7eb;">
                        No Image
                    </div>
                @endif
            </div>

            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;">
                <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;">
                    <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Tahun</div>
                    <div style="font-weight:900;font-size:20px;color:#111827;">
                        {{ $program->year }}
                    </div>
                </div>

                <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;">
                    <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Sort Order</div>
                    <div style="font-weight:900;font-size:20px;color:#111827;">
                        {{ $program->sort_order }}
                    </div>
                </div>

                <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#fff;">
                    <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Is Active</div>
                    <div style="font-weight:800;color:#111827;">
                        {{ $program->is_active ? 'Ya' : 'Tidak' }}
                    </div>
                </div>

                <div style="padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#fff;">
                    <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Galeri</div>
                    <div style="font-weight:800;color:#111827;">
                        {{ $program->images->count() }} foto
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Konten Bahasa Indonesia --}}
<div class="a-card" style="margin-bottom:18px;">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Konten Bahasa Indonesia</div>
            <div class="a-card-desc">Konten source yang dibuat oleh writer.</div>
        </div>

        <span class="a-badge a-badge--green">Source</span>
    </div>

    <div style="padding:22px 28px 26px;">
        <h2 style="font-size:24px;font-weight:900;margin:0 0 16px;color:#111827;line-height:1.45;max-width:1200px;">
            {{ $trId->title ?? '-' }}
        </h2>

        <div style="height:1px;background:#eef2f7;margin:0 0 18px;"></div>

        <p style="color:#4b5563;margin:0 0 22px;line-height:1.85;font-size:15px;max-width:1260px;">
            {{ $trId->summary ?? '-' }}
        </p>

        <div style="line-height:1.95;color:#374151;font-size:15px;max-width:1260px;">
            {!! nl2br(e($trId->content ?? '-')) !!}
        </div>
    </div>
</div>

{{-- Konten English --}}
<div class="a-card" style="margin-bottom:18px;">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Preview English Otomatis</div>
            <div class="a-card-desc">Hasil terjemahan otomatis dari DeepL.</div>
        </div>

        <span class="a-badge a-badge--blue">Auto Translate</span>
    </div>

    <div style="padding:22px 28px 26px;">
        <h2 style="font-size:24px;font-weight:900;margin:0 0 16px;color:#111827;line-height:1.45;max-width:1200px;">
            {{ $trEn->title ?? '-' }}
        </h2>

        <div style="height:1px;background:#eef2f7;margin:0 0 18px;"></div>

        <p style="color:#4b5563;margin:0 0 22px;line-height:1.85;font-size:15px;max-width:1260px;">
            {{ $trEn->summary ?? '-' }}
        </p>

        <div style="line-height:1.95;color:#374151;font-size:15px;max-width:1260px;">
            {!! nl2br(e($trEn->content ?? '-')) !!}
        </div>
    </div>
</div>

{{-- Galeri --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Galeri Dokumentasi</div>
            <div class="a-card-desc">Foto dokumentasi dari writer.</div>
        </div>
    </div>

    <div style="padding:20px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:16px;">
            @forelse($program->images as $image)
                <div style="border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff;">
                    <img src="{{ asset($image->image_path) }}"
                         alt="Galeri TJSL"
                         style="width:100%;height:140px;object-fit:cover;display:block;">

                    <div style="padding:12px 14px;font-size:12px;color:#6b7280;line-height:1.6;">
                        {{ $image->caption ?: 'Tanpa caption' }}
                    </div>
                </div>
            @empty
                <div style="grid-column:1 / -1;color:#6b7280;background:#f9fafb;border:1px dashed #d1d5db;border-radius:14px;padding:20px;text-align:center;">
                    Belum ada gambar galeri.
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection