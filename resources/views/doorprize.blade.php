@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('theme/assets/css/undian.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Page -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-1 text-dark">
                <i class="bi bi-sliders me-2 text-primary"></i>Panel Control Doorprize Admin
            </h3>
            <p class="text-muted small mb-0">Kelola pengundian Doorprize & kontrol Layar Videotron secara real-time.</p>
        </div>
        
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <!-- Button Buka Layar Videotron Panggung -->
            <a href="{{ route('doorprize.display') }}" target="_blank" class="btn btn-primary btn-lg fw-bold shadow-sm px-4">
                <i class="bi bi-display me-2"></i>Buka Display Videotron (Layar Panggung)
            </a>
        </div>
    </div>

    <!-- Control Box Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <!-- Filter Sesi -->
                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark mb-1">
                        <i class="bi bi-funnel-fill me-1 text-primary"></i>Pilih Filter Sesi:
                    </label>
                    <select class="form-select form-select-lg border-primary fw-bold text-primary shadow-sm" id="session-select" onchange="changeSession()">
                        <option value="1">Sesi 1 (Semua Peserta Hadir)</option>
                        <option value="2">Sesi 2 (Karyawan Tetap - 2 Thn Belum Menang)</option>
                        <option value="3">Sesi 3 (Karyawan Tetap - Belum Menang)</option>
                    </select>
                </div>

                <!-- Tombol Eksekusi Undian -->
                <div class="col-md-8 d-flex gap-2 justify-content-md-end flex-wrap">
                    <button class="btn btn-warning btn-lg fw-bold px-4 shadow-sm text-dark" id="btn-start" onclick="startDraw()">
                        <i class="bi bi-play-circle-fill me-1"></i> MULAI ACAK
                    </button>
                    <button class="btn btn-danger btn-lg fw-bold px-4 shadow-sm d-none" id="btn-stop" onclick="stopDraw()">
                        <i class="bi bi-stop-circle-fill me-1"></i> BERHENTI / KUNCI
                    </button>
                    <button class="btn btn-outline-secondary btn-lg fw-bold px-3" onclick="resetMachine()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Display
                    </button>
                    <button class="btn btn-success btn-lg fw-bold px-4 shadow-sm" id="btn-save" onclick="saveWinners()" disabled>
                        <i class="bi bi-check-circle me-1"></i> Simpan & Eliminasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Monitor Summary & Live Log -->
    <div class="row g-4">
        <!-- Card Candidate Stats -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-info-circle-fill me-2 text-info"></i>Status Sesi Aktif</h5>
                </div>
                <div class="card-body p-4">
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <span class="text-muted small d-block mb-1">Peserta Eligible (Hadir & Sesuai Syarat):</span>
                        <h3 class="fw-bolder text-primary mb-0" id="candidate-count">0 Orang</h3>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <span class="text-muted small d-block mb-1">Total Hadiah Sesi Ini:</span>
                        <h3 class="fw-bolder text-success mb-0" id="prize-count">0 Unit</h3>
                    </div>

                    <div class="alert alert-info border-0 mb-0 small">
                        <i class="bi bi-broadcast me-1"></i> <strong>Tips Broadcast:</strong> Buka layar Videotron di monitor/projector kedua, lalu gunakan tombol di atas untuk mengontrol animasi dari laptop ini.
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Live Winner Log -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-trophy-fill me-2 text-warning"></i>Preview Pemenang Terakhir</h5>
                    <span class="badge bg-secondary px-3 py-2" id="winner-status-badge">Menunggu Undian</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover table-striped align-middle mb-0" id="admin-winner-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th width="20%">NPK</th>
                                    <th width="40%">Nama Peserta</th>
                                    <th width="32%">Hadiah</th>
                                </tr>
                            </thead>
                            <tbody id="admin-winner-list">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <em>Belum ada undian yang dijalankan. Klik "MULAI ACAK" untuk mengundi.</em>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const channel = new BroadcastChannel('doorprize_channel');
    let currentCandidates = [];
    let currentPrizes = [];
    let lastGeneratedWinners = [];

    async function changeSession() {
        const select = document.getElementById('session-select');
        const sesi = select.value;
        const selectedText = select.options[select.selectedIndex].text;

        try {
            const response = await fetch(`{{ route('doorprize.session-data') }}?sesi=${sesi}`);
            const data = await response.json();

            if (data.success) {
                currentCandidates = data.candidates;
                currentPrizes = data.prizes;

                document.getElementById('candidate-count').innerText = `${currentCandidates.length} Orang`;
                document.getElementById('prize-count').innerText = `${currentPrizes.length} Unit`;

                // Broadcast ke layar Videotron
                channel.postMessage({
                    action: 'CHANGE_SESSION',
                    sessionText: selectedText
                });

                document.getElementById('btn-save').disabled = true;
                document.getElementById('winner-status-badge').innerText = 'Siap Diundi';
                document.getElementById('winner-status-badge').className = 'badge bg-primary px-3 py-2';
            }
        } catch (error) {
            console.error('Error loading session data:', error);
            alert('Gagal mengambil data sesi dari server.');
        }
    }

    document.addEventListener('DOMContentLoaded', changeSession);

    function startDraw() {
        if (currentPrizes.length === 0) return alert('Tidak ada hadiah terdaftar pada sesi ini!');
        if (currentCandidates.length < currentPrizes.length) {
            return alert(`Jumlah peserta eligible (${currentCandidates.length}) kurang dari total hadiah (${currentPrizes.length})!`);
        }

        document.getElementById('btn-start').classList.add('d-none');
        document.getElementById('btn-stop').classList.remove('d-none');
        document.getElementById('btn-save').disabled = true;
        document.getElementById('winner-status-badge').innerText = '🔥 Sedang Mengacak...';
        document.getElementById('winner-status-badge').className = 'badge bg-warning text-dark px-3 py-2';

        // Kirim sinyal start ke Videotron
        channel.postMessage({
            action: 'START_ROLLING',
            totalPrizes: currentPrizes.length
        });
    }

    function stopDraw() {
        document.getElementById('btn-stop').classList.add('d-none');
        document.getElementById('btn-start').classList.remove('d-none');

        // Randomize Winner Allocation
        const finalWinners = [];
        const poolCopy = [...currentCandidates];

        for (let i = 0; i < currentPrizes.length; i++) {
            const randIndex = Math.floor(Math.random() * poolCopy.length);
            const winnerData = { ...poolCopy[randIndex] };
            winnerData.prize_id = currentPrizes[i].id;
            winnerData.prize_name = currentPrizes[i].name;

            finalWinners.push(winnerData);
            poolCopy.splice(randIndex, 1);
        }

        lastGeneratedWinners = finalWinners;

        // Render preview di tabel Admin
        let logHtml = '';
        finalWinners.forEach((w, index) => {
            const isGrand = w.prize_name.toLowerCase().includes('grand prize');
            const badgeClass = isGrand ? 'bg-danger' : 'bg-success';
            logHtml += `
                <tr>
                    <td class="fw-bold">${index + 1}</td>
                    <td class="fw-bold text-primary font-monospace">${w.npk}</td>
                    <td><strong>${w.name}</strong></td>
                    <td><span class="badge ${badgeClass}">${w.prize_name}</span></td>
                </tr>
            `;
        });
        document.getElementById('admin-winner-list').innerHTML = logHtml;

        // Kirim hasil pemenang ke Videotron untuk di-reveal satu per satu
        channel.postMessage({
            action: 'STOP_AND_SHOW',
            winners: finalWinners,
            totalPrizes: currentPrizes.length,
            candidates: currentCandidates
        });

        document.getElementById('btn-save').disabled = false;
        document.getElementById('winner-status-badge').innerText = '✅ Undian Selesai';
        document.getElementById('winner-status-badge').className = 'badge bg-success px-3 py-2';
    }

    function resetMachine() {
        document.getElementById('btn-start').classList.remove('d-none');
        document.getElementById('btn-stop').classList.add('d-none');
        document.getElementById('btn-save').disabled = true;
        document.getElementById('winner-status-badge').innerText = 'Reset';
        document.getElementById('winner-status-badge').className = 'badge bg-secondary px-3 py-2';
        document.getElementById('admin-winner-list').innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    <em>Display direset. Klik "MULAI ACAK" untuk mengundi ulang.</em>
                </td>
            </tr>
        `;

        channel.postMessage({ action: 'RESET_DISPLAY' });
    }

    async function saveWinners() {
        if (lastGeneratedWinners.length === 0) return;

        const btnSave = document.getElementById('btn-save');
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        const sesi = document.getElementById('session-select').value;
        const payload = {
            sesi: parseInt(sesi),
            winners: lastGeneratedWinners.map(w => ({
                participant_id: w.id,
                prize_id: w.prize_id
            }))
        };

        try {
            const response = await fetch(`{{ route('doorprize.store-winners') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();
            if (result.success) {
                alert(result.message);
                changeSession(); // Refresh candidate pool & status
            } else {
                alert('Gagal menyimpan: ' + result.message);
            }
        } catch (error) {
            console.error('Error saving winners:', error);
            alert('Kesalahan sistem saat menyimpan pemenang.');
        } finally {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="bi bi-check-circle me-1"></i> Simpan & Eliminasi';
        }
    }
</script>
@endsection