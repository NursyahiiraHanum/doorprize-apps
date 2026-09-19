@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title text-doordark fw-bold mb-1">
                <i class="bi bi-gear-fill me-2 text-secondary"></i>Sistem Settings
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sistem Settings</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Sidebar Menu Settings -->
    <div class="col-xl-3 col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-2">
                <ul class="nav flex-column nav-pills gap-1" id="settingsMenu" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active w-100 text-start fw-semibold px-3 py-2" id="user-tab" data-bs-toggle="pill" data-bs-target="#users" type="button">
                            <i class="bi bi-people-fill me-2"></i>Manajemen User
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link w-100 text-start fw-semibold px-3 py-2 text-muted" id="event-tab" data-bs-toggle="pill" data-bs-target="#event-config" type="button">
                            <i class="bi bi-calendar-event-fill me-2"></i>Konfigurasi Event
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link w-100 text-start fw-semibold px-3 py-2 text-muted" id="system-tab" data-bs-toggle="pill" data-bs-target="#system-info" type="button">
                            <i class="bi bi-info-circle-fill me-2"></i>Info Sistem
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Role Legend -->
        <div class="card border-0 shadow-sm rounded-3 mt-3">
            <div class="card-body">
                <h6 class="fw-bold text-muted mb-3 small text-uppercase">Keterangan Role</h6>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-danger me-2">Super Admin</span>
                    <small class="text-muted">Akses penuh semua fitur</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2">Admin</span>
                    <small class="text-muted">Kelola data & undian</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-secondary me-2">Operator</span>
                    <small class="text-muted">Hanya scan kehadiran</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="col-xl-9 col-lg-8">
        <div class="tab-content" id="settingsContent">

            <!-- Tab: Manajemen User -->
            <div class="tab-pane fade show active" id="users" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-bold text-doordark mb-0">Manajemen User Sistem</h5>
                            <small class="text-muted">Kelola akun yang bisa mengakses platform ini</small>
                        </div>
                        <button class="btn btn-primary fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                            <i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Nama & Username</th>
                                        <th class="border-0 py-3">Email</th>
                                        <th class="border-0 py-3 text-center">Role</th>
                                        <th class="border-0 py-3 text-center">Status</th>
                                        <th class="border-0 py-3 text-end px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="user-table-body">
                                    <!-- Row Super Admin -->
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <i class="bi bi-person-fill text-danger"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0">Admin Voyages</h6>
                                                    <small class="text-muted">@superadmin</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-muted">admin@voyages.event</td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-danger rounded-pill px-3">Super Admin</span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Aktif</span>
                                        </td>
                                        <td class="py-3 text-end px-4">
                                            <button class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                                            <button class="btn btn-sm btn-outline-secondary ms-1" disabled title="Super Admin tidak bisa dihapus"><i class="bi bi-shield-lock-fill"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Row Admin -->
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0">Panitia Event</h6>
                                                    <small class="text-muted">@panitia01</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-muted">panitia@voyages.event</td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-primary rounded-pill px-3">Admin</span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Aktif</span>
                                        </td>
                                        <td class="py-3 text-end px-4">
                                            <button class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                                            <button class="btn btn-sm btn-outline-danger ms-1 btn-hapus-user" title="Hapus User"><i class="bi bi-trash-fill"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Row Operator -->
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <i class="bi bi-person-fill text-secondary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0">Operator Registrasi</h6>
                                                    <small class="text-muted">@operator01</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-muted">operator@voyages.event</td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-secondary rounded-pill px-3">Operator</span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Non-aktif</span>
                                        </td>
                                        <td class="py-3 text-end px-4">
                                            <button class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                                            <button class="btn btn-sm btn-outline-danger ms-1 btn-hapus-user" title="Hapus User"><i class="bi bi-trash-fill"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Konfigurasi Event -->
            <div class="tab-pane fade" id="event-config" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold text-doordark mb-0">Konfigurasi Event</h5>
                        <small class="text-muted">Atur nama event dan detail penyelenggaraannya</small>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Event</label>
                                <input type="text" class="form-control" value="Voyages Year-End Gathering 2026">
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Pelaksanaan</label>
                                    <input type="date" class="form-control" value="2026-09-02">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Lokasi Event</label>
                                    <input type="text" class="form-control" value="Lembang Park & Zoo, Bandung">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Jumlah Sesi Undian</label>
                                <select class="form-select">
                                    <option value="4" selected>4 Sesi (Sesuai PRD v1.2)</option>
                                    <option value="3">3 Sesi</option>
                                    <option value="2">2 Sesi</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-success fw-bold px-4">
                                <i class="bi bi-save me-2"></i>Simpan Konfigurasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab: Info Sistem -->
            <div class="tab-pane fade" id="system-info" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold text-doordark mb-0">Informasi Sistem</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr><th class="bg-light" style="width:35%">Nama Aplikasi</th><td>Doorprize Management System</td></tr>
                                    <tr><th class="bg-light">Versi</th><td><span class="badge bg-success">v1.2.0 Stable</span></td></tr>
                                    <tr><th class="bg-light">Framework</th><td>Laravel (PHP)</td></tr>
                                    <tr><th class="bg-light">Event</th><td>Voyages Year-End Gathering 2026</td></tr>
                                    <tr><th class="bg-light">Lokasi</th><td>Lembang Park & Zoo, Bandung</td></tr>
                                    <tr><th class="bg-light">Developer</th><td>Voyages Event Management Team</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-warning border-0 mt-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Reset Data:</strong> Fitur reset data (peserta, kehadiran, pemenang) tersedia di bawah. Lakukan hanya setelah event selesai.
                        </div>
                        <button class="btn btn-outline-danger fw-semibold" onclick="return confirm('Yakin ingin mereset semua data?')">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Data Event
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Tambah User Baru -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Akun User Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="form-tambah-user">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="new-name" placeholder="Contoh: Panitia Event 2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control" id="new-username" placeholder="panitia02" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="new-email" placeholder="user@voyages.event" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role Akses <span class="text-danger">*</span></label>
                        <select class="form-select" id="new-role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="Admin">Admin</option>
                            <option value="Operator">Operator</option>
                        </select>
                        <small class="text-muted">Role "Super Admin" tidak bisa dibuat dari sini.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new-password" placeholder="Minimal 8 karakter" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="bi bi-eye-fill" id="pass-eye"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="tambahUser()">
                    <i class="bi bi-save me-2"></i>Buat Akun
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // --- Toggle Password Visibility ---
    function togglePassword() {
        const passInput = document.getElementById('new-password');
        const eyeIcon = document.getElementById('pass-eye');
        if(passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.className = 'bi bi-eye-slash-fill';
        } else {
            passInput.type = 'password';
            eyeIcon.className = 'bi bi-eye-fill';
        }
    }

    // --- Tambah User ke Tabel (Demo / Frontend Only) ---
    function tambahUser() {
        const name     = document.getElementById('new-name').value.trim();
        const username = document.getElementById('new-username').value.trim();
        const email    = document.getElementById('new-email').value.trim();
        const role     = document.getElementById('new-role').value;
        const password = document.getElementById('new-password').value.trim();

        if(!name || !username || !email || !role || !password) {
            alert('Harap isi semua kolom yang wajib diisi (*)');
            return;
        }
        if(password.length < 8) {
            alert('Password minimal 8 karakter!');
            return;
        }

        const badgeMap = {
            'Admin': '<span class="badge bg-primary rounded-pill px-3">Admin</span>',
            'Operator': '<span class="badge bg-secondary rounded-pill px-3">Operator</span>'
        };
        const colorMap = {
            'Admin': 'primary',
            'Operator': 'secondary'
        };

        const tbody = document.getElementById('user-table-body');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-4 py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-${colorMap[role]} bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                        <i class="bi bi-person-fill text-${colorMap[role]}"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">${name}</h6>
                        <small class="text-muted">@${username}</small>
                    </div>
                </div>
            </td>
            <td class="py-3 text-muted">${email}</td>
            <td class="py-3 text-center">${badgeMap[role]}</td>
            <td class="py-3 text-center">
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Aktif</span>
            </td>
            <td class="py-3 text-end px-4">
                <button class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                <button class="btn btn-sm btn-outline-danger ms-1 btn-hapus-user" title="Hapus"><i class="bi bi-trash-fill"></i></button>
            </td>
        `;
        
        // Bind tombol hapus
        tr.querySelector('.btn-hapus-user').addEventListener('click', function() {
            if(confirm('Yakin ingin menghapus akun ini?')) { tr.remove(); }
        });

        tbody.appendChild(tr);
        
        // Tutup modal & reset form
        bootstrap.Modal.getInstance(document.getElementById('modalTambahUser')).hide();
        document.getElementById('form-tambah-user').reset();
        
        alert(`Akun @${username} berhasil ditambahkan!`);
    }

    // --- Bind Hapus untuk Data Default (Sample Rows) ---
    document.querySelectorAll('.btn-hapus-user').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Yakin ingin menghapus akun ini?')) {
                this.closest('tr').remove();
            }
        });
    });
</script>
@endsection
