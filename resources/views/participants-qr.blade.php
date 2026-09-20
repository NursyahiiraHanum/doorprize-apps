<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
    @if(isset($participants) && $participants->count() == 1)
        BARCODE QR ABSEN | CHUHATSU GATHERING - {{ $participants->first()->npk }} - {{ $participants->first()->name }}
    @else
        BARCODE QR ABSEN | CHUHATSU GATHERING - SEMUA QR PESERTA
    @endif
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #eef2f7;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        }

        /* Container Kartu Tiket Horizontal (14cm x 7.2cm) */
        .ticket-card {
            width: 14cm;
            height: 7.2cm;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            overflow: hidden;
            position: relative;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        /* Panel Kiri (Putih Dominan) */
        .ticket-left {
            flex: 1;
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #ffffff;
        }

        .event-tag {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #159C33;
            margin-bottom: 2px;
        }

        .participant-name {
            font-size: 18px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 2px;
        }

        .event-sub {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 10px;
        }

        /* Grid Data Informasi */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 10px;
        }

        .info-label {
            font-size: 8.5px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .info-value {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Footer Panel Kiri (NPK & Logo Chuhatsu) */
        .ticket-footer-left {
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .npk-badge {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .logo-chuhatsu {
            height: 24px;
            width: auto;
            object-fit: contain;
        }

        /* Panel Kanan (Biru Navy Gelap & Ramping) */
        .ticket-right {
            width: 3.4cm;
            background: #0f172a;
            color: #ffffff;
            border-left: 2px dashed #334155;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 6px;
            text-align: center;
        }

        .qr-container {
            background: #ffffff;
            padding: 5px;
            border-radius: 6px;
            margin-bottom: 6px;
            display: inline-block;
        }

        .qr-container svg, .qr-container img {
            width: 90px !important;
            height: 90px !important;
            display: block;
        }

        .scan-text {
            font-size: 7.5px;
            font-weight: 800;
            letter-spacing: 0.8px;
            color: #ffffff;
            opacity: 0.9;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .voyages-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .voyages-label {
            font-size: 6.5px;
            font-weight: 800;
            color: #94a3b8;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .logo-voyages {
            height: 14px;
            width: auto;
            margin-top: 2px;
            filter: brightness(0) invert(1);
        }

        /* Pengaturan Cetak Print / PDF */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
            }
            .ticket-card {
                box-shadow: none !important;
                border: 1px solid #000000 !important;
            }
        }
    </style>
</head>
<body>

<div class="container py-4">
    <!-- Panel Tombol Cetak (Tidak ikut terprint) -->
    <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-white shadow-sm rounded-3">
        <div>
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-qr-code-scan me-2"></i>Kupon & QR Code Peserta</h5>
            <small class="text-muted">Total {{ $participants->count() }} Kartu Peserta Siap Cetak</small>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary fw-bold px-4 me-2">
                <i class="bi bi-printer me-1"></i> Cetak / Save PDF
            </button>
            <button onclick="window.close()" class="btn btn-outline-secondary">Tutup</button>
        </div>
    </div>

    <!-- Grid Kartu Peserta Ticket Style -->
    <div class="row g-3 justify-content-start">
        @foreach($participants as $p)
            <div class="col-auto">
                <div class="ticket-card">
                    
                    <!-- PANEL KIRI (PUTIH - LUAS) -->
                    <div class="ticket-left">
                        <div>
                            <div class="event-tag">CHUHATSU — FAMILY GATHERING 2026</div>
                            <div class="participant-name">{{ $p->name }}</div>
                            <div class="event-sub">LEMBANG PARK & ZOO</div>

                            <div class="info-grid">
                                <div>
                                    <span class="info-label">Kendaraan</span>
                                    <span class="info-value">{{ strtoupper($p->kendaraan ?? '-') }}</span>
                                </div>
                                <div>
                                    <span class="info-label">Tanggungan</span>
                                    <span class="info-value">{{ $p->tanggungan }} Orang</span>
                                </div>
                                <div style="grid-column: span 2; margin-top: 2px;">
                                    <span class="info-label">Total Tiket Zoo</span>
                                    <span class="info-value text-success">{{ $p->total_tiket }} Tiket Masuk</span>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-footer-left">
                            <div>
                                <span class="info-label">NOMOR NPK PESERTA</span>
                                <span class="npk-badge">{{ $p->npk }}</span>
                            </div>
                            <img src="{{ asset('theme/assets/images/logo-chuhatsu.png') }}" 
                                 alt="Chuhatsu Logo" 
                                 class="logo-chuhatsu">
                        </div>
                    </div>

                    <!-- PANEL KANAN (BIRU NAVY - RAMPING) -->
                    <div class="ticket-right">
                        <div class="qr-container">
                            {!! QrCode::size(100)->generate($p->qr_code ?? $p->npk) !!}
                        </div>
                        <div class="scan-text">SCAN UNTUK ABSEN</div>

                        <div class="voyages-brand">
                            <span class="voyages-label">EVENT BY</span>
                            <img src="{{ asset('theme/assets/images/logo/logo-voyages.png') }}" 
                                 alt="Voyages Logo" 
                                 class="logo-voyages">
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>

</body>
</html>