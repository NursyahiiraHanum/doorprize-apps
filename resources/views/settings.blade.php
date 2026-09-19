@extends('layouts.app')

@section('content')
<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-dark mb-1">Pengaturan Sistem & Akun</h3>
            <p class="text-muted small mb-0">Kelola profil pengguna, kata sandi, dan manajemen akses sistem</p>
        </div>
        <div>
            <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill">
                <i class="bi bi-shield-lock-fill me-1"></i> Role: {{ strtoupper(Auth::user()->role ?? 'USER') }}
            </span>
        </div>
    </div>
</div>

<!-- Alert Notifications -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-octagon-fill me-2"></i> {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Kolom Kiri: Profil & Ganti Password -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-person-gear me-2 text-primary"></i>Pengaturan Akun Anda
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-key me-1 text-warning"></i> Ganti Kata Sandi (Opsional)
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Isi jika ingin merubah password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Minimal 6 karakter">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ketik ulang password baru">
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Manajemen Pengguna (Khusus Super Admin) -->
    @if(Auth::user()->role === 'super_admin')
    <div class="col-lg-6">
        <!-- Form Tambah User -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-person-plus-fill me-2 text-success"></i>Tambah Pengguna Baru
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Nama User</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Operator/Admin" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@voyages.com" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">Hak Akses / Role</label>
                        <select name="role" class="form-select" required>
                            <option value="user">User (Hanya Akses Daftar Kehadiran)</option>
                            <option value="admin">Admin (Akses Mesin Doorprize & Hadiah)</option>
                            <option value="super_admin">Super Admin (Akses Penuh Seluruh Sistem)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success fw-bold px-4 rounded-3 w-100">
                        <i class="bi bi-plus-circle me-1"></i> Tambahkan User
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Tabel Daftar Pengguna Sistem (Daftar User) -->
@if(Auth::user()->role === 'super_admin')
<div class="row mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-people-fill me-2 text-info"></i>Daftar Pengguna Sistem (Operator & Admin)
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3 py-3">Nama Pengguna</th>
                                <th class="py-3">Email</th>
                                <th class="py-3">Role / Akses</th>
                                <th class="py-3">Tanggal Dibuat</th>
                                <th class="py-3 text-end px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $userItem)
                                <tr>
                                    <td class="px-3 py-3 fw-bold text-dark">
                                        <i class="bi bi-person-circle me-2 text-secondary"></i>{{ $userItem->name }}
                                        @if($userItem->id === Auth::id())
                                            <span class="badge bg-soft-primary text-primary ms-1">(Anda)</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-secondary">{{ $userItem->email }}</td>
                                    <td class="py-3">
                                        @if($userItem->role === 'super_admin')
                                            <span class="badge bg-danger">SUPER ADMIN</span>
                                        @elseif($userItem->role === 'admin')
                                            <span class="badge bg-primary">ADMIN</span>
                                        @else
                                            <span class="badge bg-secondary">USER (OPERATOR)</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-muted small">
                                        {{ $userItem->created_at ? $userItem->created_at->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="py-3 text-end px-3">
                                        @if($userItem->id !== Auth::id())
                                            <form action="{{ route('users.destroy', $userItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah anda yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
