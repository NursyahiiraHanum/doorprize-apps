@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('theme/assets/css/hadiah.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title text-doordark fw-bold mb-1">Daftar Hadiah Doorprize</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Hadiah</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-primary fw-semibold ms-2" data-bs-toggle="modal" data-bs-target="#createPrizeModal">
                <i class="bi bi-plus-circle me-2"></i>Tambah Hadiah Baru
            </button>
        </div>
    </div>
</div>

<!-- Alert Flash Message -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Statistik Singkat --->
<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-gradient-info text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50 fw-semibold mb-2">Sesi 1 (All Karyawan)</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold text-white mb-0">{{ $stats['sesi1'] }} Item</h3>
                    <div class="avatar-sm rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center">
                        <i class="bi bi-1-circle text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-gradient-primary text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50 fw-semibold mb-2">Sesi 2 (Tetap)</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold text-white mb-0">{{ $stats['sesi2'] }} Item</h3>
                    <div class="avatar-sm rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center">
                        <i class="bi bi-2-circle text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-danger bg-gradient text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50 fw-semibold mb-2">Sesi 3 (Grandprize)</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold text-white mb-0">{{ $stats['sesi3'] }} Item</h3>
                    <div class="avatar-sm rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center">
                        <i class="bi bi-star-fill text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Control -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-bottom p-0">
        <ul class="nav nav-tabs nav-justified w-100 m-0 border-0" id="prizeTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active py-3 bg-light fw-bold" id="sesi1-tab" data-bs-toggle="tab" data-bs-target="#sesi1" type="button" role="tab">
                    <i class="bi bi-1-circle me-1"></i> Sesi 1
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-3 bg-light fw-bold text-muted" id="sesi2-tab" data-bs-toggle="tab" data-bs-target="#sesi2" type="button" role="tab">
                    <i class="bi bi-2-circle me-1"></i> Sesi 2
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-3 bg-light fw-bold text-muted" id="sesi3-tab" data-bs-toggle="tab" data-bs-target="#sesi3" type="button" role="tab">
                    <i class="bi bi-star-fill me-1"></i> Sesi 3 (Grandprize)
                </button>
            </li>
        </ul>
    </div>
    
    <div class="card-body p-0">
        <div class="tab-content" id="prizeTabContent">
            
            <!-- SESI 1 -->
            <div class="tab-pane fade show active" id="sesi1" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3">Nama Hadiah</th>
                                <th class="border-0 py-3 text-center">Jumlah (Qty)</th>
                                <th class="border-0 py-3 text-center">Kualifikasi Peserta</th>
                                <th class="border-0 py-3 text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sesi1Prizes as $prize)
                                <tr>
                                    <td class="px-4 py-3 fw-bold">{{ $prize->name }}</td>
                                    <td class="py-3 text-center fw-semibold">{{ $prize->quantity }} Unit</td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                            {{ $prize->employee_status == 'ALL' ? 'Semua Karyawan' : ($prize->employee_status == 'PERMANENT' ? 'Khusus Tetap' : 'Khusus Kontrak') }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-end px-4">
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editPrizeModal{{ $prize->id }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form action="{{ route('prizes.destroy', $prize->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus hadiah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                        </form>

                                        <!-- Modal Edit Inline -->
                                        <div class="modal fade text-start" id="editPrizeModal{{ $prize->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom-0">
                                                        <h5 class="modal-title fw-bold">Edit Hadiah</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('prizes.update', $prize->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Nama Hadiah</label>
                                                                <input type="text" name="name" class="form-control" value="{{ $prize->name }}" required>
                                                            </div>
                                                            <div class="row g-3 mb-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Jumlah (Qty)</label>
                                                                    <input type="number" name="quantity" class="form-control" value="{{ $prize->quantity }}" min="1" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Sesi Undian</label>
                                                                    <select name="sesi" class="form-select" required>
                                                                        <option value="1" {{ $prize->sesi == 1 ? 'selected' : '' }}>Sesi 1</option>
                                                                        <option value="2" {{ $prize->sesi == 2 ? 'selected' : '' }}>Sesi 2</option>
                                                                        <option value="3" {{ $prize->sesi == 3 ? 'selected' : '' }}>Sesi 3 (Grandprize)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Kualifikasi Peserta</label>
                                                                <select name="employee_status" class="form-select" required>
                                                                    <option value="ALL" {{ $prize->employee_status == 'ALL' ? 'selected' : '' }}>Semua Karyawan</option>
                                                                    <option value="PERMANENT" {{ $prize->employee_status == 'PERMANENT' ? 'selected' : '' }}>Khusus Karyawan Tetap</option>
                                                                    <option value="CONTRACT" {{ $prize->employee_status == 'CONTRACT' ? 'selected' : '' }}>Khusus Karyawan Kontrak</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data hadiah di Sesi 1.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SESI 2 -->
            <div class="tab-pane fade" id="sesi2" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3">Nama Hadiah</th>
                                <th class="border-0 py-3 text-center">Jumlah (Qty)</th>
                                <th class="border-0 py-3 text-center">Kualifikasi Peserta</th>
                                <th class="border-0 py-3 text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sesi2Prizes as $prize)
                                <tr>
                                    <td class="px-4 py-3 fw-bold">{{ $prize->name }}</td>
                                    <td class="py-3 text-center fw-semibold">{{ $prize->quantity }} Unit</td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                            {{ $prize->employee_status == 'ALL' ? 'Semua Karyawan' : ($prize->employee_status == 'PERMANENT' ? 'Khusus Tetap' : 'Khusus Kontrak') }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-end px-4">
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editPrizeModal{{ $prize->id }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form action="{{ route('prizes.destroy', $prize->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus hadiah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                        </form>

                                        <!-- Modal Edit Inline -->
                                        <div class="modal fade text-start" id="editPrizeModal{{ $prize->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom-0">
                                                        <h5 class="modal-title fw-bold">Edit Hadiah</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('prizes.update', $prize->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Nama Hadiah</label>
                                                                <input type="text" name="name" class="form-control" value="{{ $prize->name }}" required>
                                                            </div>
                                                            <div class="row g-3 mb-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Jumlah (Qty)</label>
                                                                    <input type="number" name="quantity" class="form-control" value="{{ $prize->quantity }}" min="1" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Sesi Undian</label>
                                                                    <select name="sesi" class="form-select" required>
                                                                        <option value="1" {{ $prize->sesi == 1 ? 'selected' : '' }}>Sesi 1</option>
                                                                        <option value="2" {{ $prize->sesi == 2 ? 'selected' : '' }}>Sesi 2</option>
                                                                        <option value="3" {{ $prize->sesi == 3 ? 'selected' : '' }}>Sesi 3 (Grandprize)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Kualifikasi Peserta</label>
                                                                <select name="employee_status" class="form-select" required>
                                                                    <option value="ALL" {{ $prize->employee_status == 'ALL' ? 'selected' : '' }}>Semua Karyawan</option>
                                                                    <option value="PERMANENT" {{ $prize->employee_status == 'PERMANENT' ? 'selected' : '' }}>Khusus Karyawan Tetap</option>
                                                                    <option value="CONTRACT" {{ $prize->employee_status == 'CONTRACT' ? 'selected' : '' }}>Khusus Karyawan Kontrak</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data hadiah di Sesi 2.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SESI 3 -->
            <div class="tab-pane fade" id="sesi3" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3">Nama Hadiah</th>
                                <th class="border-0 py-3 text-center">Jumlah (Qty)</th>
                                <th class="border-0 py-3 text-center">Kualifikasi Peserta</th>
                                <th class="border-0 py-3 text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sesi3Prizes as $prize)
                                <tr class="table-warning">
                                    <td class="px-4 py-3 fw-bold text-danger">{{ $prize->name }}</td>
                                    <td class="py-3 text-center fw-semibold">{{ $prize->quantity }} Unit</td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                            {{ $prize->employee_status == 'ALL' ? 'Semua Karyawan' : ($prize->employee_status == 'PERMANENT' ? 'Khusus Tetap' : 'Khusus Kontrak') }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-end px-4">
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editPrizeModal{{ $prize->id }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form action="{{ route('prizes.destroy', $prize->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus hadiah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                        </form>

                                        <!-- Modal Edit Inline -->
                                        <div class="modal fade text-start" id="editPrizeModal{{ $prize->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom-0">
                                                        <h5 class="modal-title fw-bold">Edit Hadiah</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('prizes.update', $prize->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Nama Hadiah</label>
                                                                <input type="text" name="name" class="form-control" value="{{ $prize->name }}" required>
                                                            </div>
                                                            <div class="row g-3 mb-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Jumlah (Qty)</label>
                                                                    <input type="number" name="quantity" class="form-control" value="{{ $prize->quantity }}" min="1" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Sesi Undian</label>
                                                                    <select name="sesi" class="form-select" required>
                                                                        <option value="1" {{ $prize->sesi == 1 ? 'selected' : '' }}>Sesi 1</option>
                                                                        <option value="2" {{ $prize->sesi == 2 ? 'selected' : '' }}>Sesi 2</option>
                                                                        <option value="3" {{ $prize->sesi == 3 ? 'selected' : '' }}>Sesi 3 (Grandprize)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Kualifikasi Peserta</label>
                                                                <select name="employee_status" class="form-select" required>
                                                                    <option value="ALL" {{ $prize->employee_status == 'ALL' ? 'selected' : '' }}>Semua Karyawan</option>
                                                                    <option value="PERMANENT" {{ $prize->employee_status == 'PERMANENT' ? 'selected' : '' }}>Khusus Karyawan Tetap</option>
                                                                    <option value="CONTRACT" {{ $prize->employee_status == 'CONTRACT' ? 'selected' : '' }}>Khusus Karyawan Kontrak</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data hadiah di Sesi 3.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Tambah Hadiah Baru -->
<div class="modal fade" id="createPrizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Tambah Hadiah Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('prizes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Hadiah</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: TV LED 43 Inch" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah (Qty)</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sesi Undian</label>
                            <select name="sesi" class="form-select" required>
                                <option value="1">Sesi 1</option>
                                <option value="2">Sesi 2</option>
                                <option value="3">Sesi 3 (Grandprize)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kualifikasi Peserta</label>
                        <select name="employee_status" class="form-select" required>
                            <option value="ALL">Semua Karyawan (Kontrak & Tetap)</option>
                            <option value="PERMANENT">Khusus Karyawan Tetap</option>
                            <option value="CONTRACT">Khusus Karyawan Kontrak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Hadiah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.addEventListener('shown.bs.tab', event => {
            document.querySelectorAll('.nav-link').forEach(t => {
                t.classList.remove('text-dark');
                t.classList.add('text-muted');
            });
            event.target.classList.remove('text-muted');
            event.target.classList.add('text-dark');
        });
    });
</script>
@endsection