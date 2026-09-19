<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kupon QR - {{ $p->npk }} - {{ $p->name }}</title>
    <style>
        @page {
            margin: 0;
            size: 420pt 210pt landscape;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .card-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            border: 2pt solid #0f172a;
            background-color: #ffffff;
        }
        .header-td {
            background-color: #0f172a;
            color: #ffffff;
            padding: 6pt 12pt;
            height: 40pt;
        }
        .event-title {
            font-size: 11.5pt;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .event-subtitle {
            font-size: 7.5pt;
            color: #cbd5e1;
            margin-top: 1pt;
        }
        .company-badge {
            background-color: #ffffff;
            color: #0f172a;
            font-size: 9pt;
            font-weight: bold;
            padding: 2pt 8pt;
            border-radius: 3pt;
        }
        .body-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        .info-td {
            width: 60%;
            vertical-align: top;
            padding: 10pt 12pt;
            border-right: 1.5pt dashed #cbd5e1;
        }
        .qr-td {
            width: 40%;
            vertical-align: middle;
            text-align: center;
            padding: 6pt 8pt;
        }
        .info-label {
            font-size: 7.5pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 2pt;
        }
        .participant-name {
            font-size: 13.5pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 4pt;
            line-height: 1.1;
        }
        .npk-badge {
            display: inline-block;
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1pt solid #bae6fd;
            font-size: 9.5pt;
            font-weight: bold;
            padding: 2pt 7pt;
            border-radius: 3pt;
            margin-bottom: 8pt;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4pt;
        }
        .meta-val {
            font-size: 10.5pt;
            font-weight: bold;
            color: #1e293b;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10pt;
            padding-top: 6pt;
            border-top: 1pt solid #e2e8f0;
        }
        .ticket-badge {
            background-color: #dcfce7;
            color: #15803d;
            border: 1pt solid #bbf7d0;
            font-size: 10pt;
            font-weight: bold;
            padding: 2.5pt 8pt;
            border-radius: 3pt;
        }
        .scan-text {
            font-size: 7pt;
            font-weight: bold;
            color: #64748b;
            margin-top: 3pt;
            letter-spacing: 0.5pt;
        }
        .voyages-footer {
            margin-top: 5pt;
            text-align: center;
        }
        .voyages-label {
            font-size: 6pt;
            color: #94a3b8;
            font-weight: bold;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .voyages-img {
            height: 16pt;
            width: auto;
            margin-top: 2pt;
        }
    </style>
</head>
<body>

@php
    $chuhatsuPath = public_path('theme/assets/images/logo-chuhatsu.png');
    $chuhatsuBase64 = file_exists($chuhatsuPath) ? base64_encode(file_get_contents($chuhatsuPath)) : '';

    $voyagesPath = public_path('theme/assets/images/logo/logo-voyages.png');
    $voyagesBase64 = file_exists($voyagesPath) ? base64_encode(file_get_contents($voyagesPath)) : '';
@endphp

<table class="card-table">
    <!-- Row 1: Header Bar -->
    <tr>
        <td class="header-td" colspan="2">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="vertical-align: middle;">
                        <div class="event-title">FAMILY GATHERING</div>
                        <div class="event-subtitle">CHUHATSU LEMBANG PARK ZOO 2026</div>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        @if($chuhatsuBase64)
                            <div style="background-color: #ffffff; padding: 2pt 6pt; border-radius: 3pt; display: inline-block;">
                                <img src="data:image/png;base64,{{ $chuhatsuBase64 }}" style="height: 20pt; width: auto; display: block;" alt="Chuhatsu Logo">
                            </div>
                        @else
                            <span class="company-badge">CHUHATSU</span>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Row 2: Card Content -->
    <tr>
        <td colspan="2" style="padding: 0;">
            <table class="body-table">
                <tr>
                    <!-- Info Left Column -->
                    <td class="info-td">
                        <div class="info-label">NAMA KARYAWAN</div>
                        <div class="participant-name">{{ $p->name }}</div>
                        <div class="npk-badge">NPK: {{ $p->npk }}</div>

                        <table class="meta-table">
                            <tr>
                                <td style="width: 50%; vertical-align: top;">
                                    <div class="info-label">KENDARAAN</div>
                                    <div class="meta-val">{{ $p->kendaraan ?? '-' }}</div>
                                </td>
                                <td style="width: 50%; vertical-align: top;">
                                    <div class="info-label">TANGGUNGAN</div>
                                    <div class="meta-val">{{ $p->tanggungan }} Orang</div>
                                </td>
                            </tr>
                        </table>

                        <table class="footer-table">
                            <tr>
                                <td style="vertical-align: middle;">
                                    <div class="info-label">TOTAL TIKET ZOO</div>
                                </td>
                                <td style="text-align: right; vertical-align: middle;">
                                    <span class="ticket-badge">{{ $p->total_tiket }} Tiket</span>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- QR Right Column -->
                    <td class="qr-td">
                        @php
                            $qrCodeContent = $p->qr_code ?? $p->npk;
                            $qrSvg = SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(105)->margin(0)->generate($qrCodeContent);
                            $qrBase64 = base64_encode($qrSvg);
                        @endphp
                        <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" style="width: 105px; height: 105px;" alt="QR Code"><br>
                        <div class="scan-text">SCAN UNTUK ABSENSI</div>

                        @if($voyagesBase64)
                            <div class="voyages-footer">
                                <span class="voyages-label">EVENT BY</span><br>
                                <img src="data:image/png;base64,{{ $voyagesBase64 }}" class="voyages-img" alt="Voyages Logo">
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
