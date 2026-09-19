<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Peserta</title>
    <style>
        @page {
            size: 396.85pt 204.09pt;
            margin: 0pt;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Helvetica, Arial, sans-serif; background: #fff; }

        /* CSS untuk memisah setiap kartu jadi 1 halaman baru di PDF */
        .card-wrapper {
            width: 396.85pt;
            height: 204.09pt;
            page-break-after: always; /* Pindah ke halaman baru setelah 1 kartu */
            position: relative;
        }
        .card-wrapper:last-child {
            page-break-after: avoid; /* Halaman terakhir tidak perlu break */
        }

        /* Styling Kartu Single */
        .card-table { width: 396.85pt; height: 204.09pt; border-collapse: collapse; table-layout: fixed; }
        .header-tr { height: 38pt; background-color: #0f172a; }
        .header-left { width: 255pt; padding-left: 12pt; vertical-align: middle; }
        .header-right { width: 141.85pt; padding-right: 12pt; text-align: right; vertical-align: middle; }
        .header-title { font-size: 11pt; font-weight: bold; color: #fff; }
        .header-subtitle { font-size: 7.5pt; color: #cbd5e1; margin-top: 2pt; }
        
        .body-left { width: 245pt; vertical-align: top; padding: 10pt 10pt 8pt 12pt; }
        .body-right { width: 151.85pt; vertical-align: middle; text-align: center; border-left: 1.5pt dashed #cbd5e1; padding: 4pt; }

        .label { font-size: 7pt; font-weight: bold; color: #64748b; text-transform: uppercase; }
        .val-name { font-size: 13.5pt; font-weight: bold; color: #0f172a; text-transform: uppercase; margin: 1pt 0 3pt 0; }
        .npk-badge { display: inline-block; background-color: #e0f2fe; color: #0369a1; border: 0.8pt solid #bae6fd; font-size: 8pt; font-weight: bold; padding: 1.5pt 6pt; border-radius: 3pt; }
        .val-text { font-size: 9.5pt; font-weight: bold; color: #1e293b; margin-top: 1pt; }
        .ticket-badge { display: inline-block; background-color: #16a34a; color: #ffffff; font-size: 9pt; font-weight: bold; padding: 2.5pt 7pt; border-radius: 4pt; }
        .qr-img { width: 105pt; height: 105pt; display: block; margin: 0 auto; }
        .qr-text { font-size: 6.5pt; font-weight: bold; color: #64748b; margin-top: 4pt; }
    </style>
</head>
<body>

    @foreach($participants as $participant)
    <div class="card-wrapper">
        <table class="card-table">
            <tr class="header-tr">
                <td class="header-left">
                    <div class="header-title">FAMILY GATHERING</div>
                    <div class="header-subtitle">CHUHATSU LEMBANG PARK ZOO 2026</div>
                </td>
                <td class="header-right">
                    @if(file_exists(public_path('images/logo-voyages.png')))
                        <img src="{{ public_path('images/logo-voyages.png') }}" style="height: 20pt; background: #fff; padding: 2pt 5pt; border-radius: 3pt;" alt="Logo">
                    @endif
                </td>
            </tr>
            <tr>
                <td class="body-left">
                    <div style="margin-bottom: 6pt;">
                        <div class="label">NAMA KARYAWAN</div>
                        <div class="val-name">{{ $participant->name }}</div>
                        <div class="npk-badge">NPK: {{ $participant->npk }}</div>
                    </div>
                    <div style="margin-bottom: 6pt;">
                        <div class="label">KENDARAAN</div>
                        <div class="val-text">{{ $participant->kendaraan ?? '-' }}</div>
                    </div>
                    <div style="border-top: 1pt dashed #cbd5e1; padding-top: 5pt; margin-top: 2pt;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="vertical-align: middle;">
                                    <div class="label">TANGGUNGAN</div>
                                    <div class="val-text">{{ $participant->tanggungan }} Orang</div>
                                </td>
                                <td style="vertical-align: middle; text-align: right;">
                                    <div class="label">TIKET ZOO</div>
                                    <span class="ticket-badge">{{ $participant->total_tiket }} Tiket</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td class="body-right">
                    @if(isset($participant->qrBase64))
                        <img src="{{ $participant->qrBase64 }}" class="qr-img" alt="QR Code">
                    @endif
                    <div class="qr-text">SCAN UNTUK ABSEN</div>
                </td>
            </tr>
        </table>
    </div>
    @endforeach

</body>
</html>