@extends('layouts.admin')

@section('title', 'Edit Kategori GCG')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <a href="{{ route('admin.gcg.index') }}" style="color:var(--text3)">GCG</a>
            <span class="a-breadcrumb-sep">›</span>
            <span>{{ $translationId->name ?? 'Edit' }}</span>
        </div>
        <h1 class="a-page-title">Edit Kategori GCG</h1>
        <p class="a-page-desc">Perbarui informasi kategori dan kelola dokumen</p>
    </div>
    <a href="{{ route('admin.gcg.index') }}" class="a-btn a-btn--secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali
    </a>
</div>

@if(session('success'))
    <div class="a-alert a-alert--success">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="a-alert a-alert--error">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
        <ul style="margin:0;padding-left:16px">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

{{-- ═══ FORM EDIT KATEGORI ═══ --}}
<form action="{{ route('admin.gcg.update', $gcg) }}" method="POST">
    @csrf @method('PUT')

    <div class="a-card">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Informasi Kategori</div>
                <div class="a-card-desc">Edit dalam Bahasa Indonesia, EN otomatis di-retranslate</div>
            </div>
        </div>
        <div class="a-card-body">
            <div class="a-form-group">
                <label class="a-label">Nama Kategori <span style="color:#dc2626">*</span></label>
                <input type="text" name="name" class="a-input" required
                       value="{{ old('name', $translationId->name ?? '') }}">
            </div>
            <div class="a-form-group" style="margin-bottom:0">
                <label class="a-label">Deskripsi <span class="a-label-hint">(opsional)</span></label>
                <textarea name="description" class="a-textarea" rows="5">{{ old('description', $translationId->description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="a-card">
        <div class="a-card-head">
            <div class="a-card-title">⚙️ Pengaturan</div>
        </div>
        <div class="a-card-body">
            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $gcg->is_active) ? 'checked' : '' }}
                       style="width:18px;height:18px;accent-color:var(--g500)">
                <span class="a-label" style="margin:0">Tampilkan di website</span>
            </label>
        </div>
    </div>

    <div style="display:flex;gap:10px;margin-bottom:32px">
        <button type="submit" class="a-btn a-btn--primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
            Update Kategori
        </button>
        <a href="{{ route('admin.gcg.index') }}" class="a-btn a-btn--secondary">Batal</a>
    </div>
</form>

{{-- ═══ DAFTAR DOKUMEN ═══ --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">
                Dokumen
                <span class="a-badge a-badge--blue" style="margin-left:8px">{{ $gcg->documents->count() }}</span>
            </div>
            <div class="a-card-desc">Upload dan kelola dokumen untuk kategori ini</div>
        </div>
        <button class="a-btn a-btn--primary a-btn--sm"
                onclick="document.getElementById('modalUpload').style.display='flex'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="16 16 12 12 8 16"/>
                <line x1="12" y1="12" x2="12" y2="21"/>
                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
            </svg>
            Upload Dokumen
        </button>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Judul Dokumen</th>
                    <th>File</th>
                    <th style="text-align:center" width="90">Ukuran</th>
                    <th style="text-align:center" width="90">Status</th>
                    <th style="text-align:center" width="110">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gcg->documents as $doc)
                    @php $dtId = $doc->translations->firstWhere('locale','id'); @endphp
                    <tr>
                        <td style="color:var(--text3)">{{ $loop->iteration }}</td>
                        <td style="font-weight:600">{{ $dtId->title ?? '-' }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <span class="a-badge a-badge--gray" style="font-size:10px;font-weight:800">
                                    {{ strtoupper($doc->file_type) }}
                                </span>
                                <span style="color:var(--text3);font-size:12px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    {{ $doc->file_name }}
                                </span>
                            </div>
                        </td>
                        <td style="text-align:center;color:var(--text3);font-size:12px">
                            {{ $doc->file_size_human }}
                        </td>
                        <td style="text-align:center">
                            @if($doc->is_active)
                                <span class="a-badge a-badge--green">Aktif</span>
                            @else
                                <span class="a-badge a-badge--red">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center">
                                <button class="a-btn a-btn--secondary a-btn--sm"
                                        onclick="openEditModal({{ $doc->id }})">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.gcg.documents.destroy', [$gcg, $doc]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="a-btn a-btn--danger a-btn--sm">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="a-empty">
                                <div class="a-empty-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                </div>
                                <div class="a-empty-title">Belum ada dokumen</div>
                                <div class="a-empty-desc">Klik "Upload Dokumen" untuk menambahkan</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Data dokumen untuk JS --}}
@foreach($gcg->documents as $doc)
    @php $dtId = $doc->translations->firstWhere('locale','id'); @endphp
    <script>
        window.docData = window.docData || {};
        window.docData[{{ $doc->id }}] = {
            title:      "{{ addslashes($dtId->title ?? '') }}",
            file_name:  "{{ addslashes($doc->file_name) }}",
            is_active:  {{ $doc->is_active ? 'true' : 'false' }},
            update_url: "{{ route('admin.gcg.documents.update', [$gcg, $doc]) }}"
        };
    </script>
@endforeach

{{-- ═══ MODAL UPLOAD ═══ --}}
<div id="modalUpload" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,.45)">
    <div style="background:var(--white);border-radius:var(--r-lg);width:100%;max-width:460px;box-shadow:var(--shadow-md);overflow:hidden">
        <form action="{{ route('admin.gcg.documents.store', $gcg) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="padding:20px 24px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:16px;font-weight:700">Upload Dokumen</span>
                <button type="button" onclick="document.getElementById('modalUpload').style.display='none'"
                        style="border:none;background:none;cursor:pointer;color:var(--text3);font-size:22px;line-height:1">&times;</button>
            </div>
            <div style="padding:24px">
                <div class="a-form-group">
                    <label class="a-label">Judul Dokumen <span style="color:#dc2626">*</span></label>
                    <input type="text" name="title" class="a-input" required
                           placeholder="Contoh: Laporan Tahunan 2024">
                    <div style="margin-top:5px;font-size:12px;color:var(--text3)">
                        Judul EN akan otomatis diterjemahkan via DeepL
                    </div>
                </div>
                <div class="a-form-group">
                    <label class="a-label">File <span style="color:#dc2626">*</span></label>
                    <input type="file" name="file" class="a-input" required>
                    <div style="margin-top:5px;font-size:12px;color:var(--text3)">Maks. 20MB — PDF, Word, Excel, PPT, dll.</div>
                </div>
                <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           style="width:18px;height:18px;accent-color:var(--g500)">
                    <span class="a-label" style="margin:0">Tampilkan di website</span>
                </label>
            </div>
            <div style="padding:16px 24px;border-top:1px solid var(--line);display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="document.getElementById('modalUpload').style.display='none'"
                        class="a-btn a-btn--secondary">Batal</button>
                <button type="submit" class="a-btn a-btn--primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="16 16 12 12 8 16"/>
                        <line x1="12" y1="12" x2="12" y2="21"/>
                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                    </svg>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ MODAL EDIT DOKUMEN ═══ --}}
<div id="modalEditDoc" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,.45)">
    <div style="background:var(--white);border-radius:var(--r-lg);width:100%;max-width:460px;box-shadow:var(--shadow-md);overflow:hidden">
        <form id="formEditDoc" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div style="padding:20px 24px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:16px;font-weight:700">Edit Dokumen</span>
                <button type="button" onclick="document.getElementById('modalEditDoc').style.display='none'"
                        style="border:none;background:none;cursor:pointer;color:var(--text3);font-size:22px;line-height:1">&times;</button>
            </div>
            <div style="padding:24px">
                <div class="a-form-group">
                    <label class="a-label">Judul Dokumen <span style="color:#dc2626">*</span></label>
                    <input type="text" name="title" id="edit_title" class="a-input" required>
                    <div style="margin-top:5px;font-size:12px;color:var(--text3)">
                        Judul EN akan otomatis diperbarui via DeepL
                    </div>
                </div>
                <div class="a-form-group">
                    <label class="a-label">Ganti File <span class="a-label-hint">(opsional)</span></label>
                    <input type="file" name="file" class="a-input">
                    <div id="edit_file_name" style="margin-top:5px;font-size:12px;color:var(--text3)"></div>
                </div>
                <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                           style="width:18px;height:18px;accent-color:var(--g500)">
                    <span class="a-label" style="margin:0">Tampilkan di website</span>
                </label>
            </div>
            <div style="padding:16px 24px;border-top:1px solid var(--line);display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="document.getElementById('modalEditDoc').style.display='none'"
                        class="a-btn a-btn--secondary">Batal</button>
                <button type="submit" class="a-btn a-btn--primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
window.docData = window.docData || {};

function openEditModal(id) {
    var d = window.docData[id];
    if (!d) return;

    document.getElementById('edit_title').value     = d.title;
    document.getElementById('edit_is_active').checked = d.is_active;
    document.getElementById('edit_file_name').textContent = 'File saat ini: ' + d.file_name;
    document.getElementById('formEditDoc').action   = d.update_url;

    document.getElementById('modalEditDoc').style.display = 'flex';
}

// Tutup modal klik backdrop
['modalUpload', 'modalEditDoc'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>

@endsection