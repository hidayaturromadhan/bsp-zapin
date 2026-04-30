@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Detail Laporan WBS</h2>

    <div class="wbs-toolbar">
        <div class="wbs-toolbar-left">
            <a href="{{ route('wbs.admin.reports.index') }}" class="wbs-btn wbs-btn-light">Kembali</a>
        </div>

        <div class="wbs-toolbar-right">
            <a href="{{ route('wbs.admin.reports.edit', $report->id) }}" class="wbs-btn wbs-btn-primary">Update Status</a>
            <a href="{{ route('wbs.admin.reports.export-pdf', $report->id) }}" class="wbs-btn wbs-btn-light">Export PDF</a>

            @if($report->pdf_url)
                <a href="{{ $report->pdf_url }}" target="_blank" class="wbs-btn wbs-btn-light">Lihat PDF</a>
            @endif

        </div>
    </div>

    <div class="wbs-grid wbs-grid-2">
        <div class="wbs-card">
            <h3 class="wbs-card-title">Informasi Laporan</h3>

            <div class="wbs-meta-grid">
                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">No. Laporan</div>
                    <div class="wbs-meta-item-value">{{ $report->report_number }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Pelapor</div>
                    <div class="wbs-meta-item-value">
                        {{ $report->user->name ?? '-' }}<br>
                        <span style="color:#64748b;">{{ $report->user->email ?? '-' }}</span>
                    </div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Kategori</div>
                    <div class="wbs-meta-item-value">{{ $report->category_label }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Judul</div>
                    <div class="wbs-meta-item-value">{{ $report->title }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Status</div>
                    <div class="wbs-meta-item-value"><span class="wbs-badge">{{ $report->status_label }}</span></div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Lokasi</div>
                    <div class="wbs-meta-item-value">{{ $report->location ?: '-' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Tanggal Kejadian</div>
                    <div class="wbs-meta-item-value">{{ optional($report->incident_date)->format('d-m-Y') ?? '-' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Estimasi Kerugian</div>
                    <div class="wbs-meta-item-value">{{ $report->estimated_loss ?: '-' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Dikirim</div>
                    <div class="wbs-meta-item-value">{{ optional($report->submitted_at)->format('d-m-Y H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="wbs-card">
            <h3 class="wbs-card-title">Isi Laporan</h3>

            <div class="wbs-meta-grid">
                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Pokok Masalah</div>
                    <div class="wbs-meta-item-value">{{ $report->description }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Pihak yang Terlibat</div>
                    <div class="wbs-meta-item-value">{{ $report->involved_parties ?: '-' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Kronologi</div>
                    <div class="wbs-meta-item-value">{{ $report->chronology ?: '-' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Ada Bukti</div>
                    <div class="wbs-meta-item-value">{{ $report->has_evidence ? 'Ya' : 'Tidak' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Pernah Dilaporkan</div>
                    <div class="wbs-meta-item-value">{{ $report->reported_before ? 'Ya' : 'Tidak' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Dilaporkan ke Pihak Lain</div>
                    <div class="wbs-meta-item-value">{{ $report->reported_to_other_party ? 'Ya' : 'Tidak' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="wbs-grid wbs-grid-2" style="margin-top:22px;">
        <div class="wbs-card">
            <h3 class="wbs-card-title">Tindak Lanjut Admin</h3>

            <div class="wbs-meta-grid">
                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Catatan Admin</div>
                    <div class="wbs-meta-item-value">{{ $report->admin_notes ?: '-' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Hasil Tindak Lanjut</div>
                    <div class="wbs-meta-item-value">{{ $report->follow_up_result ?: '-' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Diproses</div>
                    <div class="wbs-meta-item-value">{{ optional($report->processed_at)->format('d-m-Y H:i') ?? '-' }}</div>
                </div>

                <div class="wbs-meta-item">
                    <div class="wbs-meta-item-label">Ditutup</div>
                    <div class="wbs-meta-item-value">{{ optional($report->closed_at)->format('d-m-Y H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="wbs-card">
            <h3 class="wbs-card-title">Lampiran</h3>

            @if($report->attachments->count())
                <div class="wbs-attachment-list">
                    @foreach($report->attachments as $attachment)
                        <div class="wbs-attachment-item">
                            <div>
                                <div class="wbs-attachment-title">{{ $attachment->original_name }}</div>
                                <div class="wbs-attachment-meta">
                                    {{ $attachment->mime_type ?: '-' }} | {{ $attachment->file_size_label }}
                                </div>
                            </div>

                            <a href="{{ $attachment->file_url }}" target="_blank" class="wbs-btn wbs-btn-light">Lihat File</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="wbs-empty">Belum ada lampiran.</div>
            @endif
        </div>
    </div>
@endsection