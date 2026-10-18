<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket Peserta Gathering</title>
    <style>
        /* Mengambil font Nunito yang lebih modern dan tidak kaku */
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap');

        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif; /* Mengubah font utama */
        }
        .ticket-page {
            page-break-after: always;
        }
        .ticket-page:last-child {
            page-break-after: auto;
        }
    </style>
</head>

<body>

    @foreach($participants as $p)
    @php
        $kendaraan = strtoupper($p->kendaraan ?? '-');
    @endphp
    <div class="ticket-page">
        <table style="width: 100%; border-collapse: collapse; background-color: #fff;">
            <!-- Baris Gradasi Atas -->
            <tr>
                <td colspan="2" style="padding: 0; height: 4px; background: linear-gradient(90deg, #10b981, #0d9488, #0f172a);"></td>
            </tr>
            <tr>
                <!-- Kolom Kiri (Data Peserta) -->
                <td style="width: 68%; padding: 10px 24px; vertical-align: top; position: relative;">

                    <div style="font-size: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #10b981; margin-bottom: 4px;">CHUHATSU FAMILY GATHERING 2026</div>
                    <div style="font-size: 24px; font-weight: 900; color: #0f172a; text-transform: uppercase; line-height: 1.1; margin-bottom: 2px;">{{ $p->name }}</div>
                    <div style="font-size: 12px; font-weight: 700; color: #4b5563; margin-bottom: 10px;">LEMBANG PARK &amp; ZOO</div>

                    <!-- Info Box -->
                    <div style="background-color: #f1f5f9; border-left: 4px solid #10b981; padding: 8px 12px; margin-bottom: 4px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 50%; vertical-align: top; padding-bottom: 8px;">
                                    <div style="font-size: 8px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 2px;">Kendaraan</div>
                                    <div style="font-size: 13px; font-weight: 800; color: #0f172a;">
                                        @if(in_array(strtolower(trim($kendaraan)), ['mobil pribadi', 'bus depok']))
                                            <span style="background-color: #ef4444; color: #ffffff; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                                {{ $kendaraan }}
                                            </span>
                                        @else
                                            {{ $kendaraan }}
                                        @endif  
                                    </div>
                                </td>
                                <td style="width: 50%; vertical-align: top; padding-bottom: 8px;">
                                    <div style="font-size: 8px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 2px;">Tanggungan</div>
                                    <div style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $p->tanggungan }} Orang</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top: 4px; border-top: 1px dashed #cbd5e1;">
                                    <div style="font-size: 8px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 3px; margin-top: 4px;">Tiket Masuk Zoo</div>
                                    <div style="display: inline-block; background-color: #10b981; color: #ffffff; font-size: 12px; font-weight: 800; padding: 2px 8px; border-radius: 4px;">
                                        {{ $p->total_tiket }} Tiket
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>


                    <!-- Footer NPK & Logo -->
<table style="width: 100%; border-collapse: collapse; margin-top: 8px; padding-top: 6px; border-top: 1px dashed #e2e8f0;">
                        <tr>
                            <td style="vertical-align: bottom;">
                                <div style="font-size: 8px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Nomor NPK Peserta</div>
                                <div style="font-size: 20px; font-weight: 900; color: #111827; line-height: 1;">{{ $p->npk }}</div>
                            </td>
                            <td style="text-align: right; vertical-align: bottom;">
                                <!-- Logo Chuhatsu -->
                                <img src="{{ public_path('theme/assets/images/logo-chuhatsu.png') }}" alt="Chuhatsu" style="height: 30px; max-width: 100px;">
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- Kolom Kanan (QR Code) -->
                <!-- <td style="width: 32%; padding: 20px; vertical-align: middle; text-align: center; background-color: #0f172a; color: white; border-left: 2px dashed #374151;">
                    <div style="background: white; padding: 8px; display: inline-block; border-radius: 6px; margin-bottom: 8px;">
                        <img src="{{ $qrCodes[$p->id] }}" alt="QR Code" style="width: 120px; height: 120px; display: block;">
                    </div>
                    <div style="font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.8; color: #38bdf8;">&#128247; Scan Absen</div>
                    <div style="margin-top: 12px;">
                        <span style="font-size: 6px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px;">Event by</span><br>
                        <img src="{{ public_path('theme/assets/images/logo/logo-voyages.png') }}" alt="Voyages" style="height: 12px; margin-top: 3px; opacity: 0.8;">
                    </div>
                </td> -->

                <!-- Kolom Kanan (QR Code) - Diturunkan dengan menambah padding-top -->
                <td style="width: 32%; padding: 20px; vertical-align: middle; text-align: center; background-color: #0f172a; color: white; border-left: 2px dashed #374151;">
                    <div style="background: white; padding: 8px; display: inline-block; border-radius: 6px; margin-bottom: 8px;">
                        <img src="{{ $qrCodes[$p->id] }}" alt="QR Code" style="width: 120px; height: 120px; display: block;">
                    </div>
                    <div style="font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.8; color: #38bdf8;">&#128247; Scan Absen</div>
                    <div style="margin-top: 12px;">
                        <span style="font-size: 6px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px;">Event by</span><br>
                        <img src="{{ public_path('theme/assets/images/logo/logo-voyages.png') }}" alt="Voyages" style="height: 30px; margin-top: 3px; opacity: 0.8;">
                    </div>
                </td>

            </tr>
        </table>
    </div>
    @endforeach

</body>
</html>
