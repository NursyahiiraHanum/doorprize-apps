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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: #e2e8f0;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        }

        /* Container Kartu Tiket Horizontal (14cm x 7.2cm) */
        .ticket-card {
            width: 14cm;
            height: 7.2cm;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1);
            display: flex;
            overflow: hidden;
            position: relative;
            page-break-inside: avoid;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
        }

        /* Aksen Garis Top Border Gradient */
        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #159C33 0%, #0d9488 50%, #0f172a 100%);
            z-index: 12;
        }

        /* Efek Lekukan / Sobekan Tiket (Notch) Upper & Lower */
        .ticket-notch-top, .ticket-notch-bottom {
            position: absolute;
            width: 18px;
            height: 18px;
            background-color: #e2e8f0; /* Samakan dengan background body */
            border-radius: 50%;
            z-index: 10;
            right: 3.4cm;
            border: 1px solid #cbd5e1;
        }
        .ticket-notch-top {
            top: -10px;
            transform: translateX(50%);
        }
        .ticket-notch-bottom {
            bottom: -10px;
            transform: translateX(50%);
        }

        /* Panel Kiri (Putih Dominan) */
        .ticket-left {
            flex: 1;
            padding: 16px 18px 12px 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #ffffff;
            position: relative;
        }

        .event-tag {
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #159C33;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .participant-name {
            font-size: 17px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 2px;
            letter-spacing: -0.2px;
        }

        .event-sub {
            font-size: 9.5px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 10px;
        }

        /* Box Informasi Kartu */
        .info-container {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 8px 12px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 8px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .info-value {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Footer Panel Kiri (NPK & Logo Chuhatsu) */
        .ticket-footer-left {
            border-top: 1px dashed #e2e8f0;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .npk-box {
            display: flex;
            flex-direction: column;
        }

        .npk-badge {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        /* Logo Chuhatsu */
        .logo-chuhatsu {
            height: 38px;
            max-width: 140px;
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
            padding: 12px 6px;
            text-align: center;
            position: relative;
        }

        .qr-container {
            background: #ffffff;
            padding: 6px;
            border-radius: 8px;
            margin-bottom: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: inline-block;
        }

        .qr-container svg, .qr-container img {
            width: 88px !important;
            height: 88px !important;
            display: block;
        }

        .scan-text {
            font-size: 7.5px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #38bdf8;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .voyages-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .voyages-label {
            font-size: 6px;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .logo-voyages {
            height: 14px;
            width: auto;
            margin-top: 2px;
            filter: brightness(0) invert(1);
            opacity: 0.9;
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
            .ticket-notch-top, .ticket-notch-bottom {
                background-color: #ffffff !important;
                border-color: #000000 !important;
            }
        }
    </style>
</head>
<body>

<div class="container py-4">
    <!-- Panel Tombol Cetak (Tidak ikut terprint) -->
    <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-white shadow-sm rounded-3 border">
        <div>
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-qr-code-scan me-2 text-primary"></i>Kupon & QR Code Peserta</h5>
            <small class="text-muted">Total {{ $participants->count() }} Kartu Peserta Siap Cetak</small>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary fw-bold px-4 me-2 shadow-sm">
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
                    <!-- Sobekan Tiket Left-Right -->
                    <div class="ticket-notch-top"></div>
                    <div class="ticket-notch-bottom"></div>
                    
                    <!-- PANEL KIRI (PUTIH - LUAS) -->
                    <div class="ticket-left">
                        <div>
                            <div class="event-tag">
                                <i class="bi bi-tree-fill"></i> CHUHATSU FAMILY GATHERING 2026
                            </div>
                            <div class="participant-name">{{ $p->name }}</div>
                            <div class="event-sub">LEMBANG PARK & ZOO</div>

                            <div class="info-container">
                                <div class="info-item">
                                    <span class="info-label"><i class="bi bi-car-front-fill text-muted"></i> Kendaraan</span>
                                    <span class="info-value">{{ strtoupper($p->kendaraan ?? '-') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="bi bi-people-fill text-muted"></i> Tanggungan</span>
                                    <span class="info-value">{{ $p->tanggungan }} Orang</span>
                                </div>
                                <div class="info-item" style="grid-column: span 2;">
                                    <span class="info-label"><i class="bi bi-ticket-perforated-fill text-success"></i> Total Tiket Zoo</span>
                                    <span class="info-value text-success font-monospace">{{ $p->total_tiket }} Tiket Masuk</span>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-footer-left">
                            <div class="npk-box">
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
                        <div class="scan-text"><i class="bi bi-qr-code me-1"></i>SCAN ABSEN</div>

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