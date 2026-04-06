<div style="display:grid; grid-template-columns:1fr 340px; gap:22px; align-items:start;">
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:18px;">
        <div style="margin-bottom:16px;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Nama *</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $partner->name ?? '') }}"
                   required
                   style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:10px;">
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Kategori *</label>
            <select name="category"
                    required
                    style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:10px; background:#fff;">
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('category', $partner->category ?? \App\Models\Partner::CATEGORY_BUSINESS_PARTNER) === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <div style="margin-top:8px; font-size:12px; color:#6b7280;">
                Pilih apakah data ini termasuk pelanggan atau mitra bisnis.
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Website URL</label>
            <input type="url"
                   name="website_url"
                   value="{{ old('website_url', $partner->website_url ?? '') }}"
                   placeholder="https://example.com"
                   style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:10px;">
            <div style="margin-top:8px; font-size:12px; color:#6b7280;">
                Opsional. Jika diisi, logo akan bisa diklik menuju website.
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:10px; margin-top:4px;">
            <label style="display:flex; align-items:center; gap:8px; font-weight:700;">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       {{ old('is_active', $partner->is_active ?? true) ? 'checked' : '' }}>
                Aktif
            </label>
        </div>

        <div style="margin-top:18px; padding:14px 16px; border-radius:12px; background:#f8fafc; border:1px solid #e5e7eb; color:#475467; font-size:13px; line-height:1.6;">
            Urutan tampil di homepage diatur otomatis oleh sistem berdasarkan kategori dan waktu input data.
            Admin tidak perlu mengatur urutan manual.
        </div>
    </div>

    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:18px;">
        <div style="margin-bottom:12px;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Logo {{ isset($partner) ? '' : '*' }}</label>
            <input type="file" name="logo" {{ isset($partner) ? '' : 'required' }}>
            <div style="margin-top:8px; font-size:12px; color:#6b7280;">
                Format: JPG, PNG, WEBP, SVG
            </div>
        </div>

        @if(!empty($partner?->logo_path))
            <div style="margin-top:14px;">
                <img src="{{ asset($partner->logo_path) }}"
                     alt="{{ $partner->name }}"
                     style="width:100%; max-height:180px; object-fit:contain; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px;">
            </div>
        @endif
    </div>
</div>

<div style="margin-top:18px; display:flex; gap:10px; flex-wrap:wrap;">
    <button type="submit"
            style="display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:0 18px; border-radius:10px; border:0; background:#173f08; color:#fff; font-weight:700; cursor:pointer;">
        {{ $submitLabel }}
    </button>

    <a href="{{ route('admin.partners.index') }}"
       style="display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:0 18px; border-radius:10px; border:1px solid #d1d5db; background:#fff; color:#111827; text-decoration:none; font-weight:700;">
        Kembali
    </a>
</div>