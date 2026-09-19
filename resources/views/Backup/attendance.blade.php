@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('theme/assets/css/attendance.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-8">
        <div>
            <h3 class="page-title text-doordark fw-bold mb-1">Registrasi Kehadiran</h3>
            <p class="text-secondary fs-6 fw-bold mb-0">
                <i class="bi bi-qr-code-scan me-1 text-gold"></i> Scan Tiket Peserta Gathering Chuhatsu 2026
            </p>
        </div>
    </div>
</div>

<!-- Layout Bagian Atas: Pemindai Tiket (Kiri) & Data Registrasi (Kanan) -->
<div class="row mb-4">
    <!-- Area Pemindai (Auto-Detect Hardware Gun & Kamera) -->
    <div class="col-xl-5 col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-primary text-white border-0 py-3 rounded-top d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold d-flex align-items-center fs-5">
                    <i class="bi bi-upc-scan me-2 fs-4"></i> Pemindai Tiket
                </h5>
                <span class="badge bg-white text-primary fw-bold" id="scanner-mode-badge">Auto-Detect Ready</span>
            </div>
            <div class="card-body p-4 text-center d-flex flex-column justify-content-between align-items-center">
                
                <!-- Dropdown Pilih Kamera Webcam -->
                <div class="w-100 mb-2 text-start">
                    <label for="sourceSelect" class="form-label fs-6 fw-bold text-dark mb-1">
                        <i class="bi bi-camera-video-fill me-1 text-primary"></i> Sumber Kamera Webcam:
                    </label>
                    <select id="sourceSelect" class="form-select form-select-md fw-semibold border-secondary-subtle shadow-sm"></select>
                </div>

                <!-- Preview Video Kamera, Overlay Frame & Scan Line -->
                <div class="scanner-box d-flex align-items-center justify-content-center my-2">
                    <video id="video" style="width: 100%; height: 100%; object-fit: cover;"></video>
                    <div class="scanner-viewfinder"></div>
                    <div class="scan-line"></div>
                </div>

                <!-- Banner Status Scan -->
                <div class="w-100 my-2 p-2 bg-light rounded-3 border">
                    <span class="d-block text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Status Pemindai</span>
                    <div class="fs-6 fw-bold text-primary" id="scan-status-text">Siap memindai dengan Kamera atau Alat Barcode Gun.</div>
                </div>

                <!-- Tombol Kontrol Kamera -->
                <div class="d-flex gap-2 w-100 justify-content-center">
                    <button id="startButton" class="btn btn-primary px-3 py-2 fw-bold w-50 d-flex align-items-center justify-content-center gap-1 shadow-sm">
                        <i class="bi bi-play-circle-fill fs-6"></i> Mulai Kamera
                    </button>
                    <button id="resetButton" class="btn btn-outline-danger px-3 py-2 fw-bold w-50 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-stop-circle-fill fs-6"></i> Stop Kamera
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Area Validasi (Data Registrasi) -->
    <div class="col-xl-7 col-lg-7 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-success text-white border-0 py-3 rounded-top" id="result-header">
                <h5 class="card-title mb-0 fw-bold d-flex align-items-center fs-5">
                    <i class="bi bi-person-badge-fill fs-4 me-2"></i> Hasil Data Diri Registrasi
                </h5>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-center" id="scan-result-card" style="min-height: 350px;">
                <!-- Empty state -->
                <div class="text-center text-muted py-5" id="empty-state">
                    <i class="bi bi-qr-code fs-1 mb-3 d-block text-secondary" style="opacity: 0.4;"></i>
                    <h4 class="fw-bold text-dark">Menunggu Pemindaian Barcode...</h4>
                    <p class="fs-6 text-muted mb-0">Tembak tiket menggunakan alat scanner fisik atau posisikan ke kamera untuk verifikasi peserta.</p>
                </div>

                <!-- Data Hasil Scan -->
                <div id="user-info" class="d-none w-100">
                    <div class="text-center mb-4">
                        <h1 class="fw-bolder mb-1 text-dark display-6" id="u-name">-</h1>
                        <p class="text-muted fs-4 fw-bold mb-0" id="u-npk">-</p>
                    </div>
                    
                    <div class="row g-3 mb-4 justify-content-center">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 text-center h-100 border">
                                <span class="text-secondary fs-6 fw-bold d-block mb-1">Total Tiket</span>
                                <span class="fw-extrabold fs-2 text-gold" id="u-ticket">0</span> <span class="fw-bold text-muted fs-5">Lembar</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 text-center h-100 border">
                                <span class="text-secondary fs-6 fw-bold d-block mb-1">Kendaraan</span>
                                <span class="badge bg-dark fs-5 px-3 py-2 mt-1" id="u-vehicle">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-success d-flex align-items-center mb-0 border-0 p-3 rounded-3 shadow-sm" id="status-alert" role="alert">
                        <i class="bi bi-check-circle-fill fs-2 me-3" id="status-icon"></i>
                        <div>
                            <div class="fw-bold fs-5" id="status-title">Registrasi Berhasil!</div>
                            <span class="fs-6" id="status-message">Waktu hadir tersimpan di sistem. Tolong serahkan fisik tiket sesuai jumlah di atas.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div> 

<!-- Layout Bagian Bawah: Data Tabel Hadir (Nuansa Kuning Emas) -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 card-gold-header">
            <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h5 class="card-title mb-0 fw-bold text-dark fs-5 d-flex align-items-center me-2">
                        <i class="bi bi-people-fill text-gold me-2 fs-4"></i> Peserta Hadir Terkini
                    </h5>
                    
                    <!-- TOMBOL DEMO: HADIRKAN SEMUA -->
                    @if(isset($allAttended) && $allAttended)
                        {{-- Jika semua peserta sudah hadir --}}
                        <button class="btn btn-sm btn-secondary fw-bold shadow-sm d-flex align-items-center gap-1" disabled title="Semua peserta sudah hadir">
                            <i class="bi bi-check-circle-fill fs-6 text-warning"></i> Semua Peserta Sudah Hadir
                        </button>
                    @else
                        {{-- Jika masih ada peserta yang belum hadir --}}
                        <button class="btn btn-sm btn-warning text-dark fw-bold shadow-sm d-flex align-items-center gap-1" id="btn-demo-all" onclick="demoAttendAll(event)" title="Hadirkan semua peserta untuk demo">
                            <i class="bi bi-magic fs-6"></i> Demo: Hadirkan Semua
                        </button>
                    @endif

                    <!-- TOMBOL RESET TERPILIH -->
                    <button class="btn btn-sm btn-danger d-none fw-bold" id="btn-reset-selected" onclick="resetSelectedAttendance()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Terpilih (<span id="selected-count">0</span>)
                    </button>

                    <button class="btn btn-sm btn-outline-warning text-dark border-warning fw-bold" onclick="window.location.reload()">
                        Muat Ulang Data<i class="bi bi-arrow-clockwise ms-1 text-gold"></i>
                    </button>
                </div>

                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-gold"></i></span>
                    <input type="text" id="search-input" class="form-control border-start-0 bg-light" placeholder="Cari NPK atau nama...">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-gold-header">
                            <tr>
                                <th class="border-0 px-4 py-3" style="width: 40px;">
                                    <input type="checkbox" class="form-check-input" id="check-all" onchange="toggleSelectAll(this)">
                                </th>
                                <th class="border-0 py-3 fw-bold">Nama Peserta</th>
                                <th class="border-0 py-3 text-center fw-bold">Tiket</th>
                                <th class="border-0 py-3 text-center fw-bold">Tiba Pada</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            @forelse($attendances as $attendance)
                            <tr id="row-attendance-{{ $attendance->id }}">
                                <td class="px-4 py-3">
                                    <input type="checkbox" class="form-check-input row-checkbox" value="{{ $attendance->id }}" onchange="updateSelectedCount()">
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold text-gold fs-6">{{ $attendance->participant->npk }}</div>
                                    <span class="fw-semibold text-dark">{{ $attendance->participant->name }}</span>
                                </td>
                                <td class="py-3 text-center fw-bold fs-6">{{ $attendance->participant->total_tiket }}</td>
                                <td class="py-3 text-center fw-bold text-gold fs-6">
                                    <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i:s') }}
                                </td>
                            </tr>
                            @empty
                            <tr id="empty-table-row">
                                <td colspan="5" class="text-center py-4 text-muted fw-semibold">Belum ada peserta yang melakukan registrasi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
<script>
    let selectedDeviceId;
    const codeReader = new ZXing.BrowserMultiFormatReader();
    const sourceSelect = document.getElementById('sourceSelect');
    const startButton = document.getElementById('startButton');
    const resetButton = document.getElementById('resetButton');
    const scanLine = document.querySelector('.scan-line');
    
    let isProcessing = false;

    // --- DETEKSI KETUKAN TIPE BARCODE SCANNER FISIK (HARDWARE GUN) ---
    let barcodeBuffer = "";
    let lastKeyTime = 0;

    window.addEventListener('keydown', function (e) {
        if (document.activeElement && document.activeElement.id === 'search-input') {
            return;
        }

        const currentTime = new Date().getTime();

        if (currentTime - lastKeyTime > 100) {
            barcodeBuffer = "";
        }

        if (e.key === 'Enter') {
            if (barcodeBuffer.length >= 2 && !isProcessing) {
                e.preventDefault();
                
                stopScanner(); 
                document.getElementById('scan-status-text').innerText = "Terdeteksi dari Alat Scanner Fisik...";
                document.getElementById('scanner-mode-badge').innerText = "Mode: Hardware Gun";
                
                playBeep();
                verifyQR(barcodeBuffer);
                barcodeBuffer = "";
            }
        } else if (e.key.length === 1) {
            barcodeBuffer += e.key;
            lastKeyTime = currentTime;
        }
    });

    // --- SETUP KAMERA WEBCAM (ZXing) ---
    window.addEventListener('load', function () {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    stream.getTracks().forEach(track => track.stop());
                    
                    codeReader.listVideoInputDevices()
                        .then((videoInputDevices) => {
                            if (videoInputDevices.length >= 1) {
                                sourceSelect.innerHTML = '';
                                videoInputDevices.forEach((element, index) => {
                                    const sourceOption = document.createElement('option');
                                    sourceOption.text = element.label || `Camera ${index + 1}`;
                                    sourceOption.value = element.deviceId;
                                    sourceSelect.appendChild(sourceOption);
                                });

                                selectedDeviceId = videoInputDevices[0].deviceId;
                                for (const device of videoInputDevices) {
                                    if (device.label.toLowerCase().includes('back') || 
                                        device.label.toLowerCase().includes('rear') || 
                                        device.label.toLowerCase().includes('belakang')) {
                                        selectedDeviceId = device.deviceId;
                                        break;
                                    }
                                }
                                sourceSelect.value = selectedDeviceId;

                                sourceSelect.onchange = () => {
                                    selectedDeviceId = sourceSelect.value;
                                    if (scanLine.style.display === 'block') {
                                        stopScanner();
                                        startScanner();
                                    }
                                };

                                startButton.addEventListener('click', () => {
                                    startScanner();
                                });

                                resetButton.addEventListener('click', () => {
                                    stopScanner();
                                });

                                startScanner();
                            }
                        })
                        .catch((err) => {
                            console.error("Gagal mengambil kamera:", err);
                        });
                })
                .catch(function(err) {
                    console.error("Izin kamera ditolak / tidak tersedia:", err);
                });
        }

        // Fitur Filter Pencarian Tabel
        document.getElementById('search-input').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#attendance-table-body tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });

    function startScanner() {
        if (!selectedDeviceId) return;
        
        scanLine.style.display = 'block';
        startButton.disabled = true;
        document.getElementById('scan-status-text').innerText = "Memindai menggunakan Kamera...";
        document.getElementById('scanner-mode-badge').innerText = "Mode: Kamera";
        
        codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
            if (result && !isProcessing) {
                playBeep();
                verifyQR(result.text);
            }
            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err);
            }
        });
    }

    function stopScanner() {
        codeReader.reset();
        scanLine.style.display = 'none';
        startButton.disabled = false;
        document.getElementById('scan-status-text').innerText = "Kamera dihentikan. Siap memindai dengan Alat Barcode Gun.";
    }

    function verifyQR(qrCodeStr) {
        isProcessing = true;
        document.getElementById('scan-status-text').innerText = "Memproses data barcode: " + qrCodeStr;

        fetch("{{ route('attendance.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_code: qrCodeStr })
        })
        .then(async (response) => {
            const data = await response.json();
            return { status: response.status, body: data };
        })
        .then((res) => {
            const data = res.body;

            if (res.status === 200) {
                renderCardResult('success', data.message, data.data);
                prependTableRows(data.data, data.attendance_id);
            } else if (res.status === 400) {
                renderCardResult('warning', data.message, data.data);
            } else {
                renderCardResult('danger', data.message || 'Data tidak ditemukan!');
            }
        })
        .catch((err) => {
            console.error(err);
            renderCardResult('danger', 'Terjadi kesalahan jaringan atau server.');
        })
        .finally(() => {
            setTimeout(() => { 
                isProcessing = false; 
                document.getElementById('scan-status-text').innerText = "Siap memindai barcode berikutnya...";
            }, 2000);
        });
    }

    function renderCardResult(type, message, participant = null) {
        document.getElementById('empty-state').classList.add('d-none');
        document.getElementById('user-info').classList.remove('d-none');

        const header = document.getElementById('result-header');
        const alertBox = document.getElementById('status-alert');
        const icon = document.getElementById('status-icon');
        const title = document.getElementById('status-title');
        const msgText = document.getElementById('status-message');

        header.className = 'card-header text-white border-0 py-3 rounded-top';
        alertBox.className = 'alert d-flex align-items-center mb-0 border-0 p-3 rounded-3 shadow-sm';
        icon.className = 'fs-2 me-3 bi';

        if (type === 'success') {
            header.classList.add('bg-success');
            alertBox.classList.add('alert-success');
            icon.classList.add('bi-check-circle-fill');
            title.innerText = 'Registrasi Berhasil!';
        } else if (type === 'warning') {
            header.classList.add('bg-warning', 'text-dark');
            alertBox.classList.add('alert-warning');
            icon.classList.add('bi-exclamation-triangle-fill');
            title.innerText = 'Sudah Pernah Absen!';
        } else {
            header.classList.add('bg-danger');
            alertBox.classList.add('alert-danger');
            icon.classList.add('bi-x-circle-fill');
            title.innerText = 'Gagal Registrasi!';
        }

        msgText.innerText = message;

        if (participant) {
            document.getElementById('u-name').innerText = participant.name;
            document.getElementById('u-npk').innerText = 'NPK: ' + participant.npk;
            document.getElementById('u-ticket').innerText = participant.total_tiket ?? '1';
            document.getElementById('u-vehicle').innerText = participant.kendaraan ?? 'Tidak Ada';
        } else {
            document.getElementById('u-name').innerText = 'Tidak Ditemukan';
            document.getElementById('u-npk').innerText = '-';
            document.getElementById('u-ticket').innerText = '0';
            document.getElementById('u-vehicle').innerText = '-';
        }
    }

    function prependTableRows(participant, attendanceId) {
        const emptyRow = document.getElementById('empty-table-row');
        if (emptyRow) emptyRow.remove();

        const existingRow = document.getElementById(`row-attendance-${attendanceId}`);
        if (existingRow) existingRow.remove();

        const timeString = new Date().toLocaleTimeString('id-ID', { hour12: false });
        const tr = document.createElement("tr");
        tr.id = `row-attendance-${attendanceId}`;
        tr.innerHTML = `
            <td class="px-4 py-3">
                <input type="checkbox" class="form-check-input row-checkbox" value="${attendanceId}" onchange="updateSelectedCount()">
            </td>
            <td class="py-3">
                <div class="fw-bold text-gold fs-6">${participant.npk}</div>
                <span class="fw-semibold text-dark">${participant.name}</span>
            </td>
            <td class="py-3 text-center fw-bold fs-6">${participant.total_tiket ?? 1}</td>
            <td class="py-3 text-center fw-bold text-gold fs-6">
                <i class="bi bi-clock me-1"></i> ${timeString}
            </td>
            <td class="px-4 py-3 text-end">
                <button class="btn btn-sm btn-outline-danger fw-semibold" onclick="cancelAttendance(${attendanceId})" title="Reset Kehadiran">
                    <i class="bi bi-trash me-1"></i> Reset
                </button>
            </td>
        `;

        tr.style.backgroundColor = '#fff2cc';
        document.getElementById('attendance-table-body').prepend(tr);

        setTimeout(() => {
            tr.style.backgroundColor = 'transparent';
            tr.style.transition = 'background-color 1.5s ease';
        }, 1500);
    }

    /* FUNGSI DEMO: HADIRKAN SEMUA PESERTA */
    function demoAttendAll(event) {
        if (!confirm('Apakah Anda yakin ingin menghadirkan SELURUH peserta untuk keperluan demo?')) return;

        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...`;

        fetch("{{ route('attendance.demo-all') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                window.location.reload(); // Reload halaman agar status tombol berubah jadi disabled
            } else if (data.status === 'info') {
                alert(data.message);
                // Ubah tombol langsung jadi disabled tanpa reload
                btn.className = "btn btn-sm btn-secondary fw-bold shadow-sm d-flex align-items-center gap-1";
                btn.innerHTML = `<i class="bi bi-check-circle-fill fs-6 text-warning"></i> Semua Peserta Sudah Hadir`;
                btn.disabled = true;
            } else {
                alert(data.message || 'Gagal memproses demo kehadiran.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan pada server.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }


    function cancelAttendance(attendanceId) {
        if (!confirm('Yakin ingin membatalkan kehadiran peserta ini?')) return;

        fetch(`/attendance/${attendanceId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const row = document.getElementById(`row-attendance-${attendanceId}`);
                if (row) row.remove();
                updateSelectedCount();
            } else {
                alert('Gagal membatalkan kehadiran.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan pada server.');
        });
    }

    function toggleSelectAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        const count = selectedCheckboxes.length;
        const btnReset = document.getElementById('btn-reset-selected');
        const countSpan = document.getElementById('selected-count');

        countSpan.innerText = count;
        if (count > 0) {
            btnReset.classList.remove('d-none');
        } else {
            btnReset.classList.add('d-none');
            document.getElementById('check-all').checked = false;
        }
    }

    function resetSelectedAttendance() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        const ids = Array.from(selectedCheckboxes).map(cb => cb.value);

        if (ids.length === 0) return;

        if (!confirm(`Yakin ingin mereset kehadiran untuk ${ids.length} peserta terpilih?`)) return;

        fetch("{{ route('attendance.bulk-reset') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                ids.forEach(id => {
                    const row = document.getElementById(`row-attendance-${id}`);
                    if (row) row.remove();
                });
                updateSelectedCount();
            } else {
                alert('Gagal mereset kehadiran terpilih.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan pada server.');
        });
    }

    function playBeep() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            const ctx = new AudioContext();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            osc.type = 'sine';
            osc.frequency.value = 800;
            
            gain.gain.setValueAtTime(0.5, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.1);
            
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.1);
        } catch(e) {
            console.log("Audio tidak didukung atau diblokir browser");
        }
    }
</script>
@endsection