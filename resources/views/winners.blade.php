@extends('layouts.app')

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('theme/assets/css/winners.css') }}">
@endpush

<!-- Header & Page Title -->
<div class="page-heading mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h3 class="page-title text-doordark fw-bold mb-1">Daftar Pemenang Doorprize</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Pemenang</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <!-- Tombol Reset History -->
            <button type="button" class="btn btn-outline-danger fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#resetModal">
                <i class="bi bi-trash3 me-2"></i>Reset Riwayat
            </button>
            
            <!-- Tombol Export Excel -->
            <a href="{{ route('winners.exportExcel', request()->query()) }}" class="btn btn-success fw-semibold shadow-sm px-3">
                <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
            </a>
        </div>
    </div>
</div>

<!-- Alert Notifikasi -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Summary Cards (Statistik) -->
<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-info">
            <div class="card-body py-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">Total Hadiah Diundi</span>
                    <h4 class="fw-bold text-doordark mb-0">
                        {{ number_format((int)($totalBarang ?? 0)) }} <span class="fs-6 fw-normal text-muted">Item</span>
                    </h4>
                </div>
                <div class="avatar bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-gift fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-success">
            <div class="card-body py-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">Pemenang Valid</span>
                    <h4 class="fw-bold text-success mb-0">
                        {{ number_format((int)($totalPemenang ?? 0)) }} <span class="fs-6 fw-normal text-muted">Orang</span>
                    </h4>
                </div>
                <div class="avatar bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-people fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-warning">
            <div class="card-body py-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">Belum Diundi</span>
                    <h4 class="fw-bold text-warning mb-0">
                        {{ number_format((int)($sisaBarang ?? 0)) }} <span class="fs-6 fw-normal text-muted">Sisa</span>
                    </h4>
                </div>
                <div class="avatar bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grouping Winner Data Per Sesi -->
@php
    $winnerCollection = $winners instanceof \Illuminate\Pagination\AbstractPaginator 
        ? collect($winners->items()) 
        : collect($winners);

    $sesiList = [
        'all' => $winnerCollection,
        '1'   => $winnerCollection->filter(fn($w) => is_object($w) && ((int) preg_replace('/[^0-9]/', '', (string)($w->prize->sesi ?? $w->sesi ?? 1))) === 1),
        '2'   => $winnerCollection->filter(fn($w) => is_object($w) && ((int) preg_replace('/[^0-9]/', '', (string)($w->prize->sesi ?? $w->sesi ?? 1))) === 2),
        '3'   => $winnerCollection->filter(fn($w) => is_object($w) && ((int) preg_replace('/[^0-9]/', '', (string)($w->prize->sesi ?? $w->sesi ?? 1))) === 3),
    ];
    
    $activeTab = (string) request('sesi', 'all');
    if (!in_array($activeTab, ['all', '1', '2', '3'])) {
        $activeTab = 'all';
    }

    $currentWinners = $sesiList[$activeTab];
@endphp

<!-- Main Container Tabel Per Sesi -->
<div class="card border-0 shadow-sm rounded-3">
    <!-- Card Header + Nav Pills Per Sesi -->
    <div class="card-header bg-white py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <!-- Title -->
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-trophy text-warning fs-5"></i>
                <h5 class="card-title mb-0 fw-bold text-doordark">History Winners</h5>
            </div>
            
            <!-- Form Pencarian -->
            <form action="{{ route('winners.index') }}" method="GET" class="w-100" style="max-width: 320px;">
                @if(request('sesi'))
                    <input type="hidden" name="sesi" value="{{ request('sesi') }}">
                @endif

                <!-- <div class="input-group">
                    <input type="text" 
                        name="search" 
                        class="form-control bg-light border-end-0" 
                        placeholder="Cari NPK / Nama..." 
                        value="{{ request('search') }}">
                    
                    <button type="submit" class="btn btn-dark" title="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                    
                    @if(request()->filled('search'))
                        <a href="{{ route('winners.index', request()->only('sesi')) }}" 
                        class="btn btn-outline-secondary" 
                        title="Reset Search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div> -->
            </form>
        </div>

        <!-- Nav Pills Tab Sesi -->
        @php
            $tabs = [
                'all' => [
                    'label'          => 'Semua Sesi',
                    'icon'           => 'bi-grid-fill',
                    'active_class'   => 'active-all',
                    'icon_active'    => '',
                    'icon_inactive'  => '',
                    'badge_active'   => 'bg-white text-dark',
                    'badge_inactive' => 'bg-secondary bg-opacity-20 text-dark',
                ],
                '1' => [
                    'label'          => 'Sesi 1',
                    'icon'           => 'bi-1-circle-fill',
                    'active_class'   => 'active-sesi1',
                    'icon_active'    => 'text-dark',
                    'icon_inactive'  => 'text-info',
                    'badge_active'   => 'bg-dark text-white',
                    'badge_inactive' => 'bg-info bg-opacity-20 text-info',
                ],
                '2' => [
                    'label'          => 'Sesi 2',
                    'icon'           => 'bi-2-circle-fill',
                    'active_class'   => 'active-sesi2',
                    'icon_active'    => 'text-dark',
                    'icon_inactive'  => 'text-warning',
                    'badge_active'   => 'bg-dark text-white',
                    'badge_inactive' => 'bg-warning bg-opacity-20 text-warning',
                ],
                '3' => [
                    'label'          => 'Sesi 3',
                    'icon'           => 'bi-3-circle-fill',
                    'active_class'   => 'active-sesi3',
                    'icon_active'    => 'text-white',
                    'icon_inactive'  => 'text-success',
                    'badge_active'   => 'bg-white text-success',
                    'badge_inactive' => 'bg-success bg-opacity-20 text-success',
                ],
            ];
        @endphp

        <ul class="nav nav-pills card-header-pills gap-2" role="tablist">
            @foreach($tabs as $key => $tab)
                @php
                    $isActive = $activeTab === (string) $key;
                    
                    // Menjaga parameter search tapi mereset page saat pindah tab
                    $queryParams = request()->except(['sesi', 'page']);
                    if ($key !== 'all') {
                        $queryParams['sesi'] = $key;
                    }
                    $tabUrl = route('winners.index', $queryParams);
                @endphp

                <li class="nav-item">
                    <a class="nav-link nav-link-custom fw-semibold px-3 {{ $isActive ? $tab['active_class'] : '' }}" 
                    href="{{ $tabUrl }}">
                        <i class="bi {{ $tab['icon'] }} me-1 {{ $isActive ? $tab['icon_active'] : $tab['icon_inactive'] }}"></i> 
                        {{ $tab['label'] }} 
                        <span class="badge {{ $isActive ? $tab['badge_active'] : $tab['badge_inactive'] }} ms-1">
                            {{ $sesiList[$key]->count() }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Table Body (Kolom Disesuaikan: No, NPK, Nama, Hadiah, Sesi Undian) -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center py-3 px-3" style="width: 60px;">No</th>
                        <th class="py-3 px-3" style="width: 140px;">NPK</th>
                        <th class="py-3 px-3">Nama</th>
                        <th class="py-3 px-3">Hadiah</th>
                        <th class="text-center py-3 px-3" style="width: 130px;">Sesi Undian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($currentWinners as $index => $winner)
                        @php
                            if (!is_object($winner)) { continue; }

                            $prizeName    = $winner->prize->name ?? 'Hadiah Doorprize';
                            $isGrandPrize = str_contains(strtolower($prizeName), 'grand prize');
                            
                            $rawSesi = $winner->prize->sesi ?? $winner->sesi ?? 1;
                            $sesiNum = (int) preg_replace('/[^0-9]/', '', (string) $rawSesi);
                            if ($sesiNum === 0) { $sesiNum = 1; }

                            $sesiBadgeClass = match($sesiNum) {
                                1 => 'bg-info text-info',
                                2 => 'bg-warning text-warning',
                                3 => 'bg-success text-success',
                                default => 'bg-secondary text-secondary'
                            };

                            $firstItem = method_exists($winners, 'firstItem') ? ($winners->firstItem() ?? 1) : 1;
                            $rowNumber = (int) $firstItem + (int) $index;
                        @endphp
                        <tr>
                            <!-- 1. Nomor Urut -->
                            <td class="text-center fw-semibold text-muted px-3">
                                {{ $rowNumber }}
                            </td>

                            <!-- 2. NPK -->
                            <td class="px-3 py-3">
                                <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                    {{ $winner->participant->npk ?? '-' }}
                                </span>
                            </td>

                            <!-- 3. Nama Pemenang -->
                            <td class="px-3 py-3 fw-bold text-dark">
                                {{ $winner->participant->name ?? 'N/A' }}
                            </td>

                            <!-- 4. Hadiah -->
                            <td class="px-3 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $isGrandPrize ? 'bi-award-fill text-warning fs-5' : 'bi-gift-fill text-primary fs-6' }} me-2"></i>
                                    <span class="fw-bold {{ $isGrandPrize ? 'text-danger' : 'text-dark' }}">
                                        {{ $prizeName }}
                                    </span>
                                </div>
                            </td>

                            <!-- 5. Sesi Undian -->
                            <td class="text-center px-3 py-3">
                                <span class="badge {{ $sesiBadgeClass }} bg-opacity-10 rounded-pill px-3 py-2 fw-bold">
                                    Sesi {{ $sesiNum }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                    <h6 class="fw-bold mb-1">Belum Ada Data Pemenang
                                        @if($activeTab !== 'all') 
                                            di Sesi {{ $activeTab }}
                                        @endif
                                    </h6>
                                    <p class="small text-muted mb-0">Tidak ada data pemenang yang tercatat untuk kategori ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div class="px-4 py-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center bg-white rounded-bottom gap-2">
            <span class="text-muted small">
                Menampilkan <strong>{{ $currentWinners->count() }}</strong> Data Pemenang
                @if($activeTab !== 'all') 
                    (Sesi {{ $activeTab }})
                @endif
            </span>
            <div>
                @if(method_exists($winners, 'links'))
                    {{ $winners->appends(request()->query())->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Reset History -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-danger" id="resetModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Reset Riwayat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="mb-0 text-secondary">
                    Apakah Anda yakin ingin menghapus <strong>seluruh riwayat pemenang doorprize</strong>? 
                    Tindakan ini tidak dapat dibatalkan dan seluruh data pemenang akan dikosongkan.
                </p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('winners.reset') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold">
                        <i class="bi bi-trash me-1"></i> Ya, Reset Semua Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection