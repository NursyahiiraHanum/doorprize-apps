<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Peserta - {{ $participant->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Arial', sans-serif; }
        body { background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        
        .card-container {
            width: 360px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            text-align: center;
        }
        .card-header {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 16px;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .qr-box {
            padding: 20px;
            background: #ffffff;
            display: flex;
            justify-content: center;
        }
        .qr-box svg {
            width: 180px !important;
            height: 180px !important;
        }
        .card-body {
            padding: 0 20px 20px;
        }
        .participant-name {
            font-size: 18px;
            font-weight: bold;
            color: #212529;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .participant-npk {
            font-size: 14px;
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .divider {
            border-top: 2px dashed #dee2e6;
            margin-bottom: 15px;
        }
        .info-grid {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 12px;
        }
        .info-item {
            text-align: center;
        }
        .info-item small {
            display: block;
            color: #6c757d;
            font-size: 10px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .info-item strong {
            color: #212529;
        }
        
        @media print {
            body { background: white; }
            .card-container { box-shadow: none; border: 1px solid #000; }
        }
    </style>
</head>
<body>

    <!-- ID 'card-participant' dipakai oleh Browsershot untuk memotong gambar (crop) -->
    <div class="card-container" id="card-participant">
        <div class="card-header">
            Tiket Masuk Peserta
        </div>
        
        <div class="qr-box">
            <!-- QR SVG tanpa Imagick -->
            {!! QrCode::size(180)->margin(1)->generate($participant->npk) !!}
        </div>
        
        <div class="card-body">
            <div class="participant-name">{{ $participant->name }}</div>
            <div class="participant-npk">NPK: {{ $participant->npk }}</div>
            
            <div class="divider"></div>
            
            <div class="info-grid">
                <div class="info-item">
                    <small>Status</small>
                    <strong>{{ $participant->status_karyawan ?? '-' }}</strong>
                </div>
                <div class="info-item">
                    <small>Total Tiket</small>
                    <strong>{{ $participant->total_tiket ?? 0 }} Pax</strong>
                </div>
                <div class="info-item">
                    <small>Kendaraan</small>
                    <strong>{{ $participant->kendaraan ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>

    @if(!request()->has('export'))
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
    @endif
</body>
</html>