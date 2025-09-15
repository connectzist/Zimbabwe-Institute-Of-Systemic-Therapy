@if(auth()->check())
    @if(auth()->user()->role === 'CertificateAdmin')
        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div>
                    <div class="nav_list">
                        <a href="{{ route('dashboard.certdashboard') }}" class="nav_link active">
                            <i class='bx bx-home nav_icon'></i>
                            <span class="nav_name">Dashboard</span>
                        </a>

                        <a href="{{ route('certificate.certificate_modules') }}" class="nav_link">
                            <i class='bx bx-book-open nav_icon'></i>
                            <span class="nav_name">Modules</span>
                        </a>

                        <a href="{{ route('certificate.certificate_students') }}" class="nav_link">
                            <i class='bx bx-group nav_icon'></i>
                            <span class="nav_name">Students</span>
                        </a>

                        <a href="{{ route('certificate.cert_coursework') }}" class="nav_link">
                            <i class='bx bx-edit nav_icon'></i>
                            <span class="nav_name">Mark Records</span>
                        </a>

                        <a href="{{ route('certificate.cert_results') }}" class="nav_link">
                            <i class='bx bx-purchase-tag nav_icon'></i>
                            <span class="nav_name">Results</span>
                        </a>
                    </div>
                </div>

                <!-- Logout -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="logout" class="nav_link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out nav_icon'></i>
                    <span class="nav_name">LogOut</span>
                </a>
            </nav>
        </div>
    @elseif(auth()->user()->role === 'DiplomaAdmin')
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <div class="nav_list">
                    <a href="{{ route('dashboard.diplodashboard') }}" class="nav_link active">
                        <i class='bx bx-home nav_icon'></i>
                        <span class="nav_name">Dashboard</span>
                    </a>

                    <a href="{{ route('diploma.diploma_modules') }}" class="nav_link">
                        <i class='bx bx-book-open nav_icon'></i>
                        <span class="nav_name">Modules</span>
                    </a>

                    <a href="{{ route('diploma.diploma_students') }}" class="nav_link">
                        <i class='bx bx-group nav_icon'></i>
                        <span class="nav_name">Students</span>
                    </a>

                    <a href="{{ route('diploma.diploma_coursework') }}" class="nav_link">
                        <i class='bx bx-edit nav_icon'></i>
                        <span class="nav_name">Mark Records</span>
                    </a>

                    <a href="{{ route('diploma.diploma_finalmodule') }}" class="nav_link">
                        <i class='bx bx-edit nav_icon'></i>
                        <span class="nav_name">Final Module</span>
                    </a>

                    <a href="{{ route('diploma.diploma_results') }}" class="nav_link">
                        <i class='bx bx-purchase-tag nav_icon'></i>
                        <span class="nav_name">Results</span>
                    </a>
                </div>
            </div>

                <!-- Logout -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="logout" class="nav_link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out nav_icon'></i>
                    <span class="nav_name">LogOut</span>
                </a>
            </nav>
        </div>
    @elseif(auth()->user()->role === 'AdvancedDiplomaAdmin')
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <div class="nav_list">
                    <a href="{{ route('dashboard.advdiplodashboard') }}" class="nav_link active">
                        <i class='bx bx-home nav_icon'></i>
                        <span class="nav_name">Dashboard</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_modules') }}" class="nav_link">
                        <i class='bx bx-book-open nav_icon'></i>
                        <span class="nav_name">Modules</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_students') }}" class="nav_link">
                        <i class='bx bx-group nav_icon'></i>
                        <span class="nav_name">Students</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_coursework') }}" class="nav_link">
                        <i class='bx bx-edit nav_icon'></i>
                        <span class="nav_name">Mark Records</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_semester_exams') }}" class="nav_link">
                        <i class='bx bx-edit nav_icon'></i>
                        <span class="nav_name">Semester Exams</span>
                    </a>

                    <a href="{{ route('advanced_diploma.advanced_results') }}" class="nav_link">
                        <i class='bx bx-purchase-tag nav_icon'></i>
                        <span class="nav_name">Results</span>
                    </a>
                </div>
            </div>
            
                <!-- Logout -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="logout" class="nav_link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out nav_icon'></i>
                    <span class="nav_name">LogOut</span>
                </a>
            </nav>
        </div>
    @endif
    @else
    <p>Please log in to view the sidebar. <a href="{{ route('admin_login') }}">Log in here</a></p>
@endif

