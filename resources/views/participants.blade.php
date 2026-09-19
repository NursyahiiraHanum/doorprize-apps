@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="page-title text-doordark fw-bold mb-1">Data Peserta</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data Peserta</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <button class="btn btn-success fw-semibold" data-bs-toggle="modal" style="margin-left:10px;" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-excel me-2"></i>Import Data
            </button>

            <!-- Tombol Generate QR -->
            <form action="{{ route('participants.generate-qr') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary fw-semibold">
                    <i class="bi bi-qr-code me-2"></i>Generate QR
                </button>
            </form>

            <!-- Tombol Export ZIP QR (Fitur Baru) -->
            <a href="{{ route('participants.export-qr') }}" class="btn btn-primary fw-semibold">
                <i class="bi bi-file-earmark-zip me-2"></i>Export ZIP QR
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold text-doordark">Daftar Peserta</h5>
                
                <!-- Form Pencarian -->
                <form action="{{ route('participants.index') }}" method="GET" class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 bg-light" placeholder="Cari nama atau NPK..." value="{{ request('search') }}">
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3">NPK</th>
                                <th class="border-0 py-3">Nama Lengkap</th>
                                <th class="border-0 py-3">Tanggungan</th>
                                <th class="border-0 py-3">Status</th>
                                <th class="border-0 py-3 text-center">Tiket Zoo</th>
                                <th class="border-0 py-3">Kendaraan</th>
                                <th class="border-0 py-3 text-center">Riwayat Hadiah</th>
                                <th class="border-0 px-4 py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($participants as $participant)
                            <tr>
                                <td class="px-4 py-3 fw-bold text-primary">
                                    {{ $participant->npk }}
                                    <br>
                                    @if(!empty($participant->qr_code))
                                        <span class="badge bg-success bg-opacity-10 text-success fw-normal" style="font-size: 10px;">
                                            <i class="bi bi-check-circle-fill me-1"></i>QR Ready
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger fw-normal" style="font-size: 10px;">
                                            <i class="bi bi-x-circle-fill me-1"></i>Belum Ready QR
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold">{{ $participant->name }}</span>
                                </td>
                                <td class="py-3">
                                    {{ $participant->tanggungan }}
                                </td>
                                <td class="py-3">
                                    @if(in_array(strtoupper($participant->status_karyawan), ['PERMANENT', 'TETAP']))
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $participant->status_karyawan }}</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning">{{ $participant->status_karyawan }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">{{ $participant->total_tiket }}</td>
                                <td class="py-3">{{ $participant->kendaraan ?? '-' }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge {{ $participant->tahun_terakhir_menang ? 'bg-secondary' : 'text-muted' }}">
                                        {{ $participant->tahun_terakhir_menang ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    @if(!empty($participant->qr_code))
                                        <a href="{{ route('participants.print-qr', $participant->id) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Cetak QR Code">
                                            <i class="bi bi-qr-code-scan"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="QR Code Belum Ada">
                                            <i class="bi bi-qr-code-scan"></i>
                                        </button>
                                    @endif

                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal-{{ $participant->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form action="{{ route('participants.destroy', $participant->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Data -->
                            <div class="modal fade" id="editModal-{{ $participant->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('participants.update', $participant->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold">Edit Karyawan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label">NPK</label>
                                                    <input type="text" name="npk" class="form-control" value="{{ $participant->npk }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Lengkap</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $participant->name }}" required>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label">Tanggungan</label>
                                                        <input type="number" name="tanggungan" class="form-control" value="{{ $participant->tanggungan }}" min="0" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label">Total Tiket</label>
                                                        <input type="number" name="total_tiket" class="form-control" value="{{ $participant->total_tiket }}" min="1" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label">Status Karyawan</label>
                                                        <select name="status_karyawan" class="form-select" required>
                                                            <option value="TETAP" {{ $participant->status_karyawan == 'TETAP' ? 'selected' : '' }}>TETAP</option>
                                                            <option value="KONTRAK" {{ $participant->status_karyawan == 'KONTRAK' ? 'selected' : '' }}>KONTRAK</option>
                                                            <option value="PERMANENT" {{ $participant->status_karyawan == 'PERMANENT' ? 'selected' : '' }}>PERMANENT</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label">Gender</label>
                                                        <select name="gender" class="form-select" required>
                                                            <option value="MALE" {{ $participant->gender == 'MALE' ? 'selected' : '' }}>MALE</option>
                                                            <option value="FEMALE" {{ $participant->gender == 'FEMALE' ? 'selected' : '' }}>FEMALE</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label">Kendaraan</label>
                                                        <select name="kendaraan" class="form-select">
                                                            <option value="" {{ empty($participant->kendaraan) ? 'selected' : '' }}>- Belum Dipilih -</option>
                                                            <option value="BIS" {{ $participant->kendaraan == 'BIS' ? 'selected' : '' }}>BIS</option>
                                                            <option value="MOBIL PRIBADI" {{ $participant->kendaraan == 'MOBIL PRIBADI' ? 'selected' : '' }}>MOBIL PRIBADI</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label">Tahun Menang</label>
                                                        <input type="number" name="tahun_terakhir_menang" class="form-control" placeholder="Contoh: 2024" value="{{ $participant->tahun_terakhir_menang }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success text-white">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Belum ada data peserta. Silakan import atau tambah manual.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        Menampilkan {{ $participants->firstItem() ?? 0 }} hingga {{ $participants->lastItem() ?? 0 }} dari {{ $participants->total() }} data
                    </span>
                    <div class="mb-0">
                        {{ $participants->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Data -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('participants.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="importModalLabel">Import Data Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File (Excel)</label>
                        <input class="form-control" type="file" id="file" name="file" accept=".xlsx, .xls, .csv" required>
                        <div class="form-text">Pastikan file berisi header: NPK, NAME, TANGGUNGAN, TOTAL, STATUS, GENDER.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success text-white">Import Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Additional scripts for page init data peserta
</script>
@endsection