<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Videotron - Doorprize Gathering Chuhatsu</title>
    
    <link rel="shortcut icon" href="{{asset('theme/assets/images/logo/logo-voyages.svg')}}" type="image/x-icon" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Undian Custom CSS -->
    <link rel="stylesheet" href="{{ asset('theme/assets/css/undian.css') }}">

    <style>
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #0f172a;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .fullscreen-container {
            min-height: 100vh;
            width: 100vw;
            padding: 1.5rem !important;
            box-sizing: border-box;
        }

        /* Responsive text sizes for videotron */
        .vt-title {
            font-size: 2.2rem;
            letter-spacing: 1px;
        }

        .vt-badge {
            font-size: 1.1rem;
        }

        .vt-status {
            font-size: 1.8rem;
            letter-spacing: 2px;
        }

        .zoo-table th {
            font-size: 0.95rem;
            padding: 8px 12px;
        }

        .zoo-table td {
            font-size: 1rem;
            padding: 8px 12px;
        }
    </style>
</head>
<body>

<div class="machine-bg d-flex flex-column align-items-center justify-content-between fullscreen-container" id="machine-container">
    
    <!-- Header Videotron (Tanpa Tombol Admin/Navbar) -->
    <div class="text-center text-white my-2" style="z-index: 5;">
        <img src="{{ asset('theme/assets/images/logo-chuhatsu.png') }}" alt="Logo Chuhatsu" class="logo-chuhatsu mb-2" style="max-height: 55px;"
             onerror="this.outerHTML='<h2 class=\'text-success fw-bolder mb-0 font-monospace\'>CHUHATSU</h2>'">
        <h1 class="fw-bold mb-1 text-warning vt-title" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.7);">
            <i class="bi bi-magic me-2"></i>Doorprize Gathering Chuhatsu
        </h1>
    </div>

    <!-- Zoo Ornaments -->
    <div class="zoo-decor decor-tl">🌿</div>
    <div class="zoo-decor decor-tr">🌴</div>
    <div class="zoo-decor decor-bl">🍃</div>
    <div class="zoo-decor decor-br">🌿</div>

    <!-- STATE 1: Animation Rolling (Bola acak) -->
    <div id="animation-container" class="text-center w-100 my-auto" style="z-index: 2;">
        <h2 class="text-warning text-uppercase fw-bold mb-4 vt-status" id="rolling-status" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.6);">
            🐾 Bersiaplah! Doorprize Menarik Menanti Anda... 🐾
        </h2>
        
        <div id="single-ball-container" class="my-4">
            <div class="giant-ball" id="the-ball">🦁</div>
        </div>
    </div>

    <!-- STATE 2: Tabel Hasil Undian (Prize Stays Static, NPK & Nama Slot Spin) -->
    <div id="result-table-container" class="d-none w-100 zoo-table-card p-4 my-auto" style="z-index: 2;">
        <div class="text-center mb-3">
            <h3 class="fw-bold text-success text-uppercase mb-0 fs-4">
                <span class="me-2">🌴</span>PENGUMUMAN PEMENANG (<span id="winner-count-display">0</span> HADIAH)<span class="ms-2">🦁</span>
            </h3>
        </div>
        
        <!-- Layout 2 Kolom Tabel -->
        <div class="row g-3">
            <!-- Tabel Kiri -->
            <div class="col-md-6">
                <div class="table-responsive border rounded-3 overflow-hidden shadow-sm">
                    <table class="table table-hover table-bordered text-center align-middle mb-0 zoo-table">
                        <thead>
                            <tr>
                                <th width="8%">#</th>
                                <th width="22%">🦁 NPK</th>
                                <th width="40%">🌴 Nama</th>
                                <th width="30%">🎁 Hadiah</th>
                            </tr>
                        </thead>
                        <tbody id="winner-table-left" class="fw-semibold"></tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Kanan -->
            <div class="col-md-6">
                <div class="table-responsive border rounded-3 overflow-hidden shadow-sm">
                    <table class="table table-hover table-bordered text-center align-middle mb-0 zoo-table">
                        <thead>
                            <tr>
                                <th width="8%">#</th>
                                <th width="22%">🦁 NPK</th>
                                <th width="40%">🌴 Nama</th>
                                <th width="30%">🎁 Hadiah</th>
                            </tr>
                        </thead>
                        <tbody id="winner-table-right" class="fw-semibold"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Visual -->
    <div class="text-white-50 small my-1" style="z-index: 5;">
        <span>Doorprize System &copy; {{ date('Y') }} PT Chuhatsu Indonesia</span>
    </div>

</div>

<!-- Confetti JS -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
    const channel = new BroadcastChannel('doorprize_channel');
    let rollingInterval = null;
    let audioCtx = null;
    let candidatePool = [];
    const animals = ['🦁', '🐯', '🦒', '🐘', '🐼', '🦊', '🐸', '🐵', '🐰', '🐹'];

    // Inisialisasi Audio kontekstual
    window.addEventListener('click', initAudio);
    function initAudio() {
        if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        if (audioCtx.state === 'suspended') audioCtx.resume();
    }

    // Broadcast Listener dari Operator Admin
    channel.onmessage = async (event) => {
        const data = event.data;

        switch (data.action) {
            case 'CHANGE_SESSION':
                document.getElementById('fs-session-badge').innerText = data.sessionText;
                resetDisplay();
                break;

            case 'START_ROLLING':
                initAudio();
                document.getElementById('result-table-container').classList.add('d-none');
                document.getElementById('animation-container').classList.remove('d-none');
                document.getElementById('rolling-status').innerHTML = `🔥 MENGACAK UNTUK ${data.totalPrizes} PEMENANG... 🔥`;
                document.getElementById('the-ball').classList.add('spin-fast');

                if (rollingInterval) clearInterval(rollingInterval);
                rollingInterval = setInterval(() => {
                    document.getElementById('the-ball').innerText = animals[Math.floor(Math.random() * animals.length)];
                    playTick(750);
                }, 80);
                break;

            case 'STOP_AND_SHOW':
                if (rollingInterval) clearInterval(rollingInterval);
                document.getElementById('the-ball').classList.remove('spin-fast');
                candidatePool = data.candidates || [];
                await revealWinnersWithSlotReel(data.winners, data.totalPrizes);
                break;

            case 'RESET_DISPLAY':
                resetDisplay();
                break;
        }
    };

    function resetDisplay() {
        if (rollingInterval) clearInterval(rollingInterval);
        document.getElementById('result-table-container').classList.add('d-none');
        document.getElementById('animation-container').classList.remove('d-none');
        document.getElementById('rolling-status').innerText = '🐾 MENUNGGU INSTRUKSI OPERATOR 🐾';
        document.getElementById('the-ball').innerText = '🦁';
        document.getElementById('the-ball').classList.remove('spin-fast');
    }

    function formatPrizeBadge(prizeName) {
        if (prizeName.toLowerCase().includes('grand prize')) {
            return `<span class="badge prize-grand">👑 ${prizeName}</span>`;
        }
        return `<span class="badge prize-tag">🎁 ${prizeName}</span>`;
    }

    // RENDER TABEL: NPK DIACAK DULU, KETIKA NPK BERHENTI BARU NAMA KELUAR (TANPA STATUS KARYAWAN)
    async function revealWinnersWithSlotReel(finalWinners, totalPrizes) {
        document.getElementById('winner-count-display').innerText = totalPrizes;
        document.getElementById('animation-container').classList.add('d-none');
        document.getElementById('result-table-container').classList.remove('d-none');

        const halfLength = Math.ceil(finalWinners.length / 2);
        let leftHtml = '', rightHtml = '';

        // 1. Tampilkan tabel dengan kolom Hadiah sudah DIEM & FIX di posisinya!
        finalWinners.forEach((w, index) => {
            const rowHtml = `
                <tr id="winner-row-${index}" style="opacity: 0.4;">
                    <td class="fw-bold align-middle">${index + 1}</td>
                    <td class="align-middle npk-cell fw-bold text-muted">------</td>
                    <td class="align-middle text-start name-cell">
                        <span class="badge bg-light text-muted border fw-normal placeholder-label">⏳ Menunggu...</span>
                    </td>
                    <td class="align-middle prize-cell">
                        ${formatPrizeBadge(w.prize_name)}
                    </td>
                </tr>
            `;
            if (index < halfLength) leftHtml += rowHtml;
            else rightHtml += rowHtml;
        });

        document.getElementById('winner-table-left').innerHTML = leftHtml;
        document.getElementById('winner-table-right').innerHTML = rightHtml;

        // 2. Acak NPK -> Ketika NPK Berhenti -> Baru Nama Keluar
        for (let i = 0; i < finalWinners.length; i++) {
            const w = finalWinners[i];
            const rowElem = document.getElementById(`winner-row-${i}`);
            if (!rowElem) continue;

            const isGrandPrize = w.prize_name.toLowerCase().includes('grand prize');
            const npkCell = rowElem.querySelector('.npk-cell');
            const nameCell = rowElem.querySelector('.name-cell');

            rowElem.style.opacity = '1';
            rowElem.classList.add('row-suspense');
            nameCell.innerHTML = `<span class="badge bg-warning-subtle text-dark border border-warning fw-normal">🔍 Mengacak NPK...</span>`;

            // Durasi slot rolling NPK
            const rollDuration = isGrandPrize ? 2500 : 1200;
            const startTime = Date.now();

            if (isGrandPrize) {
                playDrumroll(rollDuration);
            }

            // A. TAHAP 1: HANYA NPK YANG DIACAK
            await new Promise((resolve) => {
                const slotTimer = setInterval(() => {
                    const elapsed = Date.now() - startTime;

                    const dummyNpk = Math.floor(100000 + Math.random() * 899999);
                    npkCell.innerHTML = `<span class="slot-rolling-npk">${dummyNpk}</span>`;

                    if (!isGrandPrize) playTick(650 + (Math.random() * 200));

                    if (elapsed >= rollDuration) {
                        clearInterval(slotTimer);
                        resolve();
                    }
                }, 40);
            });

            // B. TAHAP 2: NPK KUNCI & BERHENTI
            playDing(520 + (i * 25));
            npkCell.innerHTML = `<span class="text-success fw-bold font-monospace fs-5">${w.npk}</span>`;
            nameCell.innerHTML = `<span class="badge bg-info-subtle text-info-emphasis border border-info fw-normal">✨ Kunci Nama...</span>`;

            // Jeda singkat (350ms) setelah NPK berhenti sebelum nama keluar
            await new Promise(r => setTimeout(r, 350));

            // C. TAHAP 3: KETIKA NPK SUDAH BERHENTI BARU NAMA KELUAR (TANPA STATUS KARYAWAN)
            rowElem.classList.remove('row-suspense');
            rowElem.classList.add('row-revealed');

            nameCell.innerHTML = `<span class="fw-bold text-dark fs-6">${w.name}</span>`;

            playDing(660 + (i * 25));

            // Jeda singkat sebelum baris berikutnya
            await new Promise(r => setTimeout(r, 400));
        }

        // 3. Efek Selebrasi Confetti setelah semua pemenang terkunci
        fireConfetti();
    }

    // Sound Helpers
    function playTick(freq = 800) {
        if (!audioCtx) return;
        try {
            const osc = audioCtx.createOscillator(), gain = audioCtx.createGain();
            osc.type = 'square'; osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(10, audioCtx.currentTime + 0.03);
            gain.gain.setValueAtTime(0.04, audioCtx.currentTime); gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.03);
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 0.03);
        } catch(e) {}
    }

    function playSuspenseThump(freq = 110) {
        if (!audioCtx) return;
        try {
            const osc = audioCtx.createOscillator(), gain = audioCtx.createGain();
            osc.type = 'sine'; osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(25, audioCtx.currentTime + 0.18);
            gain.gain.setValueAtTime(0.35, audioCtx.currentTime); gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.18);
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 0.18);
        } catch(e) {}
    }

    function playDing(freq = 523.25) {
        if (!audioCtx) return;
        try {
            const osc = audioCtx.createOscillator(), gain = audioCtx.createGain();
            osc.type = 'triangle'; osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.25, audioCtx.currentTime); gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 0.35);
        } catch(e) {}
    }

    function playDrumroll(durationMs) {
        let startTime = Date.now();
        let interval = setInterval(() => {
            let elapsed = Date.now() - startTime;
            if (elapsed >= durationMs) {
                clearInterval(interval);
            } else {
                playSuspenseThump(90 + (elapsed / durationMs) * 70);
            }
        }, 70);
    }

    function fireConfetti() {
        var duration = 4 * 1000;
        var animationEnd = Date.now() + duration;
        var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999 };
        var interval = setInterval(function() {
            var timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) return clearInterval(interval);
            var particleCount = 50 * (timeLeft / duration);
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: (Math.random() * 0.2) }, y: Math.random() - 0.2 }));
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: (Math.random() * 0.2) + 0.8 }, y: Math.random() - 0.2 }));
        }, 250);
    }
</script>

</body>
</html>