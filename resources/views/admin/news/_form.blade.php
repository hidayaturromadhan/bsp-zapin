<style>
    .news-form-page { max-width: 1200px; }
    .news-form-grid { display:grid; grid-template-columns:1fr; gap:16px; }
    .news-form-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:18px; box-shadow:0 8px 20px rgba(15,23,42,.04); }
    .news-form-section-title { margin:0 0 6px; font-size:20px; font-weight:800; color:#111827; }
    .news-form-section-subtitle { margin:0 0 14px; font-size:13px; color:#6b7280; }
    .news-form-inner-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .news-form-group { margin-bottom:14px; }
    .news-form-group:last-child { margin-bottom:0; }
    .news-form-group.full { grid-column:1 / -1; }
    .news-form-label { display:block; margin-bottom:7px; font-size:14px; font-weight:700; color:#111827; }
    .news-form-help { margin-top:6px; font-size:12px; color:#6b7280; line-height:1.6; }
    .news-form-input, .news-form-select, .news-form-textarea, .news-form-file { width:100%; border:1px solid #d1d5db; border-radius:10px; padding:10px 12px; font:inherit; color:#111827; background:#fff; }
    .news-form-select { min-height:44px; }
    .news-form-textarea { min-height:110px; resize:vertical; line-height:1.7; }
    .news-form-checks { display:flex; flex-wrap:wrap; gap:16px; align-items:center; min-height:44px; }
    .news-form-check { display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#111827; }
    .news-form-actions { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-top:18px; }
    .news-form-submit { min-height:44px; padding:0 18px; border:none; border-radius:10px; background:#173f08; color:#fff; font:inherit; font-weight:700; cursor:pointer; }
    .news-form-submit:hover { background:#21560e; }
    .news-form-cancel { display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:0 16px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700; }
    .news-form-cancel:hover { background:#f9fafb; }
    .news-form-inline { display:flex; gap:8px; align-items:center; }
    .news-form-inline .news-form-input { flex:1; }
    .news-form-mini-btn { min-height:42px; padding:0 12px; border:1px solid #d1d5db; border-radius:10px; background:#fff; color:#111827; font:inherit; font-size:13px; font-weight:700; cursor:pointer; }
    .news-form-mini-btn:hover { background:#f9fafb; }
    .news-blocks { display:flex; flex-direction:column; gap:12px; }
    .news-block-item { border:1px dashed #d1d5db; border-radius:14px; padding:14px; background:#fafafa; }
    .news-block-top { display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:12px; flex-wrap:wrap; }
    .news-block-actions { display:flex; gap:8px; flex-wrap:wrap; }
    .news-gallery-list { display:grid; grid-template-columns:repeat(auto-fill, minmax(150px, 1fr)); gap:12px; margin-top:14px; }
    .news-gallery-item img { width:100%; height:110px; object-fit:cover; border-radius:10px; border:1px solid #e5e7eb; display:block; }
    @media (max-width: 900px) {
        .news-form-inner-grid { grid-template-columns:1fr; }
        .news-form-inline { flex-direction:column; align-items:stretch; }
    }
</style>

<div class="news-form-grid">
    <div class="news-form-card">
        <h3 class="news-form-section-title">Pengaturan Umum</h3>
        <p class="news-form-section-subtitle">Atur kategori, status tayang, visibilitas, dan gambar berita.</p>

        <div class="news-form-inner-grid">
            <div class="news-form-group">
                <label class="news-form-label">Kategori Berita *</label>
                <select name="news_category_id" required class="news-form-select">
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('news_category_id', $news->news_category_id ?? '') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="news-form-group">
                <label class="news-form-label">Status Publikasi *</label>
                <select name="status" required class="news-form-select">
                    @foreach(['draft','published','archived'] as $st)
                        <option value="{{ $st }}" {{ old('status', $news->status ?? 'draft') === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="news-form-group">
                <label class="news-form-label">Tanggal Publish</label>
                <input type="date" name="published_at" value="{{ old('published_at', isset($news) ? optional($news->published_at)->format('Y-m-d') : '') }}" class="news-form-input">
            </div>

            <div class="news-form-group">
                <label class="news-form-label">Pengaturan Tampil</label>
                <div class="news-form-checks">
                    <label class="news-form-check">
                        <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $news->is_visible ?? 1) ? 'checked' : '' }}>
                        Tampilkan di website
                    </label>

                    <label class="news-form-check">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $news->is_featured ?? 0) ? 'checked' : '' }}>
                        Jadikan berita unggulan
                    </label>
                </div>
            </div>

            <div class="news-form-group full">
                <label class="news-form-label">Judul Berita (Indonesia) *</label>
                <input type="text" name="id_title" id="id_title" value="{{ old('id_title', $tId->title ?? '') }}" required class="news-form-input">
            </div>

            <div class="news-form-group full">
                <label class="news-form-label">Slug URL *</label>
                <div class="news-form-inline">
                    <input type="text" name="id_slug" id="id_slug" value="{{ old('id_slug', $tId->slug ?? '') }}" class="news-form-input">
                    <button type="button" class="news-form-mini-btn" id="reset_id_slug">Buat Ulang dari Judul</button>
                </div>
                <div class="news-form-help">Slug dibuat otomatis dari judul, tapi tetap bisa diubah manual.</div>
            </div>

            <div class="news-form-group full">
                <label class="news-form-label">Ringkasan Singkat Berita</label>
                <textarea name="id_excerpt" rows="4" class="news-form-textarea">{{ old('id_excerpt', $tId->excerpt ?? '') }}</textarea>
            </div>

            <div class="news-form-group full">
                <label class="news-form-label">Gambar Utama Berita</label>
                <input type="file" name="featured_image" class="news-form-file">

                @if(!empty($news?->featured_image))
                    <div class="news-gallery-list" style="margin-top:12px;">
                        <div class="news-gallery-item">
                            <img src="{{ asset($news->featured_image) }}" alt="Featured">
                        </div>
                    </div>
                @endif
            </div>

            <div class="news-form-group full">
                <label class="news-form-label">Galeri Foto Tambahan</label>
                <input type="file" name="gallery_images[]" multiple class="news-form-file">
                <div class="news-form-help">Bisa upload banyak foto sekaligus.</div>

                @if(!empty($news) && $news->images->count())
                    <div class="news-gallery-list">
                        @foreach($news->images as $img)
                            <div class="news-gallery-item">
                                <img src="{{ asset($img->image_path) }}" alt="Gallery">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="news-form-card">
        <h3 class="news-form-section-title">Isi Berita</h3>
        <p class="news-form-section-subtitle">Susun isi berita secara dinamis. Blok bisa dipindah naik atau turun sesuai kebutuhan.</p>

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
            <button type="button" class="news-form-mini-btn" id="add_heading_block">+ Tambah Judul Bagian</button>
            <button type="button" class="news-form-mini-btn" id="add_text_block">+ Tambah Paragraf</button>
            <button type="button" class="news-form-mini-btn" id="add_image_block">+ Tambah Gambar di Konten</button>
        </div>

        <div id="news_blocks" class="news-blocks"></div>
    </div>
</div>

<div class="news-form-actions">
    <button type="submit" class="news-form-submit">{{ $submitLabel ?? 'Simpan' }}</button>
    <a href="{{ route('admin.news.index') }}" class="news-form-cancel">Batal</a>
</div>

<script>
(function () {
    const blocksContainer = document.getElementById('news_blocks');

    function slugify(text) {
        return (text || '')
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .replace(/-{2,}/g, '-');
    }

    function bindSlug() {
        const title = document.getElementById('id_title');
        const slug = document.getElementById('id_slug');
        const reset = document.getElementById('reset_id_slug');

        if (!title || !slug) return;

        let manual = !!slug.value;

        title.addEventListener('input', function () {
            if (!manual) {
                slug.value = slugify(title.value);
            }
        });

        slug.addEventListener('input', function () {
            manual = true;
        });

        reset.addEventListener('click', function () {
            manual = false;
            slug.value = slugify(title.value);
        });
    }

    function renumberBlocks() {
        const items = blocksContainer.querySelectorAll('.news-block-item');

        items.forEach((item, index) => {
            item.querySelectorAll('[data-name]').forEach((input) => {
                input.name = input.dataset.name.replace('__INDEX__', index);
            });
        });
    }

    function createBlock(type, data = {}) {
        const wrapper = document.createElement('div');
        wrapper.className = 'news-block-item';

        let inner = `
            <div class="news-block-top">
                <strong>${type === 'heading' ? 'Judul Bagian' : type === 'text' ? 'Paragraf' : 'Gambar Konten'}</strong>
                <div class="news-block-actions">
                    <button type="button" class="news-form-mini-btn move-up">Naik</button>
                    <button type="button" class="news-form-mini-btn move-down">Turun</button>
                    <button type="button" class="news-form-mini-btn remove-block">Hapus</button>
                </div>
            </div>
            <input type="hidden" data-name="blocks[__INDEX__][type]" value="${type}">
        `;

        if (type === 'heading') {
            inner += `
                <div class="news-form-group">
                    <label class="news-form-label">Isi Judul Bagian</label>
                    <input type="text" class="news-form-input" data-name="blocks[__INDEX__][title]" value="${escapeHtml(data.title || '')}">
                </div>
            `;
        }

        if (type === 'text') {
            inner += `
                <div class="news-form-group">
                    <label class="news-form-label">Isi Paragraf</label>
                    <textarea class="news-form-textarea" rows="6" data-name="blocks[__INDEX__][body]">${escapeHtml(data.body || '')}</textarea>
                </div>
            `;
        }

        if (type === 'image') {
            inner += `
                <input type="hidden" data-name="blocks[__INDEX__][existing_image]" value="${escapeHtml(data.image || '')}">
                <div class="news-form-group">
                    <label class="news-form-label">Upload Gambar</label>
                    <input type="file" class="news-form-file" data-name="block_images[__INDEX__]">
                </div>
                <div class="news-form-group">
                    <label class="news-form-label">Caption Gambar</label>
                    <input type="text" class="news-form-input" data-name="blocks[__INDEX__][caption]" value="${escapeHtml(data.caption || '')}">
                </div>
                ${data.image ? `<div class="news-gallery-item" style="max-width:220px;"><img src="/${data.image}" style="width:100%;height:140px;object-fit:cover;border-radius:10px;border:1px solid #e5e7eb;"></div>` : ''}
            `;
        }

        wrapper.innerHTML = inner;
        blocksContainer.appendChild(wrapper);
        renumberBlocks();
    }

    function escapeHtml(str) {
        return (str || '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    document.getElementById('add_heading_block').addEventListener('click', function () {
        createBlock('heading');
    });

    document.getElementById('add_text_block').addEventListener('click', function () {
        createBlock('text');
    });

    document.getElementById('add_image_block').addEventListener('click', function () {
        createBlock('image');
    });

    blocksContainer.addEventListener('click', function (e) {
        const item = e.target.closest('.news-block-item');
        if (!item) return;

        if (e.target.classList.contains('remove-block')) {
            item.remove();
            renumberBlocks();
        }

        if (e.target.classList.contains('move-up')) {
            const prev = item.previousElementSibling;
            if (prev) {
                blocksContainer.insertBefore(item, prev);
                renumberBlocks();
            }
        }

        if (e.target.classList.contains('move-down')) {
            const next = item.nextElementSibling;
            if (next) {
                blocksContainer.insertBefore(next, item);
                renumberBlocks();
            }
        }
    });

    bindSlug();

    const initialBlocks = @json($blocks ?? []);
    if (initialBlocks.length) {
        initialBlocks.forEach(block => createBlock(block.type || 'text', block));
    } else {
        createBlock('heading');
        createBlock('text');
    }
})();
</script>