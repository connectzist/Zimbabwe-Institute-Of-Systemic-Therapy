<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title')</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Boxicons CSS -->
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

  <!-- FontAwesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <!-- Custom Style -->
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>
<body id="body-pd">

  <!-- Header -->
  <header class="header" id="header">
    <div class="header_toggle">
      <i class='bx bx-menu' id="header-toggle"></i>
    </div>
    <div class="header_img">
      <img src="https://connect.org.zw/wp-content/uploads/2024/06/Remove-background-project-1-150x150.png" alt="User Image" />
    </div>
  </header>

  <!-- Sidebar -->
  <div>
    @include('partials.sidebar')
  </div>

  <!-- Main Content -->
  <div class="content">
    @yield('content')
  </div>

  <!-- Footer -->
  <footer class="footer text-center mt-4">
    <div class="container">
      <p class="mb-0">© CONNECT ZIST - All Rights Reserved</p>
    </div>
  </footer>

  <!-- jQuery  -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap 5 JS Bundle (Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
