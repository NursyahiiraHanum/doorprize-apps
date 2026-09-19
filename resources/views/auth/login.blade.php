<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System | Voyages Event & Doorprize</title>
    <link rel="shortcut icon" href="{{ asset('theme/assets/images/logo/logo-voyages.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('theme/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/css/pages/auth-login.css') }}">

    <style>
        :root {
            --gd-navy: #0E1F4D;
            --gd-blue: #1E3A8A;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
        }

        .auth-sidebar {
            background: linear-gradient(135deg, #0E1F4D 0%, #1E3A8A 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-sidebar::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            pointer-events: none;
        }

        .login-logo {
            max-height: 150px;
            justify: center;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
        }

        .login-logo-mobile {
            max-height: 70px;
            width: auto;
        }

        .btn-voyages {
            background: linear-gradient(135deg, #0E1F4D 0%, #1E3A8A 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(14, 31, 77, 0.25);
        }

        .btn-voyages:hover {
            background: linear-gradient(135deg, #162a63 0%, #2546a3 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(14, 31, 77, 0.35);
        }

        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .input-group-text {
            border-color: #cbd5e1;
        }

        .form-control {
            border-color: #cbd5e1;
            padding: 10px 14px;
        }

        .form-control:focus {
            border-color: #1E3A8A;
            box-shadow: 0 0 0 0.25rem rgba(30, 58, 138, 0.15);
        }

        .badge-event {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0" id="auth-login">
        <div class="row g-0 min-vh-100">
            <!-- Left Side: Branding Voyages -->
            <div class="col-lg-6 col-12 d-none d-lg-flex flex-column justify-content-between p-5 auth-sidebar">
                <div>
                    <span class="badge badge-event px-3 py-2 rounded-pill">
                        <i class="bi bi-ticket-perforated me-1"></i> Sistem Event & Doorprize
                    </span>
                </div>

                <div class="login-branding-text my-auto py-5">
                    <div class="mb-4">
                        <img src="{{ asset('theme/assets/images/logo/logo-voyages.png') }}" alt="Voyages Logo" class="login-logo mb-3">
                    </div>
                    <h1 class="display-5 fw-bold text-white mb-3">
                         EVENT MANAGEMENT
                    </h1>
                    <p class="lead text-white-50">
                        Solusi sistem doorprize & presensi event otomatis, cepat, presisi, dan terintegrasi secara profesional.
                    </p>
                </div>

                <div class="text-white-50 small">
                    &copy; 2026 Voyages Event Organizer. All rights reserved.
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-lg-6 col-12 d-flex align-items-center justify-content-center bg-light p-4 p-md-5">
                <div class="auth-form-wrapper w-100" style="max-width: 420px;">
                    
                    <!-- Mobile Logo (Visible on mobile screens) -->
                    <div class="text-center d-lg-none mb-4">
                        <img src="{{ asset('theme/assets/images/logo/logo-voyages.png') }}" alt="Voyages Logo" class="login-logo-mobile mb-2">
                        <h5 class="fw-bold text-dark mb-0">VOYAGES EVENT</h5>
                        <small class="text-muted">Sistem Management & Doorprize</small>
                    </div>

                    <div class="card card-custom p-4 p-md-4 bg-white">
                        <div class="text-start mb-2">
                            <h3 class="fw-bold text-dark mb-1">LOGIN</h3>
                            <p class="text-muted small">Welcome back Voyages Team! Please enter your details.</p>
                        </div>

                        <!-- Alert Notifications -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show text-sm py-2 px-3 mb-3" role="alert">
                                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show text-sm py-2 px-3 mb-3" role="alert">
                                <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('login.perform') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="email" class="form-label fw-semibold text-secondary small">Email / Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="" placeholder="admin@voyages.com" required autofocus>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password" class="form-label fw-semibold text-secondary small mb-0">Password</label>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        value="" placeholder="••••••••" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked>
                                <label class="form-check-label text-muted small" for="rememberMe">
                                    Ingat Saya di Perangkat Ini
                                </label>
                            </div>

                            <button type="submit" class="btn btn-voyages w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </button>
                        </form>

                        <!-- <div class="p-3 bg-light rounded-3 text-center border mt-2">
                            <small class="text-muted d-block">Default Login Admin:</small>
                            <span class="badge bg-dark text-white font-monospace mt-1">admin@voyages.com</span>
                            <span class="badge bg-secondary text-white font-monospace mt-1">admin123</span>
                        </div> -->
                    </div>

                    <div class="mt-4 text-center text-muted small">
                        Supported by <strong>Voyages Event Organizer</strong>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });
    </script>
</body>

</html>
