<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: 420pt 210pt;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #ffffff;
            margin: 0;
            padding: 0;
            width: 420pt;
            height: 210pt;
        }
        .ticket-page {
            width: 420pt;
            height: 210pt;
            page-break-after: always;
            overflow: hidden;
        }
        .ticket-page:last-child {
            page-break-after: auto;
        }
        .ticket {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .ticket-left {
            width: 280pt;
            padding: 12pt 14pt 10pt 14pt;
            vertical-align: top;
            background: #ffffff;
        }
        .ticket-right {
            width: 140pt;
            padding: 10pt;
            vertical-align: middle;
            text-align: center;
            background: #111827;
            color: #ffffff;
            border-left: 1.5pt dashed #374151;
        }

        /* HEADER AREA LEFT */
        .event-label {
            font-size: 7pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5pt;
            color: #159C33;
            margin-bottom: 2pt;
        }
        .participant-name {
            font-size: 15pt;
            font-weight: 900;
            color: #1e293b;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 2pt;
        }
        .race-category {
            font-size: 8.5pt;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8pt;
        }

        /* INFO GRID */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4pt;
        }
        .info-grid td {
            padding: 2pt 0;
            vertical-align: top;
            width: 50%;
        }
        .info-label {
            font-size: 6pt;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            margin-bottom: 1pt;
        }
        .info-value {
            font-size: 9pt;
            font-weight: 700;
            color: #334155;
        }

        /* BIB / NPK SECTION */
        .bib-section {
            border-top: 1pt dashed #e2e8f0;
            padding-top: 6pt;
            margin-top: 4pt;
        }
        .bib-table {
            width: 100%;
            border-collapse: collapse;
        }
        .bib-label {
            font-size: 6pt;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .bib-number {
            font-size: 22pt;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.5pt;
            line-height: 1;
        }
        .logo-section {
            text-align: right;
            vertical-align: bottom;
        }
        .logo-chuhatsu-img {
            height: 22pt;
            width: auto;
        }

        /* NOTE FOOTER */
        .ticket-note {
            margin-top: 6pt;
            padding-top: 4pt;
            border-top: 1pt dashed #e2e8f0;
            font-size: 5.5pt;
            font-weight: bold;
            color: #64748b;
            line-height: 1.2;
        }

        /* RIGHT PANEL (QR & VOYAGES) */
        .qr-box {
            background: #ffffff;
            padding: 6pt;
            display: inline-block;
            border-radius: 4pt;
            margin-bottom: 4pt;
        }
        .qr-box img {
            width: 80pt;
            height: 80pt;
            display: block;
        }
        .scan-text {
            font-size: 6.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1pt;
            color: #ffffff;
            opacity: 0.8;
            margin-bottom: 6pt;
        }
        .event-by-label {
            font-size: 5pt;
            font-weight: 800;
            color: #9ca3af;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .logo-voyages-img {
            height: 12pt;
            width: auto;
            margin-top: 2pt;
        }
    </style>
</head>
<body>
    @foreach($participants as $p)
    @php
        // Path Base64 Logo Chuhatsu
        $chuhatsuPath = public_path('theme/assets/images/logo-chuhatsu.png');
        $chuhatsuBase64 = file_exists($chuhatsuPath) ? base64_encode(file_get_contents($chuhatsuPath)) : '';

        // Path Base64 Logo Voyages
        $voyagesPath = public_path('theme/assets/images/logo/logo-voyages.png');
        $voyagesBase64 = file_exists($voyagesPath) ? base64_encode(file_get_contents($voyagesPath)) : '';

        // Generate QR Code PNG Base64
        $qrCodeContent = $p->qr_code ?? $p->npk;
        $qrPng = SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->margin(0)->generate($qrCodeContent);
        $qrBase64 = base64_encode($qrPng);
    @endphp

    <div class="ticket-page">
        <table class="ticket" cellpadding="0" cellspacing="0">
            <tr>
                <!-- SEKSI KIRI -->
                <td class="ticket-left">
                    <div class="event-label">CHUHATSU — FAMILY GATHERING 2026</div>
                    <div class="participant-name">{{ $p->name }}</div>
                    <div class="race-category">
                        LEMBANG PARK & ZOO · {{ strtoupper($p->status_karyawan ?? 'KARYAWAN') }}
                    </div>

                    <table class="info-grid" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <div class="info-label">KENDARAAN</div>
                                <div class="info-value">{{ strtoupper($p->kendaraan ?? '-') }}</div>
                            </td>
                            <td>
                                <div class="info-label">TANGGUNGAN</div>
                                <div class="info-value">{{ $p->tanggungan ?? 0 }} Orang</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 4pt;">
                                <div class="info-label">TOTAL TIKET ZOO</div>
                                <div class="info-value" style="color: #159C33;">{{ $p->total_tiket ?? 1 }} Tiket Masuk</div>
                            </td>
                        </tr>
                    </table>

                    <div class="bib-section">
                        <table class="bib-table" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="vertical-align: bottom;">
                                    <div class="bib-label">NOMOR NPK PESERTA</div>
                                    <div class="bib-number">{{ $p->npk }}</div>
                                </td>
                                <td class="logo-section">
                                    @if($chuhatsuBase64)
                                        <img src="data:image/png;base64,{{ $chuhatsuBase64 }}" class="logo-chuhatsu-img" alt="Chuhatsu Logo">
                                    @else
                                        <strong style="font-size: 10pt; color: #159C33;">CHUHATSU</strong>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="ticket-note">
                        Harap bawa tiket ini (cetak/digital) saat registrasi kehadiran & penukaran tiket Lembang Park & Zoo. Tiket berlaku sebagai bukti registrasi resmi Anda.
                    </div>
                </td>

                <!-- SEKSI KANAN -->
                <td class="ticket-right">
                    <div class="qr-box">
                        <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code">
                    </div>
                    <div class="scan-text">SCAN FOR CHECK-IN</div>

                    @if($voyagesBase64)
                        <div>
                            <div class="event-by-label">EVENT BY</div>
                            <img src="data:image/png;base64,{{ $voyagesBase64 }}" class="logo-voyages-img" alt="Voyages Logo">
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endforeach
</body>
</html>