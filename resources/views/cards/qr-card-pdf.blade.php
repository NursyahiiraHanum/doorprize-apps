<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Family Gathering - {{ $p->npk }} - {{ $p->name }}</title>
    <style>
        @page {
            margin: 0;
            size: 420pt 210pt; /* Landscape Tiket Standard */
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        html, body {
            width: 420pt;
            height: 210pt;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* CARD MAIN CONTAINER */
        .ticket-card {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            padding: 10pt 12pt 8pt 12pt;
        }

        /* HEADER SECTION */
        .event-header-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #047857; /* Emerald Green khas tiket */
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .event-header-subtitle {
            font-size: 7pt;
            font-weight: bold;
            color: #334155;
            margin-top: 1pt;
        }

        /* LEFT CONTENT COLUMN */
        .left-col {
            width: 63%;
            vertical-align: top;
            padding-right: 10pt;
        }

        .participant-name {
            font-size: 15pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin-top: 6pt;
            line-height: 1.1;
        }

        .category-subtitle {
            font-size: 8pt;
            font-weight: bold;
            color: #047857;
            margin-top: 2pt;
            margin-bottom: 6pt;
        }

        /* DETAILS TABLE */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2pt;
        }
        .detail-label {
            font-size: 6pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
        .detail-val {
            font-size: 8.5pt;
            font-weight: bold;
            color: #1e293b;
            margin-top: 1pt;
            margin-bottom: 4pt;
        }

        /* HIGHLIGHT BOX NPK (Meniru Box Nomor BIB) */
        .npk-box {
            background-color: #f1f5f9;
            border: 1pt solid #cbd5e1;
            border-radius: 4pt;
            padding: 4pt 8pt;
            margin-top: 4pt;
            display: inline-block;
            width: 100%;
        }
        .npk-box-label {
            font-size: 6.5pt;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .npk-box-val {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 1pt;
            margin-top: 1pt;
        }

        /* RIGHT CONTENT COLUMN (LOGOS & QR) */
        .right-col {
            width: 37%;
            vertical-align: top;
            text-align: center;
            border-left: 1pt dashed #cbd5e1;
            padding-left: 8pt;
        }

        .logo-chuhatsu-img {
            max-height: 24pt;
            width: auto;
            max-width: 110pt;
        }

        .qr-image {
            width: 82pt;
            height: 82pt;
            margin-top: 4pt;
        }

        .scan-text {
            font-size: 6pt;
            font-weight: bold;
            color: #64748b;
            letter-spacing: 0.5pt;
            margin-top: 2pt;
        }

        /* EVENT BY VOYAGES FOOTER */
        .event-by-container {
            margin-top: 5pt;
        }
        .event-by-label {
            font-size: 5pt;
            color: #94a3b8;
            font-weight: bold;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .logo-voyages-img {
            max-height: 14pt;
            width: auto;
            margin-top: 1pt;
        }

        /* BOTTOM DISCLAIMER FOOTER */
        .bottom-disclaimer {
            border-top: 0.8pt solid #e2e8f0;
            padding-top: 4pt;
            margin-top: 4pt;
            font-size: 5.5pt;
            color: #64748b;
            line-height: 1.2;
        }
    </style>
</head>
<body>

@php
    // Encode Logo Chuhatsu
    $chuhatsuPath = public_path('theme/assets/images/logo-chuhatsu.png');
    $chuhatsuBase64 = file_exists($chuhatsuPath) ? base64_encode(file_get_contents($chuhatsuPath)) : '';

    // Encode Logo Voyages
    $voyagesPath = public_path('theme/assets/images/logo/logo-voyages.png');
    $voyagesBase64 = file_exists($voyagesPath) ? base64_encode(file_get_contents($voyagesPath)) : '';

    // Generate QR PNG Base64
    $qrCodeContent = $p->qr_code ?? $p->npk;
    $qrPng = SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->margin(0)->generate($qrCodeContent);
    $qrBase64 = base64_encode($qrPng);
@endphp

<table class="ticket-card" cellpadding="0" cellspacing="0">
    <tr>
        <!-- KOLOM KIRI: DETAIL PESERTA & EVENT -->
        <td class="left-col">
            <div class="event-header-title">CHUHATSU OFFICIAL TICKET 2026</div>
            <div class="event-header-subtitle">FAMILY GATHERING - LEMBANG PARK & ZOO</div>

            <!-- NAMA KARYAWAN -->
            <div class="participant-name">{{ $p->name }}</div>
            <div class="category-subtitle">
                {{ strtoupper($p->status_karyawan ?? 'KARYAWAN') }} 
                @if(!empty($p->gender)) ({{ strtoupper($p->gender) }}) @endif
            </div>

            <!-- TABLE DETAIL DATA -->
            <table class="details-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50%" style="vertical-align: top;">
                        <div class="detail-label">KENDARAAN</div>
                        <div class="detail-val">{{ strtoupper($p->kendaraan ?? '-') }}</div>
                    </td>
                    <td width="50%" style="vertical-align: top;">
                        <div class="detail-label">TANGGUNGAN</div>
                        <div class="detail-val">{{ $p->tanggungan ?? 0 }} Orang</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="vertical-align: top; padding-top: 1pt;">
                        <div class="detail-label">TOTAL TIKET ZOO</div>
                        <div class="detail-val" style="color: #047857;">{{ $p->total_tiket ?? 1 }} Tiket Masuk</div>
                    </td>
                </tr>
            </table>

            <!-- KOTAK HIGHLIGHT NOMOR NPK PESERTA -->
            <div class="npk-box">
                <div class="npk-box-label">NOMOR NPK PESERTA</div>
                <div class="npk-box-val">{{ $p->npk }}</div>
            </div>
        </td>

        <!-- KOLOM KANAN: LOGO CHUHATSU, QR CODE, & LOGO VOYAGES -->
        <td class="right-col">
            <!-- LOGO CHUHATSU (FAMILY GATHERING LOGO) -->
            @if($chuhatsuBase64)
                <img src="data:image/png;base64,{{ $chuhatsuBase64 }}" class="logo-chuhatsu-img" alt="Chuhatsu Logo">
            @else
                <div style="font-weight: bold; font-size: 11pt; color: #047857;">CHUHATSU</div>
            @endif

            <br>

            <!-- QR CODE -->
            <img src="data:image/png;base64,{{ $qrBase64 }}" class="qr-image" alt="QR Code"><br>
            <div class="scan-text">SCAN FOR CHECK-IN</div>

            <!-- LOGO VOYAGES (EVENT BY LOGO) -->
            @if($voyagesBase64)
                <div class="event-by-container">
                    <div class="event-by-label">EVENT BY</div>
                    <img src="data:image/png;base64,{{ $voyagesBase64 }}" class="logo-voyages-img" alt="Voyages Logo">
                </div>
            @endif
        </td>
    </tr>

    <!-- FOOTER DISCLAIMER (PALING BAWAH) -->
    <tr>
        <td colspan="2" style="vertical-align: bottom;">
            <div class="bottom-disclaimer">
                * Harap bawa tiket ini (cetak/digital) saat registrasi kehadiran & penukaran tiket Lembang Park & Zoo. Tiket berlaku sebagai bukti registrasi resmi Anda.
            </div>
        </td>
    </tr>
</table>

</body>
</html>