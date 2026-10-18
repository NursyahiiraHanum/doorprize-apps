<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Absen - {{ $p->npk }} - {{ $p->name }}</title>
    <style>
        @page {
            size: 14cm 7.2cm landscape;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* ===== TICKET CARD ===== */
        .ticket-card {
            width: 14cm;
            height: 7.2cm;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            display: flex;
            overflow: hidden;
            position: relative;
            border: 1px solid #cbd5e1;
        }

        /* Top gradient accent */
        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981 0%, #0d9488 50%, #0f172a 100%);
            z-index: 10;
        }

        /* Notch circles */
        .notch-top, .notch-bottom {
            position: absolute;
            width: 16px;
            height: 16px;
            background: #e2e8f0;
            border-radius: 50%;
            right: 3.4cm;
            z-index: 11;
            border: 1px solid #cbd5e1;
        }
        .notch-top  { top: -8px;    transform: translateX(50%); }
        .notch-bottom { bottom: -8px; transform: translateX(50%); }

        /* ===== LEFT PANEL ===== */
        .panel-left {
            flex: 1;
            padding: 14px 16px 10px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #ffffff;
            min-width: 0;
        }

        .event-tag {
            font-size: 7px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #10b981;
            margin-bottom: 2px;
        }

        .participant-name {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 2px;
            letter-spacing: -0.3px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .event-sub {
            font-size: 8px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 8px;
        }

        /* Info grid */
        .info-grid {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 10px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px 10px;
            margin-bottom: 8px;
        }

        .info-item { display: flex; flex-direction: column; }

        .info-label {
            font-size: 6px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 10px;
            font-weight: 700;
            color: #1e293b;
        }

        .info-full { grid-column: span 2; }

        .tiket-badge {
            display: inline-block;
            background: #d1fae5;
            color: #047857;
            font-size: 9px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
        }

        /* Footer */
        .footer-left {
            border-top: 1px dashed #e2e8f0;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .npk-label { font-size: 6px; font-weight: 800; color: #94a3b8; text-transform: uppercase; }
        .npk-value { font-size: 12px; font-weight: 900; color: #0f172a; }

        .logo-chuhatsu { height: 28px; max-width: 110px; object-fit: contain; }

        /* ===== RIGHT PANEL ===== */
        .panel-right {
            width: 3.4cm;
            background: #0f172a;
            border-left: 2px dashed #334155;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 5px;
            text-align: center;
            flex-shrink: 0;
        }

        .qr-wrap {
            background: #ffffff;
            padding: 5px;
            border-radius: 6px;
            margin-bottom: 5px;
            display: inline-block;
            box-shadow: 0 3px 8px rgba(0,0,0,0.3);
        }

        .qr-wrap svg {
            width: 72px !important;
            height: 72px !important;
            display: block;
        }

        .scan-label {
            font-size: 5.5px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #38bdf8;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .brand-footer { display: flex; flex-direction: column; align-items: center; }
        .brand-label { font-size: 5px; font-weight: 700; color: #475569; letter-spacing: 0.8px; text-transform: uppercase; }
        .logo-voyages { height: 12px; width: auto; margin-top: 2px; opacity: 0.85; }

        /* ===== PRINT BUTTON (tidak ikut print) ===== */
        .no-print {
            text-align: center;
            margin-top: 16px;
        }

        .btn-print {
            background: linear-gradient(135deg, #10b981, #0d9488);
            color: white;
            border: none;
            padding: 10px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(16,185,129,0.4);
            letter-spacing: 0.3px;
        }
        .btn-print:hover { opacity: 0.9; }

        .print-hint {
            margin-top: 8px;
            font-size: 11px;
            color: #64748b;
        }

        /* ===== PRINT MEDIA ===== */
        @media print {
            body {
                background: white;
                padding: 0;
                min-height: unset;
                align-items: flex-start;
            }
            .ticket-card {
                box-shadow: none;
                border: 1px solid #999;
                border-radius: 0;
            }
            .notch-top, .notch-bottom { background: white; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div>
        <div class="ticket-card">
            <div class="notch-top"></div>
            <div class="notch-bottom"></div>

            {{-- PANEL KIRI --}}
            <div class="panel-left">
                <div>
                    <div class="event-tag">🌿 CHUHATSU FAMILY GATHERING 2026</div>
                    <div class="participant-name">{{ $p->name }}</div>
                    <div class="event-sub">LEMBANG PARK &amp; ZOO</div>

                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">🏭 Plant</span>
                            <span class="info-value">{{ strtoupper($p->plant ?? '-') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">👤 Status</span>
                            <span class="info-value">{{ strtoupper($p->status_karyawan ?? '-') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">🚗 Kendaraan</span>
                            <span class="info-value">{{ strtoupper($p->kendaraan ?? '-') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">👨‍👩‍👧 Tanggungan</span>
                            <span class="info-value">{{ $p->tanggungan }} Orang</span>
                        </div>
                        <div class="info-item info-full">
                            <span class="info-label">🎫 Tiket Masuk Zoo</span>
                            <span class="tiket-badge">{{ $p->total_tiket }} Tiket</span>
                        </div>
                    </div>
                </div>

                <div class="footer-left">
                    <div>
                        <div class="npk-label">Nomor NPK</div>
                        <div class="npk-value">{{ $p->npk }}</div>
                    </div>
                    @if(!empty($chuhatsuLogoB64))
                        <img src="{{ $chuhatsuLogoB64 }}" class="logo-chuhatsu" alt="Logo Chuhatsu">
                    @endif
                </div>
            </div>

            {{-- PANEL KANAN --}}
            <div class="panel-right">
                <div class="qr-wrap">
                    {!! $qrSvg !!}
                </div>
                <div class="scan-label">📷 Scan Absen</div>

                <div class="brand-footer">
                    <span class="brand-label">Event by</span>
                    @if(!empty($voyagesLogoB64))
                        <img src="{{ $voyagesLogoB64 }}" class="logo-voyages" alt="Voyages">
                    @endif
                </div>
            </div>
        </div>

        <div class="no-print">
            <button class="btn-print" onclick="window.print()">🖨️ Cetak / Save PDF</button>
            <div class="print-hint">Pilih "Save as PDF" di dialog print untuk menyimpan sebagai file PDF</div>
        </div>
    </div>

</body>
</html>
