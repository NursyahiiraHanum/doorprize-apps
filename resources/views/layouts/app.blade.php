<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- 1. BOOTSTRAP 5 CSS CDN (WAJIB DI DALAM HEAD) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- 2. BOOTSTRAP ICONS CDN (WAJIB DI DALAM HEAD) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @include('components.header')
  </head>

  <body>
    <div id="app">
      @include('components.sidebar')

      <div class="sidebar-overlay"></div>

      <div id="main">
        @include('components.navbar')

        <!-- Page Content -->
        <div id="main-content">
          @yield('content')
        </div>

        @include('components.footer')
      </div>
    </div>

    <!-- JS Scripts (Tetap di bawah body) -->
    <script src="{{asset('theme/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('theme/assets/extensions/bootstrap-5/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('theme/assets/extensions/chart.js/chart.min.js')}}"></script>
    <script src="{{asset('theme/assets/js/main/dashboard.js')}}"></script>

    @yield('scripts')
  </body>
</html>