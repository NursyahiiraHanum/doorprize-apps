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
            overflow: hidden;
            page-break-inside: avoid;
        }
        .ticket-page {
            width: 420pt;
            height: 210pt;
            overflow: hidden;
            position: relative;
        }
        .ticket-table {
            width: 100%;
            height: 206pt;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* AKSEN TOP BAR */
        .top-accent-bar {
            height: 4pt;
            background-color: #159C33;
        }

        /* PANEL KIRI (PUTIH) */
        .ticket-left {
            width: 320pt;
            padding: 8pt 12pt 6pt 12pt;
            vertical-align: top;
            background: #ffffff;
        }

        .event-label {
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1pt;
            color: #159C33;
            margin-bottom: 1pt;
        }

        .participant-name {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 1pt;
        }

        .event-sub {
            font-size: 7pt;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 5pt;
        }

        /* BOX INFORMASI */
        .info-box-table {
            width: 100%;
            background-color: #f8fafc;
            border: 0.8pt solid #e2e8f0;
            border-radius: 4pt;
            border-collapse: collapse;
        }

        .info-cell {
            padding: 3pt 5pt;
            vertical-align: top;
        }

        .info-label {
            font-size: 5pt;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        .info-value {
            font-size: 8pt;
            font-weight: bold;
            color: #1e293b;
        }

        .ticket-badge {
            background-color: #e6f4ea;
            color: #159C33;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 1.5pt 4pt;
            border-radius: 2pt;
            display: inline-block;
        }

        /* FOOTER KIRI (NPK & LOGO) */
        .footer-left-table {
            width: 100%;
            border-top: 0.8pt dashed #cbd5e1;
            padding-top: 4pt;
            margin-top: 4pt;
            border-collapse: collapse;
        }

        .npk-number {
            font-size: 12.5pt;
            font-weight: bold;
            color: #0f172a;
            line-height: 1;
        }

        .logo-chuhatsu-img {
            height: 22pt;
            width: auto;
            max-width: 110pt;
        }

        /* CATATAN PENTING */
        .ticket-note {
            margin-top: 3pt;
            font-size: 4.5pt;
            color: #64748b;
            line-height: 1.15;
        }

        /* PANEL KANAN (NAVY DARK - QR) */
        .ticket-right {
            width: 100pt;
            background-color: #0f172a;
            color: #ffffff;
            border-left: 1.5pt dashed #334155;
            text-align: center;
            vertical-align: middle;
            padding: 6pt 4pt;
        }

        .qr-box {
            background-color: #ffffff;
            padding: 3pt;
            border-radius: 3pt;
            display: inline-block;
            margin-bottom: 3pt;
        }

        .qr-box img {
            width: 62pt;
            height: 62pt;
            display: block;
        }

        .scan-text {
            font-size: 5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6pt;
            color: #38bdf8;
            margin-bottom: 4pt;
        }

        .voyages-footer {
            margin-top: 1pt;
            text-align: center;
        }

        .voyages-label {
            font-size: 4pt;
            font-weight: bold;
            color: #64748b;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }

        .logo-voyages-img {
            height: 9pt;
            width: auto;
            margin-top: 1pt;
        }
    </style>
</head>
<body>

<div class="ticket-page">
    <div class="top-accent-bar"></div>

    <table class="ticket-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- PANEL KIRI -->
            <td class="ticket-left">
                <div class="event-label">CHUHATSU — FAMILY GATHERING 2026</div>
                <div class="participant-name">{{ $p->name }}</div>
                <div class="event-sub">LEMBANG PARK & ZOO</div>

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
                        <td class="info-cell" colspan="2" style="border-top: 0.5pt solid #f1f5f9; padding-top: 2pt;">
                            <div class="info-label" style="margin-bottom: 1pt;">TOTAL TIKET ZOO</div>
                            <div class="ticket-badge">{{ $p->total_tiket }} Tiket Masuk</div>
                        </td>
                    </tr>
                </table>

                <table class="footer-left-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="vertical-align: bottom;">
                            <div class="info-label">NOMOR NPK PESERTA</div>
                            <div class="npk-number">{{ $p->npk }}</div>
                        </td>
                        <td style="text-align: right; vertical-align: bottom;">
                            <img src="{{ public_path('theme/assets/images/logo-chuhatsu.png') }}" class="logo-chuhatsu-img" alt="Chuhatsu Logo">
                        </td>
                    </tr>
                </table>

                <div class="ticket-note">
                    Harap bawa tiket ini (cetak/digital) saat registrasi kehadiran & penukaran tiket Lembang Park & Zoo. Tiket berlaku sebagai bukti registrasi resmi Anda.
                </div>
            </td>

            <!-- PANEL KANAN -->
            <td class="ticket-right">
                <div class="qr-box">
                    <img src="data:image/png;base64,{{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->margin(0)->generate($p->qr_code ?? $p->npk)) }}" alt="QR Code">
                </div>
                <div class="scan-text">SCAN FOR CHECK-IN</div>

                <div class="voyages-footer">
                    <div class="voyages-label">EVENT BY</div>
                    <img src="{{ public_path('theme/assets/images/logo/logo-voyages.png') }}" class="logo-voyages-img" alt="Voyages Logo">
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>