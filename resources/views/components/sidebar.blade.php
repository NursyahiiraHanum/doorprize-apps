<div id="sidebar" class="active">
  <div class="sidebar-header">
    <a href="{{ url('/') }}">
      <img
        src="{{asset('theme/assets/images/logo/logo-voyages.png')}}"
        alt="Logo"
        class="sidebar-logo" />
    </a>
    
    <div class="sidebar-toggler d-lg-none ms-auto" id="sidebar-close">
      <i class="bi bi-x fs-2"></i>
    </div>
  </div>

  <div class="sidebar-menu">
    <ul class="list-unstyled">
      <li class="menu-header">Menu Utama</li>
      <li class="sidebar-item {{ request()->is('/') ? 'active' : '' }}">
        <a href="{{ url('/') }}" class="sidebar-link">
          <i class="bi bi-speedometer2"></i>
          <span>Dashboard</span>
        </a>
      </li>

      {{-- Data Peserta: Super Admin saja --}}
      @if(auth()->user()->role === 'super_admin')
      <li class="sidebar-item {{ request()->is('participants*') ? 'active' : '' }}">
        <a href="{{ url('/participants') }}" class="sidebar-link">
          <i class="bi bi-people-fill"></i>
          <span>Data Peserta</span>
        </a>
      </li>
      @endif

      {{-- Daftar Kehadiran: Semua Role --}}
      <li class="sidebar-item {{ request()->is('attendance*') ? 'active' : '' }}">
        <a href="{{ url('/attendance') }}" class="sidebar-link">
          <i class="bi bi-person-check-fill"></i>
          <span>Daftar Kehadiran</span>
        </a>
      </li>

      {{-- Daftar Hadiah: Super Admin & Admin --}}
      @if(in_array(auth()->user()->role ?? '', ['super_admin', 'admin']))
      <li class="sidebar-item {{ request()->is('prizes*') ? 'active' : '' }}">
        <a href="{{ url('/prizes') }}" class="sidebar-link">
          <i class="bi bi-gift-fill"></i>
          <span>Daftar Hadiah</span>
        </a>
      </li>
      @endif

      {{-- Mesin Doorprize: Admin & Super Admin --}}
      @if(in_array(auth()->user()->role ?? '', ['super_admin', 'admin']))
      <li class="sidebar-item {{ request()->is('doorprize*') ? 'active' : '' }}">
        <a href="{{ url('/doorprize') }}" class="sidebar-link">
          <i class="bi bi-dice-5-fill"></i>
          <span>Mesin Doorprize</span>
        </a>
      </li>
      @endif

      {{-- Laporan Section: Super Admin & Admin --}}
      @if(in_array(auth()->user()->role ?? '', ['super_admin', 'admin']))
      <li class="menu-header">Laporan</li>
      <li class="sidebar-item {{ request()->is('winners*') ? 'active' : '' }}">
        <a href="{{ url('/winners') }}" class="sidebar-link">
          <i class="bi bi-trophy-fill"></i>
          <span>History Winners</span>
        </a>
      </li>
      @endif

      {{-- Settings: Super Admin saja --}}
      @if(auth()->user()->role === 'super_admin')
      <li class="menu-header">Pengaturan</li>
      <li class="sidebar-item {{ request()->is('settings*') ? 'active' : '' }}">
        <a href="{{ url('/settings') }}" class="sidebar-link"
          ><i class="bi bi-sliders"></i><span>Sistem Settings</span></a>
      </li>
      @endif

      {{-- Logout: Semua Role --}}
      <li class="menu-header">Akun</li>
      <li class="sidebar-item">
        <a href="{{ route('logout') }}" class="sidebar-link text-danger"
          ><i class="bi bi-power"></i><span>Logout</span></a>
      </li>
    </ul>
  </div>
</div>

