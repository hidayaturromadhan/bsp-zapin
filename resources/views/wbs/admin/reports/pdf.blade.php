<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan WBS {{ $report->report_number }}</title>
    <style>
        /* ─── Base ─── */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1a2332;
            line-height: 1.65;
            background: #ffffff;
        }

        /* ─── Page wrapper ─── */
        .page {
            padding: 32px 36px 36px;
        }

        /* ─── HEADER / LETTERHEAD ─── */
        .letterhead {
            width: 100%;
            border-bottom: 3px solid #1a3a6b;
            padding-bottom: 14px;
            margin-bottom: 6px;
        }

        .letterhead-inner {
            display: table;
            width: 100%;
        }

        .letterhead-logo {
            display: table-cell;
            vertical-align: middle;
            width: 68px;
        }

        .letterhead-logo img {
            width: 60px;
            height: auto;
        }

        .letterhead-company {
            display: table-cell;
            vertical-align: middle;
            padding-left: 14px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1a3a6b;
            letter-spacing: 0.02em;
            line-height: 1.2;
        }

        .company-sub {
            font-size: 10px;
            color: #4b6080;
            margin-top: 3px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .letterhead-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 160px;
        }

        .doc-label {
            font-size: 9px;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .doc-number {
            font-size: 13px;
            font-weight: bold;
            color: #1a3a6b;
            margin-top: 3px;
        }

        /* Thin accent line below header */
        .header-accent {
            height: 1px;
            background: #d6e4f0;
            margin-bottom: 22px;
        }

        /* ─── Document Title Block ─── */
        .doc-title-block {
            text-align: center;
            margin-bottom: 22px;
            padding: 14px 20px;
            background: #f0f5fb;
            border: 1px solid #ccd9ea;
            border-radius: 4px;
        }

        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #1a3a6b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .doc-title-sub {
            font-size: 10px;
            color: #5a7899;
            margin-top: 4px;
        }

        /* ─── Confidential Badge ─── */
        .confidential-badge {
            display: inline-block;
            background: #fff3cd;
            border: 1px solid #f0c040;
            color: #7a5400;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 3px;
            margin-top: 6px;
        }

        /* ─── Section ─── */
        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #ffffff;
            background: #1a3a6b;
            padding: 7px 12px;
            margin-bottom: 0;
            border-radius: 3px 3px 0 0;
        }

        /* ─── Info Table ─── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #c8d6e5;
            border-top: none;
            border-radius: 0 0 3px 3px;
            overflow: hidden;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .info-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #dde8f3;
            vertical-align: top;
            font-size: 11px;
        }

        .info-table tr:nth-child(even) td {
            background: #f7fafd;
        }

        .info-table tr:nth-child(odd) td {
            background: #ffffff;
        }

        .col-label {
            width: 32%;
            font-weight: bold;
            color: #2c4a6e;
            font-size: 10.5px;
        }

        .col-sep {
            width: 3%;
            color: #8aa5c0;
            text-align: center;
        }

        .col-value {
            width: 65%;
            color: #1a2332;
        }

        /* ─── Status badges ─── */
        .status-pill {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.04em;
        }

        .status-new      { background: #e0f0ff; color: #1a5fa8; border: 1px solid #a0c8f0; }
        .status-process  { background: #fff7e0; color: #8a5a00; border: 1px solid #f0d080; }
        .status-done     { background: #e0f8ec; color: #1a6b40; border: 1px solid #80d8a8; }
        .status-reject   { background: #ffe8e8; color: #8a1a1a; border: 1px solid #f0a0a0; }
        .status-default  { background: #f0f0f0; color: #505050; border: 1px solid #c8c8c8; }

        /* ─── Masked / Sensitive value ─── */
        .masked {
            color: #8a9ab0;
            font-style: italic;
        }

        /* ─── Bool indicator ─── */
        .bool-yes { color: #1a6b40; font-weight: bold; }
        .bool-no  { color: #8a1a1a; }

        /* ─── Attachment list ─── */
        .attachment-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .attachment-list li {
            padding: 7px 12px;
            border-bottom: 1px solid #dde8f3;
            font-size: 11px;
            color: #1a2332;
            display: table;
            width: 100%;
        }

        .attachment-list li:last-child { border-bottom: none; }
        .attachment-list li:nth-child(even) { background: #f7fafd; }

        .att-icon {
            display: table-cell;
            width: 18px;
            color: #4a7ab5;
            font-weight: bold;
        }

        .att-name {
            display: table-cell;
            font-weight: bold;
            color: #1a3a6b;
        }

        .att-size {
            display: table-cell;
            text-align: right;
            color: #7a8fa6;
            font-size: 10px;
            width: 80px;
        }

        .att-empty {
            padding: 10px 12px;
            color: #8a9ab0;
            font-style: italic;
            font-size: 11px;
        }

        /* ─── Footer ─── */
        .footer {
            margin-top: 28px;
            border-top: 2px solid #1a3a6b;
            padding-top: 10px;
        }

        .footer-inner {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .footer-right {
            display: table-cell;
            vertical-align: top;
            text-align: right;
            width: 50%;
        }

        .footer-text {
            font-size: 9px;
            color: #8a9ab0;
            line-height: 1.7;
        }

        .footer-confidential {
            font-size: 9px;
            font-weight: bold;
            color: #b05a00;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .footer-page {
            font-size: 9px;
            color: #8a9ab0;
            margin-top: 3px;
        }

        /* ─── Signature block ─── */
        .signature-section {
            margin-top: 28px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            text-align: center;
            padding: 0 16px;
        }

        .signature-label {
            font-size: 10px;
            color: #4a6080;
            margin-bottom: 42px;
        }

        .signature-line {
            border-top: 1px solid #1a3a6b;
            margin: 0 auto;
            width: 80%;
        }

        .signature-name {
            font-size: 10.5px;
            font-weight: bold;
            color: #1a3a6b;
            margin-top: 5px;
        }

        .signature-title {
            font-size: 9.5px;
            color: #7a8fa6;
            margin-top: 2px;
        }

        /* ─── Watermark ─── */
        .watermark {
            position: fixed;
            top: 38%;
            left: 15%;
            width: 70%;
            text-align: center;
            font-size: 62px;
            font-weight: bold;
            color: rgba(26, 58, 107, 0.045);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            transform: rotate(-30deg);
            pointer-events: none;
            z-index: -1;
        }
    </style>
</head>
<body>

    @php
        /* ── Masking Helpers ──────────────────────────────────
         * maskName('Budi Santoso')   → 'Budi S***'
         * maskEmail('budi@mail.com') → 'budi***@mail.com'
         * maskText('Jl. Sudirman 10') → 'Jl. Sud***'
         */
        function maskName($val) {
            if (!$val || $val === '-') return '-';
            $parts = explode(' ', trim($val));
            $masked = [];
            foreach ($parts as $i => $part) {
                if (strlen($part) <= 1) { $masked[] = $part; continue; }
                if ($i === 0) {
                    $masked[] = $part; // show first name in full
                } else {
                    $masked[] = mb_substr($part, 0, 1) . '***';
                }
            }
            return implode(' ', $masked);
        }

        function maskEmail($email) {
            if (!$email || $email === '-') return '-';
            [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');
            $visible = mb_substr($local, 0, min(4, mb_strlen($local)));
            return $visible . '***@' . ($domain ?: '***');
        }

        function maskText($val, $show = 5) {
            if (!$val || $val === '-') return '-';
            $trimmed = trim($val);
            if (mb_strlen($trimmed) <= $show) return $trimmed;
            return mb_substr($trimmed, 0, $show) . '***';
        }

        function statusClass($status) {
            return match(strtolower($status ?? '')) {
                'new', 'baru'             => 'status-new',
                'process', 'diproses'     => 'status-process',
                'closed', 'ditutup', 'done', 'selesai' => 'status-done',
                'rejected', 'ditolak'     => 'status-reject',
                default                   => 'status-default',
            };
        }

        $reporterName  = maskName($report->user->name ?? null);
        $reporterEmail = maskEmail($report->user->email ?? null);
    @endphp

    {{-- Watermark --}}
    <div class="watermark">RAHASIA</div>

    <div class="page">

        {{-- ═══════ LETTERHEAD ═══════ --}}
        <div class="letterhead">
            <div class="letterhead-inner">
                <div class="letterhead-logo">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo BSP">
                </div>
                <div class="letterhead-company">
                    <div class="company-name">PT Bumi Siak Pusako Zapin</div>
                    <div class="company-sub">Whistleblowing System &nbsp;·&nbsp; Laporan Resmi</div>
                </div>
                <div class="letterhead-right">
                    <div class="doc-label">No. Laporan</div>
                    <div class="doc-number">{{ $report->report_number }}</div>
                </div>
            </div>
        </div>
        <div class="header-accent"></div>

        {{-- ═══════ DOCUMENT TITLE ═══════ --}}
        <div class="doc-title-block">
            <div class="doc-title">Laporan Whistleblowing System</div>
            <div class="doc-title-sub">
                Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB
            </div>
            <div><span class="confidential-badge">🔒 Dokumen Rahasia — Hanya untuk Pejabat Berwenang</span></div>
        </div>

        {{-- ═══════ SECTION 1 — DATA LAPORAN ═══════ --}}
        <div class="section">
            <div class="section-title">1 &nbsp; Data Laporan</div>
            <table class="info-table">
                <tr>
                    <td class="col-label">No. Laporan</td>
                    <td class="col-sep">:</td>
                    <td class="col-value"><strong>{{ $report->report_number }}</strong></td>
                </tr>
                <tr>
                    <td class="col-label">Status</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">
                        <span class="status-pill {{ statusClass($report->status ?? '') }}">
                            {{ $report->status_label ?? '-' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Kategori</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ $report->category_label ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Judul Laporan</td>
                    <td class="col-sep">:</td>
                    <td class="col-value"><strong>{{ $report->title ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td class="col-label">Tanggal Kejadian</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ optional($report->incident_date)->format('d F Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Lokasi Kejadian</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ $report->location ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Estimasi Kerugian</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">
                        {{ $report->estimated_loss !== null
                            ? 'Rp ' . number_format((float) $report->estimated_loss, 2, ',', '.')
                            : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Ada Bukti Pendukung</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">
                        @if($report->has_evidence)
                            <span class="bool-yes">✔ Ya</span>
                        @else
                            <span class="bool-no">✘ Tidak</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Pernah Dilaporkan</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">
                        @if($report->reported_before)
                            <span class="bool-yes">✔ Ya</span>
                        @else
                            <span class="bool-no">✘ Tidak</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Dilaporkan ke Pihak Lain</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">
                        @if($report->reported_to_other_party)
                            <span class="bool-yes">✔ Ya</span>
                        @else
                            <span class="bool-no">✘ Tidak</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Tanggal Pengiriman</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ optional($report->submitted_at)->format('d F Y, H:i') ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- ═══════ SECTION 2 — POKOK MASALAH ═══════ --}}
        <div class="section">
            <div class="section-title">2 &nbsp; Uraian Permasalahan</div>
            <table class="info-table">
                <tr>
                    <td class="col-label">Pokok Masalah</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ $report->description ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Pihak yang Terlibat</td>
                    <td class="col-sep">:</td>
                    <td class="col-value masked">
                        {{ $report->involved_parties ? maskText($report->involved_parties, 8) : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Kronologi Kejadian</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ $report->chronology ?: '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- ═══════ SECTION 3 — DATA PELAPOR (MASKED) ═══════ --}}
        <div class="section">
            <div class="section-title">3 &nbsp; Identitas Pelapor <small style="font-weight:normal;font-size:9px;letter-spacing:0;">(data disamarkan)</small></div>
            <table class="info-table">
                <tr>
                    <td class="col-label">Nama Pelapor</td>
                    <td class="col-sep">:</td>
                    <td class="col-value masked">{{ $reporterName }}</td>
                </tr>
                <tr>
                    <td class="col-label">Email Pelapor</td>
                    <td class="col-sep">:</td>
                    <td class="col-value masked">{{ $reporterEmail }}</td>
                </tr>
            </table>
        </div>

        {{-- ═══════ SECTION 4 — TINDAK LANJUT ═══════ --}}
        <div class="section">
            <div class="section-title">4 &nbsp; Tindak Lanjut &amp; Penyelesaian</div>
            <table class="info-table">
                <tr>
                    <td class="col-label">Catatan Admin</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ $report->admin_notes ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Hasil Tindak Lanjut</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ $report->follow_up_result ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Tanggal Diproses</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ optional($report->processed_at)->format('d F Y, H:i') ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Tanggal Ditutup</td>
                    <td class="col-sep">:</td>
                    <td class="col-value">{{ optional($report->closed_at)->format('d F Y, H:i') ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- ═══════ SECTION 5 — LAMPIRAN ═══════ --}}
        <div class="section">
            <div class="section-title">5 &nbsp; Dokumen Lampiran</div>
            @if($report->attachments->count())
                <ul class="attachment-list">
                    @foreach($report->attachments as $attachment)
                        <li>
                            <span class="att-icon">&#128206;</span>
                            <span class="att-name">{{ $attachment->original_name }}</span>
                            <span class="att-size">{{ $attachment->file_size_label }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="att-empty">Tidak ada lampiran yang disertakan.</div>
            @endif
        </div>

        {{-- ═══════ SIGNATURE BLOCK ═══════ --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Disiapkan oleh</div>
                <div class="signature-line"></div>
                <div class="signature-name">Officer WBS</div>
                <div class="signature-title">Unit Kepatuhan &amp; Risiko</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Diverifikasi oleh</div>
                <div class="signature-line"></div>
                <div class="signature-name">Manager Kepatuhan</div>
                <div class="signature-title">PT Bumi Siak Pusako Zapin</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Disetujui oleh</div>
                <div class="signature-line"></div>
                <div class="signature-name">Direktur Utama</div>
                <div class="signature-title">PT Bumi Siak Pusako Zapin</div>
            </div>
        </div>

        {{-- ═══════ FOOTER ═══════ --}}
        <div class="footer">
            <div class="footer-inner">
                <div class="footer-left">
                    <div class="footer-confidential">⚠ Dokumen Rahasia &amp; Terlindungi</div>
                    <div class="footer-text" style="margin-top:4px;">
                        Dokumen ini bersifat rahasia dan hanya diperuntukkan bagi pihak yang berwenang.<br>
                        Dilarang menyebarluaskan tanpa izin dari manajemen PT Bumi Siak Pusako Zapin.
                    </div>
                </div>
                <div class="footer-right">
                    <div class="footer-text">PT Bumi Siak Pusako Zapin</div>
                    <div class="footer-text">Whistleblowing System — Laporan Resmi</div>
                    <div class="footer-page">Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</div>
                </div>
            </div>
        </div>

    </div>{{-- end .page --}}

</body>
</html>