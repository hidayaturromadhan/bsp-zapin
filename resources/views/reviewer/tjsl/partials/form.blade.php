@php
    $trId = $program->relationLoaded('translations')
        ? $program->translations->firstWhere('locale', 'id')
        : null;

    $trEn = $program->relationLoaded('translations')
        ? $program->translations->firstWhere('locale', 'en')
        : null;

    $galleryCount = $program->exists ? $program->images->count() : 0;
    $remainingGallerySlots = max(0, 5 - $galleryCount);
@endphp

@if($errors->any())
    <div class="a-alert a-alert--danger">
        <strong>Terjadi kesalahan.</strong>
        <ul style="margin:8px 0 0 18px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="a-alert a-alert--success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="a-alert a-alert--danger">{{ session('error') }}</div>
@endif

<form id="reviewerTjslUpdateForm" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf

    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="a-card" style="margin-bottom:16px">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Informasi Program</div>
                <div class="a-card-desc">Data utama program TJSL</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px">
            <div>
                <label class="a-label">Tahun <span style="color:#dc2626">*</span></label>
                <input type="number" name="year" value="{{ old('year', $program->year) }}" class="a-input" min="2000" max="{{ date('Y') + 5 }}" required>
            </div>

            <div>
                <label class="a-label">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order ?? 0) }}" class="a-input" min="0">
            </div>

            <div>
                <label class="a-label">Featured Image</label>
                <input type="file" name="featured_image" class="a-input" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <div style="font-size:12px;color:#64748b;margin-top:6px">
                    Format: JPG, PNG, WEBP. Maksimal 2MB.
                </div>
            </div>
        </div>

        @if($program->featured_image)
            <div style="margin-top:14px">
                <div style="font-size:12px;color:#6b7280;margin-bottom:6px">Featured image saat ini:</div>
                <img src="{{ asset($program->featured_image) }}" alt="Featured TJSL" style="width:180px;height:110px;object-fit:cover;border-radius:12px;border:1px solid #e5e7eb">
            </div>
        @endif
    </div>

    <div class="a-card" style="margin-bottom:16px">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Konten Bahasa Indonesia</div>
                <div class="a-card-desc">Edit konten Bahasa Indonesia. English otomatis diperbarui menggunakan DeepL.</div>
            </div>

            <span class="a-badge a-badge--blue">Auto Translate EN</span>
        </div>

        <div style="display:grid;gap:14px">
            <div>
                <label class="a-label">Judul <span style="color:#dc2626">*</span></label>
                <input type="text" name="title_id" value="{{ old('title_id', $trId->title ?? '') }}" class="a-input" maxlength="190" required>
            </div>

            <div>
                <label class="a-label">Ringkasan</label>
                <textarea name="summary_id" class="a-input" rows="3">{{ old('summary_id', $trId->summary ?? '') }}</textarea>
            </div>

            <div>
                <label class="a-label">Konten</label>
                <textarea name="content_id" class="a-input" rows="10">{{ old('content_id', $trId->content ?? '') }}</textarea>
            </div>
        </div>

        @if($trEn && ($trEn->title || $trEn->summary || $trEn->content))
            <div style="margin-top:18px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:14px">
                <div style="font-weight:900;color:#0f172a;margin-bottom:8px">Preview English Saat Ini</div>

                <div style="font-size:13px;color:#475569;line-height:1.7">
                    <strong>Title:</strong> {{ $trEn->title ?: '-' }}<br>
                    <strong>Summary:</strong> {{ $trEn->summary ?: '-' }}
                </div>

                @if($trEn->content)
                    <details style="margin-top:10px">
                        <summary style="cursor:pointer;font-weight:800;color:#173f08">Lihat content EN</summary>
                        <div style="margin-top:8px;color:#475569;line-height:1.7">
                            {!! nl2br(e($trEn->content)) !!}
                        </div>
                    </details>
                @endif
            </div>
        @endif
    </div>

    <div class="a-card" style="margin-bottom:16px">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Tambah Galeri Dokumentasi</div>
                <div class="a-card-desc">
                    Maksimal 5 gambar total. Saat ini: {{ $galleryCount }}/5.
                    Format: JPG, PNG, WEBP. Maksimal 2MB per gambar.
                </div>
            </div>

            <span class="a-badge {{ $remainingGallerySlots > 0 ? 'a-badge--green' : 'a-badge--red' }}">
                Sisa {{ $remainingGallerySlots }} slot
            </span>
        </div>

        @if($remainingGallerySlots > 0)
            <div style="display:grid;gap:12px">
                @for($i = 0; $i < $remainingGallerySlots; $i++)
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div>
                            @if($i === 0)
                                <label class="a-label">Gambar Galeri</label>
                            @endif

                            <input type="file" name="gallery_images[]" class="a-input" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        </div>

                        <div>
                            @if($i === 0)
                                <label class="a-label">Caption</label>
                            @endif

                            <input type="text" name="gallery_captions[]" class="a-input" maxlength="190" placeholder="Caption gambar">
                        </div>
                    </div>
                @endfor
            </div>
        @else
            <div class="a-alert a-alert--warning" style="margin-bottom:0">
                Galeri sudah mencapai batas maksimal 5 gambar. Hapus salah satu gambar jika ingin mengganti.
            </div>
        @endif
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;margin-bottom:16px">
        <a href="{{ route('reviewer.tjsl.show', $program) }}" class="a-btn a-btn--secondary">Batal</a>
        <button type="submit" class="a-btn a-btn--primary">
            Simpan Perubahan
        </button>
    </div>
</form>

@if($program->exists && $program->images->count())
    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Galeri Saat Ini</div>
                <div class="a-card-desc">{{ $galleryCount }}/5 gambar tersimpan</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px">
            @foreach($program->images as $image)
                <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;background:#fff">
                    <img src="{{ asset($image->image_path) }}" alt="Galeri TJSL" style="width:100%;height:110px;object-fit:cover">

                    <div style="padding:10px">
                        <div style="font-size:12px;color:#6b7280;margin-bottom:8px">
                            {{ $image->caption ?: 'Tanpa caption' }}
                        </div>

                        <form method="POST" action="{{ route('reviewer.tjsl.images.destroy', [$program, $image]) }}" onsubmit="return confirm('Hapus gambar ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="a-btn a-btn--danger a-btn--sm" style="width:100%">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif