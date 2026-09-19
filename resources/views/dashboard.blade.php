@extends('layouts.app')

@section('content')
<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-dark mb-1">Doorprize Dashboard Overview</h3>
            <p class="text-muted small mb-0">Sistem Pengundian & Monitoring Presensi Gathering PT Chuhatsu Indonesia</p>
        </div>
        <div>
            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
            <a href="{{ route('doorprize.display') }}" target="_blank" class="btn btn-primary fw-bold shadow-sm rounded-3" style="margin-left:20px;">
                <i class="bi bi-display me-2"></i>Buka Display Videotron
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts Bar -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-3 d-flex gap-2 flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="fw-bold text-dark me-2"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Aksi Cepat:</span>
                    <a href="{{ route('attendance.index') }}" class="btn btn-primary btn-sm fw-bold shadow-sm rounded-3 px-3">
                        <i class="bi bi-qr-code-scan me-1"></i>Scan Absensi Karyawan
                    </a>
                    @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                    <a href="{{ route('doorprize.index') }}" class="btn btn-warning text-dark btn-sm fw-bold shadow-sm rounded-3 px-3">
                        <i class="bi bi-dice-5-fill me-1"></i>Mesin Doorprize
                    </a>
                    <a href="{{ route('prizes.index') }}" class="btn btn-outline-success btn-sm fw-bold rounded-3 px-3">
                        <i class="bi bi-gift me-1"></i>Kelola Hadiah
                    </a>
                    @endif
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                    <a href="{{ route('winners.index') }}" class="btn btn-success btn-sm fw-bold shadow-sm rounded-3 px-3">
                        <i class="bi bi-trophy-fill me-1"></i>History Pemenang
                    </a>
                    @endif
                    @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('participants.index') }}" class="btn btn-light border btn-sm fw-bold rounded-3 px-3">
                        <i class="bi bi-people me-1"></i>Data Peserta Master
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Metric Stats Cards Row -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Master Peserta -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white card-stat h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px">
                            <i class="bi bi-people-fill text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-white-50 mb-1 fw-semibold">Total Master Peserta</p>
                        <h3 class="mb-0 fw-bold text-white">{{ number_format($totalParticipants) }}</h3>
                        <small class="text-white-50"><i class="bi bi-person-badge me-1"></i> Terdaftar di Master</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Peserta Hadir -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 bg-success text-white card-stat h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px">
                            <i class="bi bi-person-check-fill text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-white-50 mb-1 fw-semibold">Peserta Hadir (Absensi)</p>
                        <h3 class="mb-0 fw-bold text-white">{{ number_format($totalAttendance) }}</h3>
                        <small class="text-white-50"><i class="bi bi-check-circle-fill me-1"></i> Kehadiran {{ $attendanceRate }}%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Hadiah -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 bg-info text-white card-stat h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px">
                            <i class="bi bi-gift-fill text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-white-50 mb-1 fw-semibold">Total Unit Hadiah</p>
                        <h3 class="mb-0 fw-bold text-white">{{ number_format($totalPrizes) }}</h3>
                        <small class="text-white-50"><i class="bi bi-box-seam me-1"></i> Sisa {{ $remainingPrizes }} Unit Hadiah</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Pemenang Terpilih -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark card-stat h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-dark bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px">
                            <i class="bi bi-trophy-fill text-dark fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-dark-50 mb-1 fw-semibold">Pemenang Terpilih</p>
                        <h3 class="mb-0 fw-bold text-dark">{{ number_format($totalWinners) }}</h3>
                        <small class="text-dark-50"><i class="bi bi-check2-all me-1"></i> Terkunci di Database</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Section: Recent Winners & Quick Overview -->
<div class="row g-4">
    <!-- Pemenang Terbaru -->
    <div class="col-xl-12">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-trophy me-2 text-warning"></i>5 Pemenang Doorprize Terbaru
                </h5>
                <a href="{{ route('winners.index') }}" class="btn btn-sm btn-outline-primary fw-bold">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-3 py-3">NPK</th>
                                <th class="border-0 py-3">Nama Pemenang</th>
                                <th class="border-0 py-3">Hadiah</th>
                                <th class="border-0 py-3">Sesi Undian</th>
                                <th class="border-0 py-3">Waktu Menang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentWinners as $winner)
                                <tr>
                                    <td class="px-3 py-3 fw-bold text-primary font-monospace">
                                        {{ $winner->participant->npk ?? '-' }}
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($winner->participant->name ?? 'Pemenang') }}&background=0E1F4D&color=fff" class="rounded-circle" width="30" height="30" />
                                            </div>
                                            <span class="fw-semibold text-dark">{{ $winner->participant->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 fw-semibold">
                                        @if(str_contains(strtolower($winner->prize->name ?? ''), 'grand'))
                                            <span class="badge bg-danger"><i class="bi bi-crown me-1"></i>{{ $winner->prize->name ?? '-' }}</span>
                                        @else
                                            <span class="text-dark">{{ $winner->prize->name ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-primary">Sesi {{ $winner->prize->sesi ?? 1 }}</span>
                                    </td>
                                    <td class="py-3 text-muted small">
                                        {{ $winner->won_at ? \Carbon\Carbon::parse($winner->won_at)->format('H:i') . ' WIB' : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                        <em>Belum ada pemenang yang diundi. Klik "Mesin Doorprize" untuk mulai mengundi.</em>
                                    </td>
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
