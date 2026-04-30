@php
    $reportData = $report ?? null;
@endphp

<div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:18px;">
    <div class="form-group">
        <label for="category">Kategori</label>
        <select name="category" id="category" class="select" required>
            <option value="">Pilih kategori</option>
            @foreach($categoryOptions as $value => $label)
                <option value="{{ $value }}" {{ old('category', $reportData->category ?? '') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="incident_date">Tanggal Kejadian</label>
        <input
            type="date"
            name="incident_date"
            id="incident_date"
            class="input"
            value="{{ old('incident_date', optional($reportData?->incident_date)->format('Y-m-d')) }}"
        >
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="title">Judul Laporan</label>
        <input
            type="text"
            name="title"
            id="title"
            class="input"
            value="{{ old('title', $reportData->title ?? '') }}"
            required
        >
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="description">Pokok Masalah</label>
        <textarea name="description" id="description" class="textarea" required>{{ old('description', $reportData->description ?? '') }}</textarea>
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="involved_parties">Pihak yang Terlibat</label>
        <textarea name="involved_parties" id="involved_parties" class="textarea">{{ old('involved_parties', $reportData->involved_parties ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="location">Lokasi</label>
        <input
            type="text"
            name="location"
            id="location"
            class="input"
            value="{{ old('location', $reportData->location ?? '') }}"
        >
    </div>

    <div class="form-group">
        <label for="estimated_loss">Estimasi Kerugian</label>
        <input
            type="text"
            name="estimated_loss"
            id="estimated_loss"
            class="input"
            value="{{ old('estimated_loss', $reportData->estimated_loss ?? '') }}"
            placeholder="Contoh: sekitar Rp 50 juta / belum dapat dipastikan"
        >
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="chronology">Kronologi</label>
        <textarea name="chronology" id="chronology" class="textarea">{{ old('chronology', $reportData->chronology ?? '') }}</textarea>
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label>Informasi Tambahan</label>

        <div style="display:flex; gap:18px; flex-wrap:wrap;">
            <label style="display:flex; align-items:center; gap:8px; font-size:13px;">
                <input type="hidden" name="has_evidence" value="0">
                <input type="checkbox" name="has_evidence" value="1" {{ old('has_evidence', $reportData->has_evidence ?? false) ? 'checked' : '' }}>
                Ada bukti
            </label>

            <label style="display:flex; align-items:center; gap:8px; font-size:13px;">
                <input type="hidden" name="reported_before" value="0">
                <input type="checkbox" name="reported_before" value="1" {{ old('reported_before', $reportData->reported_before ?? false) ? 'checked' : '' }}>
                Pernah dilaporkan sebelumnya
            </label>

            <label style="display:flex; align-items:center; gap:8px; font-size:13px;">
                <input type="hidden" name="reported_to_other_party" value="0">
                <input type="checkbox" name="reported_to_other_party" value="1" {{ old('reported_to_other_party', $reportData->reported_to_other_party ?? false) ? 'checked' : '' }}>
                Juga dilaporkan ke pihak lain
            </label>
        </div>
    </div>

    @if($reportData && $reportData->attachments->count())
        <div class="form-group" style="grid-column:1 / -1;">
            <label>Lampiran Saat Ini</label>

            <div style="display:grid; gap:12px;">
                @foreach($reportData->attachments as $attachment)
                    <div style="padding:14px; border:1px solid #e5e7eb; border-radius:12px; background:#fff; display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center;">
                        <div>
                            <div style="font-weight:700;">{{ $attachment->original_name }}</div>
                            <div style="font-size:12px; color:#64748b;">{{ $attachment->file_size_label }}</div>
                        </div>

                        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
                            <a href="{{ $attachment->file_url }}" target="_blank" class="wbs-btn wbs-btn-light">Lihat File</a>

                            <label style="display:flex; align-items:center; gap:8px; font-size:13px;">
                                <input type="checkbox" name="delete_attachment_ids[]" value="{{ $attachment->id }}">
                                Hapus
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="attachments">Lampiran Baru</label>
        <input type="file" name="attachments[]" id="attachments" class="input" multiple>
        <div style="font-size:12px; color:#64748b; margin-top:6px;">
            Maksimal 5 file. Format: pdf, jpg, jpeg, png, doc, docx, xls, xlsx. Maksimal 5 MB per file.
        </div>
    </div>
</div>

<div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:18px;">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('wbs.pelapor.reports.index') }}" class="btn btn-light">Kembali</a>
</div>