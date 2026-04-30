@extends('layouts.wbs')

@section('content')
    <style>
        .report-detail-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .report-detail-title {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .report-detail-subtitle {
            margin-top: 8px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
        }

        .report-detail-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(360px, .75fr);
            gap: 22px;
            align-items: start;
        }

        .report-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
            padding: 24px;
        }

        .report-section + .report-section {
            margin-top: 22px;
        }

        .report-section-title {
            margin: 0 0 18px;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .report-info-list {
            display: grid;
            gap: 14px;
        }

        .report-info-item {
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #f8fafc;
        }

        .report-info-label {
            margin-bottom: 6px;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .report-info-value {
            color: #0f172a;
            font-size: 15px;
            line-height: 1.75;
            word-break: break-word;
        }

        .report-info-value.strong {
            font-size: 17px;
            font-weight: 800;
        }

        .report-status-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .report-text-box {
            padding: 16px 18px;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #1e293b;
            font-size: 15px;
            line-height: 1.85;
            white-space: pre-line;
        }

        .report-muted {
            color: #64748b;
        }

        .report-lock-note {
            padding: 14px 16px;
            border-radius: 18px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            font-size: 14px;
            line-height: 1.8;
            font-weight: 600;
        }

        .report-timeline {
            display: grid;
            gap: 14px;
            position: relative;
        }

        .report-timeline-item {
            display: grid;
            grid-template-columns: 34px 1fr;
            gap: 12px;
        }

        .report-timeline-dot {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
        }

        .report-timeline-content {
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #f8fafc;
        }

        .report-timeline-title {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .report-timeline-date {
            color: #64748b;
            font-size: 13px;
        }

        .report-attachment-list {
            display: grid;
            gap: 12px;
        }

        .report-attachment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #f8fafc;
        }

        .report-attachment-name {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            word-break: break-word;
        }

        .report-attachment-meta {
            font-size: 13px;
            color: #64748b;
        }

        .report-empty {
            padding: 16px 18px;
            border-radius: 18px;
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            color: #64748b;
            line-height: 1.75;
        }

        .report-friendly-note {
            padding: 16px 18px;
            border-radius: 18px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            line-height: 1.8;
            font-size: 14px;
        }

        @media (max-width: 1100px) {
            .report-detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .report-section {
                padding: 18px;
                border-radius: 20px;
            }

            .report-detail-title {
                font-size: 24px;
            }

            .report-attachment-item {
                align-items: flex-start;
                flex-direction: column;
            }

            .report-attachment-item .wbs-btn {
                width: 100%;
            }
        }
    </style>

    <div class="report-detail-header">
        <div>
            <h2 class="report-detail-title">Detail Laporan Saya</h2>
            <div class="report-detail-subtitle">
                Informasi berikut menampilkan ringkasan laporan, status penanganan, lampiran, dan catatan tindak lanjut dari admin WBS.
            </div>
        </div>

        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('wbs.pelapor.reports.index') }}" class="wbs-btn wbs-btn-light">Kembali</a>

            @if($report->canBeEditedByPelapor())
                <a href="{{ route('wbs.pelapor.reports.edit', $report->id) }}" class="wbs-btn wbs-btn-primary">Edit Laporan</a>
            @endif
        </div>
    </div>

    @if(! $report->canBeEditedByPelapor())
        <div class="report-lock-note" style="margin-bottom:22px;">
            Laporan ini sudah mulai ditangani oleh admin WBS, sehingga data laporan tidak dapat diubah untuk menjaga keakuratan proses tindak lanjut.
        </div>
    @endif

    <div class="report-detail-grid">
        <div>
            <div class="report-section">
                <h3 class="report-section-title">Ringkasan Laporan</h3>

                <div class="report-info-list">
                    <div class="report-info-item">
                        <div class="report-info-label">Nomor Laporan</div>
                        <div class="report-info-value strong">{{ $report->report_number }}</div>
                    </div>

                    <div class="report-info-item">
                        <div class="report-info-label">Status Saat Ini</div>
                        <div class="report-status-row">
                            <span class="wbs-badge">{{ $report->status_label }}</span>
                            <span class="report-muted">{{ optional($report->updated_at)->format('d-m-Y H:i') ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="report-info-item">
                        <div class="report-info-label">Kategori</div>
                        <div class="report-info-value">{{ $report->category_label }}</div>
                    </div>

                    <div class="report-info-item">
                        <div class="report-info-label">Judul Laporan</div>
                        <div class="report-info-value strong">{{ $report->title }}</div>
                    </div>

                    <div class="report-info-item">
                        <div class="report-info-label">Tanggal Kejadian</div>
                        <div class="report-info-value">{{ optional($report->incident_date)->format('d-m-Y') ?? '-' }}</div>
                    </div>

                    <div class="report-info-item">
                        <div class="report-info-label">Lokasi Kejadian</div>
                        <div class="report-info-value">{{ $report->location ?: '-' }}</div>
                    </div>

                    <div class="report-info-item">
                        <div class="report-info-label">Estimasi Kerugian</div>
                        <div class="report-info-value">{{ $report->estimated_loss ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <h3 class="report-section-title">Isi Laporan</h3>

                <div class="report-info-list">
                    <div>
                        <div class="report-info-label">Pokok Masalah</div>
                        <div class="report-text-box">{{ $report->description ?: '-' }}</div>
                    </div>

                    <div>
                        <div class="report-info-label">Pihak yang Terlibat</div>
                        <div class="report-text-box">{{ $report->involved_parties ?: '-' }}</div>
                    </div>

                    <div>
                        <div class="report-info-label">Kronologi</div>
                        <div class="report-text-box">{{ $report->chronology ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <h3 class="report-section-title">Informasi Tambahan</h3>

                <div class="report-info-list">
                    <div class="report-info-item">
                        <div class="report-info-label">Apakah ada bukti?</div>
                        <div class="report-info-value">{{ $report->has_evidence ? 'Ya, pelapor menyatakan memiliki bukti.' : 'Tidak / belum ada bukti.' }}</div>
                    </div>

                    <div class="report-info-item">
                        <div class="report-info-label">Pernah dilaporkan sebelumnya?</div>
                        <div class="report-info-value">{{ $report->reported_before ? 'Ya, laporan pernah disampaikan sebelumnya.' : 'Tidak.' }}</div>
                    </div>

                    <div class="report-info-item">
                        <div class="report-info-label">Dilaporkan ke pihak lain?</div>
                        <div class="report-info-value">{{ $report->reported_to_other_party ? 'Ya, laporan juga disampaikan ke pihak lain.' : 'Tidak.' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="report-section">
                <h3 class="report-section-title">Status Penanganan</h3>

                <div class="report-friendly-note" style="margin-bottom:16px;">
                    Laporan Anda telah tercatat di sistem WBS. Perubahan status dan catatan tindak lanjut akan tampil di halaman ini.
                </div>

                <div class="report-timeline">
                    <div class="report-timeline-item">
                        <div class="report-timeline-dot">1</div>
                        <div class="report-timeline-content">
                            <div class="report-timeline-title">Laporan Dikirim</div>
                            <div class="report-timeline-date">
                                {{ optional($report->submitted_at)->format('d-m-Y H:i') ?? optional($report->created_at)->format('d-m-Y H:i') ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="report-timeline-item">
                        <div class="report-timeline-dot">2</div>
                        <div class="report-timeline-content">
                            <div class="report-timeline-title">Diproses Admin</div>
                            <div class="report-timeline-date">
                                {{ optional($report->processed_at)->format('d-m-Y H:i') ?? 'Belum ada waktu proses.' }}
                            </div>
                        </div>
                    </div>

                    <div class="report-timeline-item">
                        <div class="report-timeline-dot">3</div>
                        <div class="report-timeline-content">
                            <div class="report-timeline-title">Status Akhir</div>
                            <div class="report-timeline-date">
                                {{ optional($report->closed_at)->format('d-m-Y H:i') ?? 'Belum ditutup.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <h3 class="report-section-title">Tindak Lanjut</h3>

                <div class="report-info-list">
                    <div>
                        <div class="report-info-label">Catatan Admin</div>
                        @if($report->admin_notes)
                            <div class="report-text-box">{{ $report->admin_notes }}</div>
                        @else
                            <div class="report-empty">Belum ada catatan dari admin.</div>
                        @endif
                    </div>

                    <div>
                        <div class="report-info-label">Hasil Tindak Lanjut</div>
                        @if($report->follow_up_result)
                            <div class="report-text-box">{{ $report->follow_up_result }}</div>
                        @else
                            <div class="report-empty">Belum ada hasil tindak lanjut yang dapat ditampilkan.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="report-section">
                <h3 class="report-section-title">Lampiran</h3>

                @if($report->attachments->count())
                    <div class="report-attachment-list">
                        @foreach($report->attachments as $attachment)
                            <div class="report-attachment-item">
                                <div>
                                    <div class="report-attachment-name">{{ $attachment->original_name }}</div>
                                    <div class="report-attachment-meta">
                                        {{ $attachment->mime_type ?: '-' }} | {{ $attachment->file_size_label }}
                                    </div>
                                </div>

                                <a href="{{ $attachment->file_url }}" target="_blank" class="wbs-btn wbs-btn-light">Lihat File</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="report-empty">Belum ada lampiran pada laporan ini.</div>
                @endif
            </div>
        </div>
    </div>
@endsection