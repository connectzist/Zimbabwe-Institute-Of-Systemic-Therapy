<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('student/dashboard') }}">
        <!-- Desktop Logo -->
        <img src="{{ asset('images/Connect.logo.jpg') }}" 
             class="logo-desktop" 
             alt="Connect Logo">

        <!-- Mobile Logo -->
        <img src="{{ asset('images/Connect,mobileLogo.png') }}" 
             class="logo-mobile" 
             alt="Mobile Connect Logo">
    </a>
    
    <hr class="sidebar-divider my-0">
    <hr class="sidebar-divider">
    
    <!-- Dashboard Item -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('student/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    
    <!-- Financial Statement Item -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('student/statement') }}">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>Financial Statement</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    
    <!-- Academic Results Item -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('student/results') }}">
            <i class="fas fa-fw fa-book-open"></i>
            <span>Academic Results</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    
    <!-- Registration Item -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('student/registration') }}">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Registration</span>
        </a>
    </li>
    <hr class="sidebar-divider">

    <!-- Notices Item -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('student/notices') }}">
            <i class="fas fa-fw fa-bell"></i>
            <span>Notices</span>
        </a>
    </li>
    <hr class="sidebar-divider">

    <!-- Timetable Item -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('student/timetable') }}">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Timetable</span>
        </a>
    </li>
    <hr class="sidebar-divider">

    <!-- E-Counselling Item -->
    {{-- <li class="nav-item">
        <a class="nav-link" href="{{ url('student/e_counselling') }}">
            <i class="fas fa-fw fa-comments"></i>
            <span>E-Counselling</span>
        </a>
    </li> --}}
    {{-- <hr class="sidebar-divider"> --}}

    <!-- E-Library Item -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('student/e_library') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>E-Library</span>
        </a>
    </li>
    <hr class="sidebar-divider">

    <!-- Online Classes Item -->
    {{-- <li class="nav-item">
        <a class="nav-link" href="{{ url('student/online-classes') }}">
            <i class="fas fa-fw fa-video"></i>
            <span>Online Classes</span>
        </a>
    </li>
    <hr class="sidebar-divider"> --}}

</ul>
