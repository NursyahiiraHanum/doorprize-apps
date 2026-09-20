<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kupon QR - {{ $p->npk }} - {{ $p->name }}</title>
    <style>
        @page {
            size: 420pt 210pt;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        html, body {
            width: 420pt;
            height: 210pt;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .ticket-page {
            width: 420pt;
            height: 210pt;
            overflow: hidden;
        }
        .ticket {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        /* PANEL KIRI (PUTIH - LEBIH LUAS) */
        .ticket-left {
            width: 330pt; /* Diperluas agar area putih dominan */
            padding: 10pt 12pt 8pt 12pt;
            vertical-align: top;
            background: #ffffff;
        }

        /* PANEL KANAN (BIRU NAVY - PORSI SEDIKIT / RAMPING) */
        .ticket-right {
            width: 90pt; /* Diperkecil agar area biru porsinya sedikit */
            padding: 8pt 4pt;
            vertical-align: middle;
            text-align: center;
            background: #0f172a; /* Warna Biru Navy Gelap */
            color: #ffffff;
            border-left: 1.5pt dashed #334155;
        }

        /* PENYESUAIAN ELEMEN DI DALAM PANEL BIRU (90pt) */
        .qr-box {
            background: #ffffff;
            padding: 4pt;
            display: inline-block;
            border-radius: 3pt;
            margin-bottom: 3pt;
        }
        .qr-box img {
            width: 60pt; /* Disesuaikan agar muat di panel 90pt */
            height: 60pt;
            display: block;
        }
        .scan-text {
            font-size: 5.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            color: #ffffff;
            opacity: 0.9;
            margin-bottom: 4pt;
        }
        .voyages-footer {
            margin-top: 2pt;
            text-align: center;
        }
        .voyages-label {
            font-size: 4.5pt;
            font-weight: 800;
            color: #94a3b8;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .voyages-img, .logo-voyages-img {
            height: 9pt;
            width: auto;
            margin-top: 1pt;
        }

        /* HEADER AREA */
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
            margin-bottom: 1pt;
        }
        .race-category {
            font-size: 8pt;
            font-weight: 700;
            color: #475569;
            margin-bottom: 6pt;
        }

        /* GRID INFORMASI */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4pt;
        }
        .info-grid td {
            padding: 1pt 0;
            vertical-align: top;
            width: 50%;
        }
        .info-label {
            font-size: 5.5pt;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .info-value {
            font-size: 8.5pt;
            font-weight: 700;
            color: #1e293b;
        }

        /* BIB / NPK SECTION */
        .bib-section {
            border-top: 1pt dashed #cbd5e1;
            padding-top: 4pt;
            margin-top: 4pt;
        }
        .bib-table {
            width: 100%;
            border-collapse: collapse;
        }
        .bib-label {
            font-size: 5.5pt;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .bib-number {
            font-size: 18pt;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5pt;
            line-height: 1;
        }
        .logo-section {
            text-align: right;
            vertical-align: bottom;
        }
        .logo-chuhatsu-img {
            height: 18pt;
            width: auto;
        }
        .company-badge {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 7pt;
            font-weight: bold;
            padding: 2pt 5pt;
            border-radius: 2pt;
        }

        /* FOOTER NOTE */
        .ticket-note {
            margin-top: 4pt;
            padding-top: 4pt;
            border-top: 1pt dashed #e2e8f0;
            font-size: 5pt;
            font-weight: bold;
            color: #64748b;
            line-height: 1.2;
        }

        /* QR CODE & VOYAGES LOGO (KANAN) */
        .qr-box {
            background: #ffffff;
            padding: 5pt;
            display: inline-block;
            border-radius: 4pt;
            margin-bottom: 4pt;
        }
        .qr-box img {
            width: 75pt;
            height: 75pt;
            display: block;
        }
        .scan-text {
            font-size: 6pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1pt;
            color: #ffffff;
            opacity: 0.85;
            margin-bottom: 6pt;
        }
        .voyages-footer {
            margin-top: 2pt;
            text-align: center;
        }
        .voyages-label {
            font-size: 4.5pt;
            font-weight: 800;
            color: #9ca3af;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .logo-voyages-img {
            height: 11pt;
            width: auto;
            margin-top: 1pt;
        }
    </style>
</head>
<body>

@php
    $chuhatsuPath = public_path('theme/assets/images/logo-chuhatsu.png');
    $chuhatsuBase64 = file_exists($chuhatsuPath) ? base64_encode(file_get_contents($chuhatsuPath)) : '';

    $voyagesPath = public_path('theme/assets/images/logo/logo-voyages.png');
    $voyagesBase64 = file_exists($voyagesPath) ? base64_encode(file_get_contents($voyagesPath)) : '';

    $qrCodeContent = $p->qr_code ?? $p->npk;
    $qrPng = SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->margin(0)->generate($qrCodeContent);
    $qrBase64 = base64_encode($qrPng);
@endphp

<div class="ticket-page">
    <table class="ticket" cellpadding="0" cellspacing="0">
        <tr>
            <!-- PANEL KIRI (INFO PESERTA) -->
            <td class="ticket-left">
                <div class="event-label">CHUHATSU — FAMILY GATHERING 2026</div>
                <div class="participant-name">{{ $p->name }}</div>
                <div class="race-category">LEMBANG PARK & ZOO</div>

                <table class="info-grid" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div class="info-label">KENDARAAN</div>
                            <div class="info-value">{{ strtoupper($p->kendaraan ?? '-') }}</div>
                        </td>
                        <td>
                            <div class="info-label">TANGGUNGAN</div>
                            <div class="info-value">{{ $p->tanggungan }} Orang</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 3pt;">
                            <div class="info-label">TOTAL TIKET ZOO</div>
                            <div class="info-value" style="color: #159C33;">{{ $p->total_tiket }} Tiket Masuk</div>
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
                                    <span class="company-badge">CHUHATSU</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="ticket-note">
                    Harap bawa tiket ini (cetak/digital) saat registrasi kehadiran & penukaran tiket Lembang Park & Zoo. Tiket berlaku sebagai bukti registrasi resmi Anda.
                </div>
            </td>

            <!-- PANEL KANAN (QR CODE & EVENT BY) -->
            <td class="ticket-right">
                <div class="qr-box">
                    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code">
                </div>
                <div class="scan-text">SCAN FOR CHECK-IN</div>

                @if($voyagesBase64)
                    <div class="voyages-footer">
                        <div class="voyages-label">EVENT BY</div>
                        <img src="data:image/png;base64,{{ $voyagesBase64 }}" class="logo-voyages-img" alt="Voyages Logo">
                    </div>
                @endif
            </td>
        </tr>
    </table>
</div>

</body>
</html>