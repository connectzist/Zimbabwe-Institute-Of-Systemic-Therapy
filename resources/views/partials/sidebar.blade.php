@if(auth()->check())

    {{-- ================= CERTIFICATE ADMIN SIDEBAR ================= --}}
    @if(auth()->user()->role === 'CertificateAdmin')
        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div class="nav_list">

                    <a href="{{ route('dashboard.certdashboard') }}"
                       class="nav_link {{ request()->routeIs('dashboard.certdashboard') ? 'active' : '' }}">
                        <i class='bx bx-home nav_icon'></i> <span class="nav_name">Dashboard</span>
                    </a>

                    <a href="{{ route('certificate.certificate_modules') }}"
                       class="nav_link {{ request()->routeIs('certificate.certificate_modules') ? 'active' : '' }}">
                        <i class='bx bx-book-open nav_icon'></i> <span class="nav_name">Modules</span>
                    </a>

                    <a href="{{ route('certificate.certificate_students') }}"
                       class="nav_link {{ request()->routeIs('certificate.certificate_students') ? 'active' : '' }}">
                        <i class='bx bx-group nav_icon'></i> <span class="nav_name">Students</span>
                    </a>

                    <a href="{{ route('certificate.cert_coursework') }}"
                       class="nav_link {{ request()->routeIs('certificate.cert_coursework') ? 'active' : '' }}">
                        <i class='bx bx-edit nav_icon'></i> <span class="nav_name">Mark Records</span>
                    </a>

                    <a href="{{ route('certificate.cert_results') }}"
                       class="nav_link {{ request()->routeIs('certificate.cert_results') ? 'active' : '' }}">
                        <i class='bx bx-purchase-tag nav_icon'></i> <span class="nav_name">Results</span>
                    </a>
                </div>

                {{-- Logout --}}
                @include('partials.logout-link')
            </nav>
        </div>

    {{-- ================= DIPLOMA ADMIN SIDEBAR ================= --}}
    @elseif(auth()->user()->role === 'DiplomaAdmin')

        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div class="nav_list">

                    <a href="{{ route('dashboard.diplodashboard') }}"
                       class="nav_link {{ request()->routeIs('dashboard.diplodashboard') ? 'active' : '' }}">
                        <i class='bx bx-home nav_icon'></i> <span class="nav_name">Dashboard</span>
                    </a>

                    <a href="{{ route('diploma.diploma_modules') }}"
                       class="nav_link {{ request()->routeIs('diploma.diploma_modules') ? 'active' : '' }}">
                        <i class='bx bx-book-open nav_icon'></i> <span class="nav_name">Modules</span>
                    </a>

                    <a href="{{ route('diploma.diploma_students') }}"
                       class="nav_link {{ request()->routeIs('diploma.diploma_students') ? 'active' : '' }}">
                        <i class='bx bx-group nav_icon'></i> <span class="nav_name">Students</span>
                    </a>

                    <a href="{{ route('diploma.diploma_coursework') }}"
                       class="nav_link {{ request()->routeIs('diploma.diploma_coursework') ? 'active' : '' }}">
                        <i class='bx bx-edit nav_icon'></i> <span class="nav_name">Mark Records</span>
                    </a>

                    <a href="{{ route('diploma.diploma_finalmodule') }}"
                       class="nav_link {{ request()->routeIs('diploma.diploma_finalmodule') ? 'active' : '' }}">
                        <i class='bx bx-edit nav_icon'></i> <span class="nav_name">Final Module</span>
                    </a>

                    <a href="{{ route('diploma.diploma_results') }}"
                       class="nav_link {{ request()->routeIs('diploma.diploma_results') ? 'active' : '' }}">
                        <i class='bx bx-purchase-tag nav_icon'></i> <span class="nav_name">Results</span>
                    </a>
                </div>

                @include('partials.logout-link')
            </nav>
        </div>

    {{-- ================= ADVANCED DIPLOMA ADMIN ================= --}}
    @elseif(auth()->user()->role === 'AdvancedDiplomaAdmin')

        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div class="nav_list">

                    <a href="{{ route('dashboard.advdiplodashboard') }}"
                       class="nav_link {{ request()->routeIs('dashboard.advdiplodashboard') ? 'active' : '' }}">
                        <i class='bx bx-home nav_icon'></i> <span class="nav_name">Dashboard</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_modules') }}"
                       class="nav_link {{ request()->routeIs('advanced_diploma.advanced_modules') ? 'active' : '' }}">
                        <i class='bx bx-book-open nav_icon'></i> <span class="nav_name">Modules</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_students') }}"
                       class="nav_link {{ request()->routeIs('advanced_diploma.advanced_students') ? 'active' : '' }}">
                        <i class='bx bx-group nav_icon'></i> <span class="nav_name">Students</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_coursework') }}"
                       class="nav_link {{ request()->routeIs('advanced_diploma.advanced_coursework') ? 'active' : '' }}">
                        <i class='bx bx-edit nav_icon'></i> <span class="nav_name">Mark Records</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_semester_exams') }}"
                       class="nav_link {{ request()->routeIs('advanced_diploma.advanced_semester_exams') ? 'active' : '' }}">
                        <i class='bx bx-edit nav_icon'></i> <span class="nav_name">Semester Exams</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_results') }}"
                       class="nav_link {{ request()->routeIs('advanced_diploma.advanced_results') ? 'active' : '' }}">
                        <i class='bx bx-purchase-tag nav_icon'></i> <span class="nav_name">Results</span>
                    </a>

                </div>

                @include('partials.logout-link')
            </nav>
        </div>

    @endif

@else
    <p>Please log in to view the sidebar.  
        <a href="{{ route('admin_login') }}">Log in here</a>
    </p>
@endif
