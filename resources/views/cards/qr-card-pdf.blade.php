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

        /* AKSEN TOP BAR GRADIENT */
        .top-accent-bar {
            height: 5pt;
            background-color: #10b981;
        }

        /* PANEL KIRI (UTAMA) */
        .ticket-left {
            width: 310pt;
            padding: 8pt 10pt 6pt 10pt;
            vertical-align: top;
            background: #ffffff;
        }

        .header-table {
            width: 100%;
            margin-bottom: 2pt;
            border-collapse: collapse;
        }

        .event-label {
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            color: #059669;
        }

        .event-sub {
            font-size: 6.5pt;
            font-weight: bold;
            color: #64748b;
        }

        .participant-name {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 3pt;
            word-wrap: break-word;
        }

        /* BOX INFORMASI GRID */
        .info-box-table {
            width: 100%;
            background-color: #f8fafc;
            border: 0.8pt solid #cbd5e1;
            border-radius: 4pt;
            border-collapse: collapse;
            margin-bottom: 4pt;
        }

        .info-cell {
            padding: 2.5pt 4pt;
            vertical-align: top;
            border-bottom: 0.5pt solid #e2e8f0;
        }

        .info-cell-last {
            border-bottom: none;
        }

        .info-label {
            font-size: 4.8pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.4pt;
        }

        .info-value {
            font-size: 7.5pt;
            font-weight: bold;
            color: #0f172a;
            white-space: nowrap;
        }

        .ticket-badge {
            background-color: #d1fae5;
            color: #047857;
            font-size: 7pt;
            font-weight: bold;
            padding: 1pt 4pt;
            border-radius: 2pt;
            display: inline-block;
        }

        .npk-badge {
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 8pt;
            font-weight: bold;
            padding: 1.5pt 5pt;
            border-radius: 2pt;
            display: inline-block;
        }

        /* FOOTER KIRI */
        .footer-left-table {
            width: 100%;
            border-top: 0.8pt dashed #cbd5e1;
            padding-top: 3pt;
            border-collapse: collapse;
        }

        .logo-chuhatsu-img {
            height: 20pt;
            width: auto;
            max-width: 100pt;
        }

        .ticket-note {
            font-size: 4.2pt;
            color: #64748b;
            line-height: 1.1;
        }

        /* PANEL KANAN (NAVY DARK - QR) */
        .ticket-right {
            width: 110pt;
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
            border-radius: 4pt;
            display: inline-block;
            margin-bottom: 4pt;
        }

        .qr-box svg {
            width: 68pt;
            height: 68pt;
            display: block;
        }

        .scan-text {
            font-size: 5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            color: #38bdf8;
            margin-bottom: 3pt;
        }

        .voyages-footer {
            margin-top: 2pt;
            text-align: center;
        }

        .voyages-label {
            font-size: 4pt;
            font-weight: bold;
            color: #94a3b8;
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
    $chuhatsuLogoPath = public_path('theme/assets/images/logo-chuhatsu.png');
    $chuhatsuLogoBase64 = file_exists($chuhatsuLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($chuhatsuLogoPath)) : '';

    $voyagesLogoPath = public_path('theme/assets/images/logo/logo-voyages.png');
    $voyagesLogoBase64 = file_exists($voyagesLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($voyagesLogoPath)) : '';

    // Generate QR Code sebagai SVG inline (DomPDF v3 support inline SVG)
    $qrCodeSvg = SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->margin(0)->generate($p->qr_code ?? $p->npk);
@endphp

<div class="ticket-page">
    <div class="top-accent-bar"></div>

    <table class="ticket-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- PANEL KIRI (DATA PESERTA) -->
            <td class="ticket-left">
                <table class="header-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div class="event-label">CHUHATSU — FAMILY GATHERING 2026</div>
                            <div class="event-sub">LEMBANG PARK & ZOO</div>
                        </td>
                        <td style="text-align: right; vertical-align: top;">
                            <div class="npk-badge">NPK: {{ $p->npk }}</div>
                        </td>
                    </tr>
                </table>

                <div class="participant-name">{{ $p->name }}</div>

                <table class="info-box-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="info-cell" width="50%">
                            <div class="info-label">PLANT</div>
                            <div class="info-value">{{ strtoupper($p->plant ?? '-') }}</div>
                        </td>
                        <td class="info-cell" width="50%">
                            <div class="info-label">STATUS KARYAWAN</div>
                            <div class="info-value">{{ strtoupper($p->status_karyawan ?? '-') }}</div>
                        </td>
                    </tr>
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
                        <td class="info-cell info-cell-last" width="50%">
                            <div class="info-label">TIKET MASUK ZOO</div>
                            <div class="ticket-badge">{{ $p->total_tiket }} Tiket</div>
                        </td>
                        <td class="info-cell info-cell-last" width="50%">
                            <div class="info-label">TANGGAL LAHIR</div>
                            <div class="info-value">{{ $p->birthdate ? \Carbon\Carbon::parse($p->birthdate)->format('d/m/Y') : '-' }}</div>
                        </td>
                    </tr>
                </table>

                <table class="footer-left-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="vertical-align: middle;" width="65%">
                            <div class="ticket-note">
                                Harap bawa tiket ini saat registrasi & penukaran tiket Lembang Park & Zoo. Valid untuk bukti registrasi resmi.
                            </div>
                        </td>
                        <td style="text-align: right; vertical-align: middle;" width="35%">
                            @if(!empty($chuhatsuLogoBase64))
                                <img src="{{ $chuhatsuLogoBase64 }}" class="logo-chuhatsu-img" alt="Chuhatsu Logo">
                            @endif
                        </td>
                    </tr>
                </table>
            </td>

            <!-- PANEL KANAN (QR CODE & VOYAGES LOGO) -->
            <td class="ticket-right">
                <div class="qr-box">
                    {!! $qrCodeSvg !!}
                </div>
                <div class="scan-text">SCAN FOR CHECK-IN</div>

                <div class="voyages-footer">
                    <div class="voyages-label">EVENT BY</div>
                    @if(!empty($voyagesLogoBase64))
                        <img src="{{ $voyagesLogoBase64 }}" class="logo-voyages-img" alt="Voyages Logo">
                    @endif
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>