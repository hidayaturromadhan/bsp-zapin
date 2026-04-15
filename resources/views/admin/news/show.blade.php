@extends('layouts.admin')

@section('title', 'Detail News')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <a href="{{ route('admin.news.index') }}" style="color:var(--text3)">News</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>Detail</span>
        </div>
        <h1 class="a-page-title">{{ $tId->title ?? 'Detail News' }}</h1>
        <p class="a-page-desc">Admin mode monitoring: hanya lihat detail, status review, dan logs.</p>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('admin.news.logs', $news) }}" class="a-btn a-btn--secondary">Lihat Logs</a>
        <a href="{{ route('admin.news.index') }}" class="a-btn a-btn--secondary">Kembali</a>
    </div>
</div>

<div class="a-card" style="margin-bottom:20px;">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Status Review</div>
            <div class="a-card-desc">Informasi status approval dan publish</div>
        </div>
    </div>

    <div class="a-card-body" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;">
        <div>
            <div class="a-label">Status</div>
            <div>
                @if($news->status === 'published')
                    <span class="a-badge a-badge--green">Published</span>
                @elseif($news->status === 'in_review')
                    <span class="a-badge a-badge--blue">In Review</span>
                @elseif($news->status === 'rejected')
                    <span class="a-badge a-badge--red">Rejected</span>
                @elseif($news->status === 'draft')
                    <span class="a-badge a-badge--gray">Draft</span>
                @else
                    <span class="a-badge a-badge--gray">{{ ucfirst($news->status) }}</span>
                @endif
            </div>
        </div>

        <div>
            <div class="a-label">Kategori</div>
            <div>{{ $news->category->name ?? '-' }}</div>
        </div>

        <div>
            <div class="a-label">Author</div>
            <div>{{ $news->author->name ?? '-' }}</div>
        </div>

        <div>
            <div class="a-label">Reviewer</div>
            <div>{{ $news->reviewer->name ?? '-' }}</div>
        </div>

        <div>
            <div class="a-label">Published At</div>
            <div>{{ $news->published_at ? $news->published_at->format('d M Y H:i') : '-' }}</div>
        </div>

        <div>
            <div class="a-label">Reviewed At</div>
            <div>{{ $news->reviewed_at ? $news->reviewed_at->format('d M Y H:i') : '-' }}</div>
        </div>

        <div>
            <div class="a-label">Visible di Public</div>
            <div>{{ $news->is_visible ? 'Ya' : 'Tidak' }}</div>
        </div>

        <div>
            <div class="a-label">Featured</div>
            <div>{{ $news->is_featured ? 'Ya' : 'Tidak' }}</div>
        </div>

        <div style="grid-column:1/-1;">
            <div class="a-label">Review Note</div>
            <div>{{ $news->review_note ?: '-' }}</div>
        </div>
    </div>
</div>

@if($news->featured_image)
    <div class="a-card" style="margin-bottom:20px;">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Featured Image</div>
            </div>
        </div>
        <div class="a-card-body">
            <img
                src="{{ asset($news->featured_image) }}"
                alt="{{ $tId->title ?? 'Featured image' }}"
                style="width:100%;max-height:420px;object-fit:cover;border-radius:14px;border:1px solid var(--line);"
            >
        </div>
    </div>
@endif

<div class="a-card" style="margin-bottom:20px;">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Konten Bahasa Indonesia</div>
            <div class="a-card-desc">Versi utama yang dibuat writer/reviewer</div>
        </div>
    </div>
    <div class="a-card-body">
        <div class="a-form-group">
            <label class="a-label">Judul</label>
            <div>{{ $tId->title ?? '-' }}</div>
        </div>

        <div class="a-form-group">
            <label class="a-label">Slug</label>
            <div>{{ $tId->slug ?? '-' }}</div>
        </div>

        <div class="a-form-group">
            <label class="a-label">Excerpt</label>
            <div>{{ $tId->excerpt ?? '-' }}</div>
        </div>

        <div class="a-form-group" style="margin-bottom:0;">
            <label class="a-label">Content</label>
            <div class="a-editor-output">
                {!! $tId->content ?? '-' !!}
            </div>
        </div>
    </div>
</div>

<div class="a-card" style="margin-bottom:20px;">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Konten Bahasa Inggris</div>
            <div class="a-card-desc">Hasil auto translate</div>
        </div>
    </div>
    <div class="a-card-body">
        <div class="a-form-group">
            <label class="a-label">Title</label>
            <div>{{ $tEn->title ?? '-' }}</div>
        </div>

        <div class="a-form-group">
            <label class="a-label">Slug</label>
            <div>{{ $tEn->slug ?? '-' }}</div>
        </div>

        <div class="a-form-group">
            <label class="a-label">Excerpt</label>
            <div>{{ $tEn->excerpt ?? '-' }}</div>
        </div>

        <div class="a-form-group" style="margin-bottom:0;">
            <label class="a-label">Content</label>
            <div class="a-editor-output">
                {!! $tEn->content ?? '-' !!}
            </div>
        </div>
    </div>
</div>

@if($news->images->count())
    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Galeri</div>
                <div class="a-card-desc">{{ $news->images->count() }} gambar</div>
            </div>
        </div>

        <div class="a-card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;">
                @foreach($news->images as $image)
                    <div style="border:1px solid var(--line);border-radius:14px;overflow:hidden;background:#fff;">
                        <img
                            src="{{ asset($image->image_path) }}"
                            alt="News image"
                            style="width:100%;height:150px;object-fit:cover;display:block;"
                        >
                        <div style="padding:10px 12px;font-size:12px;color:var(--text3);">
                            Urutan: {{ $image->sort_order }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@endsection