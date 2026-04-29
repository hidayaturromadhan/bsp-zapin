@extends('layouts.admin')

@section('title', 'Edit Halaman Profil')

@section('content')
@php
    $shareholderData = $shareholderData ?? [];
    $shareholderIntro = $shareholderData['intro'] ?? ['title' => 'Pemegang Saham', 'desc' => ''];
    $shareholderItems = $shareholderData['items'] ?? [
        ['percentage' => '', 'name' => '', 'desc' => '', 'logo' => null],
        ['percentage' => '', 'name' => '', 'desc' => '', 'logo' => null],
    ];

    $organizationData = $organizationData ?? [];
    $organizationIntro = $organizationData['intro'] ?? ['title' => 'Struktur Organisasi', 'desc' => ''];
    $directorItem = $organizationData['director'] ?? ['name' => '', 'position' => 'Direktur Utama', 'photo' => null];
    $commissionerItem = $organizationData['commissioner'] ?? ['name' => '', 'position' => 'Komisaris Utama', 'photo' => null];

    $hseData = $hseData ?? [];
    $hseIntro = $hseData['intro'] ?? ['title' => 'Health, Safety & Environment', 'desc' => ''];
    $hseCertification = $hseData['certification'] ?? [
        'title' => 'Bersertifikat Sistem Manajemen Terintegrasi',
        'subtitle' => 'Ruang Lingkup : Penyediaan Jasa Transportasi Minyak & Gas',
        'items' => [
            ['code' => 'ISO 9001:2015', 'title' => 'Quality Management System'],
            ['code' => 'ISO 14001:2015', 'title' => 'Environmental Management System'],
            ['code' => 'ISO 45001:2018', 'title' => 'Occupational Health & Safety Management System'],
        ],
    ];

    $templateLabel = match($templateType) {
        'about_us' => 'Tentang Kami Custom',
        'vision_mission' => 'Visi & Misi Custom',
        'history' => 'Sejarah Custom',
        'shareholder' => 'Pemegang Saham Custom',
        'organization_structure' => 'Struktur Organisasi Custom',
        'hse' => 'HSE Custom',
        default => 'Generic Profile',
    };
@endphp

<style>
    .ap-wrap { max-width: 1240px; }
    .ap-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .ap-title { margin:0; font-size:30px; font-weight:800; color:#111827; letter-spacing:-.03em; }
    .ap-subtitle { margin:6px 0 0; font-size:14px; color:#6b7280; line-height:1.7; }
    .ap-grid { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:20px; align-items:start; }
    .ap-card { background:#fff; border:1px solid #e5e7eb; border-radius:18px; box-shadow:0 10px 24px rgba(15,23,42,.04); overflow:hidden; }
    .ap-card-head { padding:18px 20px; border-bottom:1px solid #eef2f7; }
    .ap-card-title { margin:0; font-size:18px; font-weight:800; color:#111827; }
    .ap-card-desc { margin:6px 0 0; font-size:13px; color:#6b7280; line-height:1.7; }
    .ap-card-body { padding:20px; }
    .ap-form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
    .ap-field { margin-bottom:16px; }
    .ap-field.full { grid-column:1 / -1; }
    .ap-label { display:block; margin-bottom:8px; font-size:13px; font-weight:800; color:#111827; }
    .ap-input, .ap-textarea {
        width:100%;
        min-height:44px;
        border:1px solid #d1d5db;
        border-radius:12px;
        padding:0 12px;
        background:#fff;
        color:#111827;
        font:inherit;
        transition:border-color .18s ease, box-shadow .18s ease;
    }
    .ap-input:focus, .ap-textarea:focus {
        outline:none;
        border-color:#7aa46d;
        box-shadow:0 0 0 4px rgba(47,125,50,.08);
    }
    .ap-textarea { min-height:140px; resize:vertical; padding:12px; }
    .ap-textarea--sm { min-height:100px; }
    .ap-help { margin-top:6px; font-size:12px; color:#6b7280; line-height:1.6; }
    .ap-upload {
        width:100%;
        min-height:56px;
        display:flex;
        align-items:center;
        gap:10px;
        padding:8px;
        border:1px solid #d1d5db;
        border-radius:12px;
        background:#fff;
    }
    .ap-upload input[type=file] { display:none; }
    .ap-upload-btn {
        min-height:38px;
        padding:0 14px;
        border-radius:10px;
        background:#173f08;
        color:#fff;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        font-size:13px;
        font-weight:700;
        cursor:pointer;
        white-space:nowrap;
        text-decoration:none;
    }
    .ap-upload-name {
        min-width:0;
        font-size:13px;
        color:#6b7280;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }
    .ap-preview {
        margin-top:12px;
        padding:12px;
        border-radius:14px;
        border:1px solid #e5e7eb;
        background:#f8fafc;
    }
    .ap-preview-label {
        font-size:12px;
        font-weight:800;
        color:#6b7280;
        text-transform:uppercase;
        letter-spacing:.04em;
        margin-bottom:8px;
    }
    .ap-preview img {
        width:100%;
        max-width:260px;
        height:260px;
        border-radius:14px;
        display:block;
        object-fit:cover;
    }
    .ap-actions {
        display:flex;
        justify-content:flex-end;
        gap:10px;
        margin-top:20px;
        flex-wrap:wrap;
    }
    .ap-btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:42px;
        padding:0 16px;
        border-radius:12px;
        text-decoration:none;
        font-size:14px;
        font-weight:700;
        border:1px solid #d1d5db;
        background:#fff;
        color:#111827;
        cursor:pointer;
    }
    .ap-btn--primary {
        background:#173f08;
        color:#fff;
        border-color:#173f08;
    }
    .ap-side-list { display:grid; gap:12px; }
    .ap-side-item { padding:12px 0; border-bottom:1px solid #eef2f7; }
    .ap-side-item:last-child { border-bottom:0; }
    .ap-side-label { font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.04em; color:#6b7280; margin-bottom:4px; }
    .ap-side-value { font-size:14px; color:#111827; line-height:1.6; }
    .ap-box {
        padding:16px;
        border:1px solid #e5e7eb;
        border-radius:18px;
        background:linear-gradient(180deg,#ffffff 0%,#f8fafc 100%);
        box-shadow:0 10px 24px rgba(15,23,42,.04);
        height:100%;
    }
    .ap-box-title {
        font-size:12px;
        font-weight:800;
        color:#173f08;
        margin-bottom:12px;
        text-transform:uppercase;
        letter-spacing:.05em;
    }
    .ap-divider {
        height:1px;
        background:#eef2f7;
        margin:10px 0 18px;
    }
    .ap-org-grid {
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:16px;
    }

    @media (max-width:1024px) {
        .ap-grid { grid-template-columns:1fr; }
    }

    @media (max-width:768px) {
        .ap-form-grid { grid-template-columns:1fr; }
        .ap-org-grid { grid-template-columns:1fr; }
    }
</style>

<div class="ap-wrap">
    <div class="ap-head">
        <div>
            <h1 class="ap-title">Edit Halaman Profil</h1>
            <p class="ap-subtitle">Editor custom halaman profil. Struktur lama tetap aman, hanya menambah template yang dibutuhkan.</p>
        </div>

        <a href="{{ route('admin.profile-pages.index') }}" class="ap-btn">Kembali</a>
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#eef8ee;color:#17603a;border:1px solid #cfe9d3;font-size:14px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#fff4f4;color:#b42318;border:1px solid #f3c6c6;font-size:14px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li style="margin:4px 0;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile-pages.update', $page->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="ap-grid">
            <div>
                <div class="ap-card">
                    <div class="ap-card-head">
                        <h2 class="ap-card-title">Konten Halaman</h2>
                        <p class="ap-card-desc">Template aktif: {{ $templateLabel }}</p>
                    </div>

                    <div class="ap-card-body">
                        <div class="ap-form-grid">
                            <div class="ap-field full">
                                <label class="ap-label">Judul (ID)</label>
                                <input type="text" name="id_title" class="ap-input" value="{{ old('id_title', $tId->title) }}" required>
                            </div>

                            <div class="ap-field full">
                                <label class="ap-label">Slug (ID)</label>
                                <input type="text" name="id_slug" class="ap-input" value="{{ old('id_slug', $tId->slug) }}" required>
                                <div class="ap-help">
                                    Gunakan slug:
                                    <b>tentang-kami</b>,
                                    <b>visi-misi</b>,
                                    <b>sejarah</b>,
                                    <b>pemegang-saham</b>,
                                    <b>struktur-organisasi</b>,
                                    atau <b>health-safety-environment</b>.
                                </div>
                            </div>

                            <div class="ap-field full">
                                <label class="ap-label">Cover Utama / Background</label>
                                <div class="ap-upload">
                                    <label for="cover_image" class="ap-upload-btn">Pilih Gambar</label>
                                    <div class="ap-upload-name" id="coverImageName">Belum ada file dipilih</div>
                                    <input type="file" name="cover_image" id="cover_image" accept=".jpg,.jpeg,.png,.webp">
                                </div>

                                @if($page->cover_image)
                                    <div class="ap-preview">
                                        <div class="ap-preview-label">Preview Cover</div>
                                        <img src="{{ asset($page->cover_image) }}" alt="Cover">
                                    </div>
                                @endif
                            </div>

                            @if($templateType === 'hse')
                                <div class="ap-field full">
                                    <label class="ap-label">Judul Intro</label>
                                    <input
                                        type="text"
                                        name="hse_intro_title"
                                        class="ap-input"
                                        value="{{ old('hse_intro_title', $hseIntro['title'] ?? 'Health, Safety & Environment') }}"
                                        required
                                    >
                                </div>

                                <div class="ap-field full">
                                    <label class="ap-label">Deskripsi Intro</label>
                                    <textarea
                                        name="hse_intro_desc"
                                        class="ap-textarea ap-textarea--sm"
                                    >{{ old('hse_intro_desc', $hseIntro['desc'] ?? '') }}</textarea>
                                </div>

                                <div class="ap-field full">
                                    <div class="ap-divider"></div>
                                    <label class="ap-label">Kebijakan HSE / K3LL</label>
                                </div>

                                <div class="ap-field full">
                                    <label class="ap-label">Judul Gambar Kebijakan</label>
                                    <input
                                        type="text"
                                        name="hse_policy_title"
                                        class="ap-input"
                                        value="{{ old('hse_policy_title', $hseData['policy_title'] ?? 'Kebijakan K3LL') }}"
                                        required
                                    >
                                </div>

                                <div class="ap-field full">
                                    <label class="ap-label">Upload Gambar Kebijakan HSE</label>
                                    <div class="ap-upload">
                                        <label for="hse_policy_image" class="ap-upload-btn">Pilih Gambar</label>
                                        <div class="ap-upload-name" id="hsePolicyImageName">Belum ada file dipilih</div>
                                        <input type="file" name="hse_policy_image" id="hse_policy_image" accept=".jpg,.jpeg,.png,.webp">
                                    </div>

                                    @if(!empty($hseData['policy_image']))
                                        <div class="ap-preview">
                                            <div class="ap-preview-label">Preview Kebijakan HSE</div>
                                            <img src="{{ asset($hseData['policy_image']) }}" alt="Kebijakan HSE" style="max-width:420px;width:100%;height:auto;object-fit:contain;">
                                        </div>
                                    @endif
                                </div>

                                <div class="ap-field full">
                                    <div class="ap-divider"></div>
                                    <label class="ap-label">Sertifikasi</label>
                                    <div class="ap-help">Bagian sertifikasi hanya menampilkan title seperti contoh. Tidak ada upload dokumen sertifikat.</div>
                                </div>

                                <div class="ap-field full">
                                    <label class="ap-label">Judul Sertifikasi</label>
                                    <input
                                        type="text"
                                        name="hse_certification_title"
                                        class="ap-input"
                                        value="{{ old('hse_certification_title', $hseCertification['title'] ?? '') }}"
                                        required
                                    >
                                </div>

                                <div class="ap-field full">
                                    <label class="ap-label">Subjudul Sertifikasi</label>
                                    <input
                                        type="text"
                                        name="hse_certification_subtitle"
                                        class="ap-input"
                                        value="{{ old('hse_certification_subtitle', $hseCertification['subtitle'] ?? '') }}"
                                    >
                                </div>

                                <div class="ap-field full">
                                    <div class="ap-org-grid">
                                        @for($i = 1; $i <= 3; $i++)
                                            @php
                                                $certItem = $hseCertification['items'][$i - 1] ?? ['code' => '', 'title' => ''];
                                            @endphp
                                            <div class="ap-box">
                                                <div class="ap-box-title">Sertifikasi {{ $i }}</div>

                                                <label class="ap-label">Kode / Title Utama</label>
                                                <input
                                                    type="text"
                                                    name="hse_cert_{{ $i }}_code"
                                                    class="ap-input"
                                                    value="{{ old('hse_cert_'.$i.'_code', $certItem['code'] ?? '') }}"
                                                    required
                                                >

                                                <label class="ap-label" style="margin-top:12px;">Deskripsi Singkat</label>
                                                <input
                                                    type="text"
                                                    name="hse_cert_{{ $i }}_title"
                                                    class="ap-input"
                                                    value="{{ old('hse_cert_'.$i.'_title', $certItem['title'] ?? '') }}"
                                                    required
                                                >
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                            @elseif($templateType === 'organization_structure')
                                <div class="ap-field full">
                                    <label class="ap-label">Judul Intro</label>
                                    <input
                                        type="text"
                                        name="organization_intro_title"
                                        class="ap-input"
                                        value="{{ old('organization_intro_title', $organizationIntro['title'] ?? 'Struktur Organisasi') }}"
                                        required
                                    >
                                </div>

                                <div class="ap-field full">
                                    <label class="ap-label">Deskripsi Intro</label>
                                    <textarea
                                        name="organization_intro_desc"
                                        class="ap-textarea ap-textarea--sm"
                                    >{{ old('organization_intro_desc', $organizationIntro['desc'] ?? '') }}</textarea>
                                </div>

                                <div class="ap-field full">
                                    <div class="ap-divider"></div>
                                    <label class="ap-label">Data Struktur Organisasi</label>
                                    <div class="ap-help">Direktur dan komisaris hanya 1 data, dan form dibuat sejajar agar lebih rapi serta sinkron dengan tampilan web.</div>
                                </div>

                                <div class="ap-field full">
                                    <div class="ap-org-grid">
                                        <div class="ap-box">
                                            <div class="ap-box-title">Direktur</div>

                                            <label class="ap-label">Nama Direktur</label>
                                            <input
                                                type="text"
                                                name="director_name"
                                                class="ap-input"
                                                value="{{ old('director_name', $directorItem['name'] ?? '') }}"
                                            >

                                            <label class="ap-label" style="margin-top:12px;">Jabatan</label>
                                            <input
                                                type="text"
                                                name="director_position"
                                                class="ap-input"
                                                value="{{ old('director_position', $directorItem['position'] ?? 'Direktur Utama') }}"
                                            >

                                            <label class="ap-label" style="margin-top:12px;">Foto Direktur</label>
                                            <div class="ap-upload">
                                                <label for="director_photo" class="ap-upload-btn">Pilih Gambar</label>
                                                <div class="ap-upload-name" id="directorPhotoName">Belum ada file dipilih</div>
                                                <input type="file" name="director_photo" id="director_photo" accept=".jpg,.jpeg,.png,.webp">
                                            </div>

                                            @if(!empty($directorItem['photo']))
                                                <div class="ap-preview">
                                                    <div class="ap-preview-label">Preview Foto Direktur</div>
                                                    <img src="{{ asset($directorItem['photo']) }}" alt="Direktur">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ap-box">
                                            <div class="ap-box-title">Komisaris</div>

                                            <label class="ap-label">Nama Komisaris</label>
                                            <input
                                                type="text"
                                                name="commissioner_name"
                                                class="ap-input"
                                                value="{{ old('commissioner_name', $commissionerItem['name'] ?? '') }}"
                                            >

                                            <label class="ap-label" style="margin-top:12px;">Jabatan</label>
                                            <input
                                                type="text"
                                                name="commissioner_position"
                                                class="ap-input"
                                                value="{{ old('commissioner_position', $commissionerItem['position'] ?? 'Komisaris Utama') }}"
                                            >

                                            <label class="ap-label" style="margin-top:12px;">Foto Komisaris</label>
                                            <div class="ap-upload">
                                                <label for="commissioner_photo" class="ap-upload-btn">Pilih Gambar</label>
                                                <div class="ap-upload-name" id="commissionerPhotoName">Belum ada file dipilih</div>
                                                <input type="file" name="commissioner_photo" id="commissioner_photo" accept=".jpg,.jpeg,.png,.webp">
                                            </div>

                                            @if(!empty($commissionerItem['photo']))
                                                <div class="ap-preview">
                                                    <div class="ap-preview-label">Preview Foto Komisaris</div>
                                                    <img src="{{ asset($commissionerItem['photo']) }}" alt="Komisaris">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($templateType === 'shareholder')
                                <div class="ap-field full">
                                    <label class="ap-label">Judul Intro</label>
                                    <input
                                        type="text"
                                        name="shareholder_intro_title"
                                        class="ap-input"
                                        value="{{ old('shareholder_intro_title', $shareholderIntro['title'] ?? 'Pemegang Saham') }}"
                                        required
                                    >
                                </div>

                                <div class="ap-field full">
                                    <label class="ap-label">Deskripsi Intro</label>
                                    <textarea
                                        name="shareholder_intro_desc"
                                        class="ap-textarea ap-textarea--sm"
                                    >{{ old('shareholder_intro_desc', $shareholderIntro['desc'] ?? '') }}</textarea>
                                </div>

                                <div class="ap-field full">
                                    <label class="ap-label">Gambar Komposisi / Diagram</label>
                                    <div class="ap-upload">
                                        <label for="shareholder_chart_image" class="ap-upload-btn">Pilih Gambar</label>
                                        <div class="ap-upload-name" id="shareholderChartName">Belum ada file dipilih</div>
                                        <input type="file" name="shareholder_chart_image" id="shareholder_chart_image" accept=".jpg,.jpeg,.png,.webp">
                                    </div>

                                    @if(!empty($shareholderData['chart_image']))
                                        <div class="ap-preview">
                                            <div class="ap-preview-label">Preview Diagram</div>
                                            <img src="{{ asset($shareholderData['chart_image']) }}" alt="Diagram">
                                        </div>
                                    @endif
                                </div>

                                @for($i = 1; $i <= 2; $i++)
                                    <div class="ap-field full">
                                        <label class="ap-label">Pemegang Saham {{ $i }}</label>
                                        <div class="ap-box" style="margin-bottom:0;">
                                            <div class="ap-box-title">Data Pemegang Saham {{ $i }}</div>

                                            <label class="ap-label">Persentase</label>
                                            <input
                                                type="text"
                                                name="shareholder_{{ $i }}_percentage"
                                                class="ap-input"
                                                value="{{ old('shareholder_'.$i.'_percentage', $shareholderItems[$i - 1]['percentage'] ?? '') }}"
                                                required
                                            >

                                            <label class="ap-label" style="margin-top:12px;">Nama</label>
                                            <input
                                                type="text"
                                                name="shareholder_{{ $i }}_name"
                                                class="ap-input"
                                                value="{{ old('shareholder_'.$i.'_name', $shareholderItems[$i - 1]['name'] ?? '') }}"
                                                required
                                            >

                                            <label class="ap-label" style="margin-top:12px;">Deskripsi Kecil</label>
                                            <input
                                                type="text"
                                                name="shareholder_{{ $i }}_desc"
                                                class="ap-input"
                                                value="{{ old('shareholder_'.$i.'_desc', $shareholderItems[$i - 1]['desc'] ?? '') }}"
                                            >

                                            <label class="ap-label" style="margin-top:12px;">Logo</label>
                                            <div class="ap-upload">
                                                <label for="shareholder_{{ $i }}_logo" class="ap-upload-btn">Pilih Gambar</label>
                                                <div class="ap-upload-name" id="shareholder{{ $i }}LogoName">Belum ada file dipilih</div>
                                                <input type="file" name="shareholder_{{ $i }}_logo" id="shareholder_{{ $i }}_logo" accept=".jpg,.jpeg,.png,.webp">
                                            </div>

                                            @if(!empty($shareholderItems[$i - 1]['logo']))
                                                <div class="ap-preview">
                                                    <div class="ap-preview-label">Preview Logo {{ $i }}</div>
                                                    <img src="{{ asset($shareholderItems[$i - 1]['logo']) }}" alt="Logo {{ $i }}">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endfor
                            @else
                                <div class="ap-field full">
                                    <label class="ap-label">Catatan</label>
                                    <div class="ap-help">
                                        File ini sudah aman untuk template baru <b>struktur-organisasi</b> dan <b>health-safety-environment</b>.
                                        Template lain tetap bisa memakai editor lama yang sudah kamu miliki.
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="ap-actions">
                            <label style="display:inline-flex;align-items:center;gap:10px;margin-right:auto;font-size:14px;color:#374151;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                Halaman aktif
                            </label>

                            <button type="submit" class="ap-btn ap-btn--primary">Simpan Halaman</button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="ap-card">
                    <div class="ap-card-head">
                        <h2 class="ap-card-title">Ringkasan</h2>
                        <p class="ap-card-desc">Status dan info halaman profile.</p>
                    </div>

                    <div class="ap-card-body">
                        <div class="ap-side-list">
                            <div class="ap-side-item">
                                <div class="ap-side-label">Menu Group</div>
                                <div class="ap-side-value">{{ $page->menu_group }}</div>
                            </div>

                            <div class="ap-side-item">
                                <div class="ap-side-label">Template</div>
                                <div class="ap-side-value">{{ $templateLabel }}</div>
                            </div>

                            <div class="ap-side-item">
                                <div class="ap-side-label">Slug EN</div>
                                <div class="ap-side-value">{{ $tEn->slug ?: '-' }}</div>
                            </div>

                            <div class="ap-side-item">
                                <div class="ap-side-label">Status</div>
                                <div class="ap-side-value">{{ $page->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    function bindName(inputId, outputId) {
        const input = document.getElementById(inputId);
        const output = document.getElementById(outputId);
        if (!input || !output) return;

        input.addEventListener('change', function () {
            output.textContent = this.files && this.files[0]
                ? this.files[0].name
                : 'Belum ada file dipilih';
        });
    }

    bindName('cover_image', 'coverImageName');
    bindName('shareholder_chart_image', 'shareholderChartName');
    bindName('hse_policy_image', 'hsePolicyImageName');
    bindName('director_photo', 'directorPhotoName');
    bindName('commissioner_photo', 'commissionerPhotoName');

    for (let i = 1; i <= 2; i++) {
        bindName(`shareholder_${i}_logo`, `shareholder${i}LogoName`);
    }
})();
</script>
@endsection
