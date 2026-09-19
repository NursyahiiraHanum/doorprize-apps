<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
    @if(isset($participants) && $participants->count() == 1)
        BARCODE QR ABSEN | CHUHATSU GATHERING - {{ $participants->first()->npk }} - {{ $participants->first()->name }}
    @else
        BARCODE QR ABSEN | CHUHATSU GATHERING  - SEMUA QR PESERTA
    @endif
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #eef2f7;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        /* Desain Card Horizontal / Kesamping */
        .ticket-card {
            width: 14cm;
            height: 7.2cm;
            border: 2px solid #1e293b;
            border-radius: 12px;
            background: #ffffff;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            page-break-inside: avoid;
            margin-bottom: 15px;
        }

        /* Strip Event Header */
        .ticket-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 48px;
        }

        .event-title {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #f8fafc;
        }

        .logo-voyages {
            height: 32px;
            width: auto;
            object-fit: contain;
            background: #ffffff;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Body Layout Flex Kesamping */
        .ticket-body {
            display: flex;
            height: calc(100% - 48px);
            padding: 12px 15px;
        }

        /* Sisi Kiri: Informasi Peserta */
        .info-section {
            flex: 1;
            padding-right: 12px;
            border-right: 2px dashed #cbd5e1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .participant-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .info-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }

        .info-value {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Sisi Kanan: QR Code */
        .qr-section {
            width: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-left: 12px;
        }

        .qr-code svg, .qr-code img {
            width: 151px !important;
            height: 151px !important;
        }

        /* Mode Cetak Print PDF/Kertas */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
            }
            .ticket-card {
                box-shadow: none !important;
                border: 1.5px solid #000000 !important;
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

    <!-- Grid Kartu Peserta Horizontal -->
    <div class="row g-3 justify-content-start">
        @foreach($participants as $p)
            <div class="col-auto">
                <div class="ticket-card">
                    <!-- Header Kartu (Judul Event & Logo Chuhatsu) -->
                    <div class="ticket-header">
                        <div>
                            <div class="event-title">FAMILY GATHERING</div>
                            <small style="font-size: 9px; opacity: 0.8; display: block; margin-top: -2px;">CHUHATSU LEMBANG PARK ZOO 2026</small>
                        </div>
                        
                        <!-- Logo Chuhatsu -->
                        <div style="background: #ffffff; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                            <img src="{{ asset('theme/assets/images/logo-chuhatsu.png') }}" 
                                 alt="Chuhatsu Logo" 
                                 style="height: 24px; width: auto; display: block;">
                        </div>
                    </div>

                    <!-- Body Kartu -->
                    <div class="ticket-body">
                        <!-- Informasi Peserta (Kiri) -->
                        <div class="info-section">
                            <div>
                                <div class="info-label">Nama Karyawan</div>
                                <div class="participant-name">{{ $p->name }}</div>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size: 11px;">
                                    NPK: {{ $p->npk }}
                                </span>
                            </div>

                            <div class="row g-1 mt-1">
                                <div class="col-6">
                                    <div class="info-label">Kendaraan</div>
                                    <div class="info-value">{{ $p->kendaraan ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="pt-2 border-top mt-1 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="info-label">Tanggungan</div>
                                    <div class="info-value">{{ $p->tanggungan }} Orang</div>
                                </div>
                                <div class="text-end">
                                    <div class="info-label">Tiket Zoo</div>
                                    <div class="badge bg-success fs-6 px-2 py-1">{{ $p->total_tiket }} Tiket</div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code (Kanan Kesamping) -->
                        <div class="qr-section">
                            <div class="qr-code">
                                {!! QrCode::size(105)->generate($p->qr_code ?? $p->npk) !!}
                            </div>
                            <small class="fw-bold text-muted mt-1" style="font-size: 9px; letter-spacing: 0.5px;">
                                SCAN UNTUK ABSEN
                            </small>

                            <div class="text-center mt-2">
                                <small class="text-muted fw-bold d-block" style="font-size: 8px; letter-spacing: 0.5px;">EVENT BY</small>
                                <img src="{{ asset('theme/assets/images/logo/logo-voyages.png') }}" style="height: 18px; width: auto; margin-top: 1px;" alt="Voyages Logo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

</body>
</html>