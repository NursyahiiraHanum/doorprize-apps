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
            position: relative;
        }
        .ticket-table {
            width: 100%;
            height: 206pt
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* AKSEN AROM TIKET / TOP ACCENT BAR */
        .top-accent-bar {
            height: 4pt;
            background-color: #159C33;
        }

        /* PANEL KIRI (PUTIH & UTAMA) */
        .ticket-left {
            width: 320pt;
            padding: 10pt 14pt 8pt 14pt;
            vertical-align: top;
            background: #ffffff;
        }

        .event-label {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.2pt;
            color: #159C33;
            margin-bottom: 2pt;
        }

        .participant-name {
            font-size: 15pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 2pt;
        }

        .event-sub {
            font-size: 7.5pt;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 6pt;
        }

        /* CONTAINER BOX INFORMASI */
        .info-box-table {
            width: 100%;
            background-color: #f8fafc;
            border: 0.8pt solid #e2e8f0;
            border-radius: 4pt;
            border-collapse: collapse;
            margin-bottom: 4pt;
        }

        .info-cell {
            padding: 4pt 6pt;
            vertical-align: top;
        }

        .info-label {
            font-size: 5.5pt;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        .info-value {
            font-size: 8.5pt;
            font-weight: bold;
            color: #1e293b;
        }

        .ticket-badge {
            background-color: #e6f4ea;
            color: #159C33;
            font-size: 8pt;
            font-weight: bold;
            padding: 2pt 5pt;
            border-radius: 3pt;
            display: inline-block;
        }

        /* SECTION NPK & LOGO CHUHATSU (DI FOOTER KIRI) */
        .footer-left-table {
            width: 100%;
            border-top: 1pt dashed #cbd5e1;
            padding-top: 5pt;
            margin-top: 4pt;
            border-collapse: collapse;
        }

        .npk-number {
            font-size: 13.5pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.3pt;
            line-height: 1;
        }

        /* LOGO CHUHATSU (DI PERBESAR SANGAT JELAS) */
        .logo-chuhatsu-img {
            height: 28pt; /* Diperbesar dari 18pt agar sangat tegas */
            width: auto;
            max-width: 130pt;
        }

        .company-badge {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 3pt 6pt;
            border-radius: 2pt;
        }

        /* FOOTER NOTE */
        .ticket-note {
            margin-top: 4pt;
            font-size: 4.8pt;
            color: #64748b;
            line-height: 1.2;
        }

        /* PANEL KANAN (NAVY DARK - QR ABSENSI) */
        .ticket-right {
            width: 100pt;
            background-color: #0f172a;
            color: #ffffff;
            border-left: 1.5pt dashed #334155;
            text-align: center;
            vertical-align: middle;
            padding: 8pt 4pt;
        }

        .qr-box {
            background-color: #ffffff;
            padding: 4pt;
            border-radius: 4pt;
            display: inline-block;
            margin-bottom: 4pt;
        }

        .qr-box img {
            width: 68pt;
            height: 68pt;
            display: block;
        }

        .scan-text {
            font-size: 5.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            color: #38bdf8;
            margin-bottom: 6pt;
        }

        .voyages-footer {
            margin-top: 2pt;
            text-align: center;
        }

        .voyages-label {
            font-size: 4.5pt;
            font-weight: bold;
            color: #64748b;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }

        .logo-voyages-img {
            height: 10pt;
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
    $qrPng = SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(120)->margin(0)->generate($qrCodeContent);
    $qrBase64 = base64_encode($qrPng);
@endphp

<div class="ticket-page">
    <!-- Top Accent Bar -->
    <div class="top-accent-bar"></div>

    <table class="ticket-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- PANEL KIRI (INFO PESERTA) -->
            <td class="ticket-left">
                <div class="event-label">CHUHATSU — FAMILY GATHERING 2026</div>
                <div class="participant-name">{{ $p->name }}</div>
                <div class="event-sub">LEMBANG PARK & ZOO</div>

                <!-- BOX INFORMASI SENSUS DATA -->
                <table class="info-box-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="info-cell" width="50%">
                            <div class="info-label">KENDARAAN</div>
                            <div class="info-value">{{ strtoupper($p->kendaraan ?? '-') }}</div>
                        </td>
                        <td class="info-cell" width="50%">
                            <div class="info-label">TANGGUNGAN</div>
                            <div class="info-value">{{ $p->tanggungan }} Orang</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="info-cell" colspan="2" style="border-top: 0.5pt solid #f1f5f9; padding-top: 3pt;">
                            <div class="info-label" style="margin-bottom: 1pt;">TOTAL TIKET ZOO</div>
                            <div class="ticket-badge">{{ $p->total_tiket }} Tiket Masuk</div>
                        </td>
                    </tr>
                </table>

                <!-- FOOTER KIRI: NPK PESERTA & LOGO CHUHATSU (GEDE) -->
                <table class="footer-left-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="vertical-align: bottom;">
                            <div class="info-label">NOMOR NPK PESERTA</div>
                            <div class="npk-number">{{ $p->npk }}</div>
                        </td>
                        <td style="text-align: right; vertical-align: bottom;">
                            @if($chuhatsuBase64)
                                <img src="data:image/png;base64,{{ $chuhatsuBase64 }}" class="logo-chuhatsu-img" alt="Chuhatsu Logo">
                            @else
                                <span class="company-badge">CHUHATSU</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- CATATAN PENTING -->
                <div class="ticket-note">
                    Harap bawa tiket ini (cetak/digital) saat registrasi kehadiran & penukaran tiket Lembang Park & Zoo. Tiket berlaku sebagai bukti registrasi resmi Anda.
                </div>
            </td>

            <!-- PANEL KANAN (QR CODE & EVENT BY VOYAGES) -->
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