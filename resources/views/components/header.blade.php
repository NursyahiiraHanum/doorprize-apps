    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Voyages Doorprize System</title>
    <link
      rel="shortcut icon"
      href="{{asset('theme/assets/images/logo/icon.svg')}}"
      type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="{{asset('theme/assets/extensions/bootstrap-5/css/bootstrap.min.css')}}" />
    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="{{asset('theme/assets/extensions/bootstrap-icons/font/bootstrap-icons.css')}}" />

    <!-- Main Styles -->
    <link rel="stylesheet" href="{{asset('theme/assets/css/main/app.css')}}" />
    <link
      rel="stylesheet"
      href="{{asset('theme/assets/extensions/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('theme/assets/css/main/dashboard.css')}}" />
    @stack('styles')
    @yield('styles')