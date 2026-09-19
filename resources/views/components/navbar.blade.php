<header class="navbar-custom">
  <button class="burger-btn d-block"><i class="bi bi-list"></i></button>
  <div class="navbar-right">
    <div class="dropdown">
      <div class="user-dropdown" data-bs-toggle="dropdown">
        <div class="user-info text-end me-2 d-none d-md-block">
          {{-- Nama User --}}
          <div class="user-name fw-bold">{{ Auth::user()->name }}</div>
          
          {{-- Role User --}}
          <div class="user-role text-primary">
            @if(Auth::user()->role === 'super_admin')
              Super Admin
            @elseif(Auth::user()->role === 'admin')
              Admin / Event Coordinator
            @else
              Operator Absensi
            @endif
          </div>
        </div>
        
        {{-- Avatar Dinamis Berdasarkan Nama --}}
        <img
          src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff"
          alt="{{ Auth::user()->name }}"
          class="user-avatar border border-2 border-primary" />
      </div>
      <!-- <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
        <li>
          <a class="dropdown-item" href="#"
            ><i class="bi bi-person me-2"></i> Profil</a>
        </li>
        <li>
          <a class="dropdown-item" href="#"
            ><i class="bi bi-gear me-2"></i> Pengaturan</a>
        </li>
        <li>
          <hr class="dropdown-divider" />
        </li>
        <li>
          <a class="dropdown-item text-danger" href="auth-login.html"
            ><i class="bi bi-box-arrow-right me-2"></i> Keluar</a>
        </li>
      </ul> -->
    </div>
  </div>
</header>
