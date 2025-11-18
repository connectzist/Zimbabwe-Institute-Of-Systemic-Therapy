<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Super Admin Dashboard</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Boxicons -->
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />

</head>

<body id="body-pd">

  <!-- Overlay -->
  <div class="overlay" id="overlay"></div>

  <!-- Header -->
  <header class="header" id="header">
    <i class="bx bx-menu" id="header-toggle"></i>
    <div class="header_img">
      <img src="https://connect.org.zw/wp-content/uploads/2024/06/Remove-background-project-1-150x150.png" alt="User Image" />
    </div>
  </header>

  <!-- Sidebar Navigation -->
  <div class="l-navbar" id="nav-bar">
    <nav class="nav">
      <div class="nav_list">
        <a href="{{ route('dashboard.dashboard') }}" class="nav_link {{ request()->routeIs('dashboard.dashboard') ? 'active' : '' }}">
          <i class="bx bx-grid-alt nav_icon"></i><span class="nav_name">Dashboard</span>
        </a>

        <a href="{{ route('dashboard.users') }}" class="nav_link {{ request()->routeIs('dashboard.users') ? 'active' : '' }}">
          <i class="bx bx-user nav_icon"></i><span class="nav_name">Users</span>
        </a>

        <a href="{{ route('courses.index') }}" class="nav_link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
          <i class="bx bx-folder nav_icon"></i><span class="nav_name">Courses</span>
        </a>

        <a href="{{ route('course_modules.course_modules') }}" class="nav_link {{ request()->routeIs('course_modules.*') ? 'active' : '' }}">
          <i class="bx bx-folder-open nav_icon"></i><span class="nav_name">Module Topics</span>
        </a>

        <a href="{{ route('fees.index') }}" class="nav_link {{ request()->routeIs('fees.*') ? 'active' : '' }}">
          <i class="bx bx-dollar nav_icon"></i><span class="nav_name">Manage Fees</span>
        </a>

        <a href="{{ route('students.index') }}" class="nav_link {{ request()->routeIs('students.*') ? 'active' : '' }}">
          <i class="bx bx-group nav_icon"></i><span class="nav_name">Students</span>
        </a>

        <a href="{{ route('uploads.view') }}" class="nav_link {{ request()->routeIs('uploads.*') ? 'active' : '' }}">
          <i class="bx bx-upload nav_icon"></i><span class="nav_name">Uploads</span>
        </a>

        <a href="{{ route('registration.registered_students') }}" class="nav_link {{ request()->routeIs('registration.*') ? 'active' : '' }}">
          <i class="bx bx-folder nav_icon"></i><span class="nav_name">Registration</span>
        </a>

        <a href="{{ route('students_results.students_results') }}" class="nav_link {{ request()->routeIs('students_results.*') ? 'active' : '' }}">
          <i class="bx bx-trophy nav_icon"></i><span class="nav_name">Results</span>
        </a>

        <a href="{{ route('transcripts.view') }}" class="nav_link {{ request()->routeIs('transcripts.*') ? 'active' : '' }}">
          <i class="bx bx-file nav_icon"></i><span class="nav_name">Transcripts</span>
        </a>
      </div>

      <!-- Logout -->
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
      <a href="logout" class="nav_link mt-auto" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bx bx-log-out nav_icon"></i><span class="nav_name">LogOut</span>
      </a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="row">
        
      </div>
    </div>
  </div>


  <!-- Footer -->
  <footer class="footer">
    <p class="mb-0">© CONNECT ZIST - All Rights Reserved</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Sidebar Toggle JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggle = document.getElementById('header‑toggle');
      const sidebar = document.getElementById('nav-bar');
      const main = document.querySelector('.main-content');
      const overlay = document.getElementById('overlay');
      const body = document.body;

      toggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        main.classList.toggle('shifted');
        const isOpen = sidebar.classList.contains('show');
        overlay.style.display = isOpen ? 'block' : 'none';
        body.style.overflow = isOpen ? 'hidden' : '';
      });

      overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        main.classList.remove('shifted');
        overlay.style.display = 'none';
        body.style.overflow = '';
      });

      document.querySelectorAll('.nav_link').forEach(link => {
        link.addEventListener('click', () => {
          if (window.innerWidth <= 768) {
            sidebar.classList.remove('show');
            main.classList.remove('shifted');
            overlay.style.display = 'none';
            body.style.overflow = '';
          }
        });
      });

      window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
          sidebar.classList.remove('show');
          main.classList.remove('shifted');
          overlay.style.display = 'none';
          body.style.overflow = '';
        }
      });
    });
  </script>

  <script src="{{ asset('js/admin.js') }}"></script>

</body>
</html>
