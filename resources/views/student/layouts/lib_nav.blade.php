    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Library</h4>
            </div>
        </div>
    </div>

    <div style="
        margin-top: 25px;
        background-image: url('https://images.unsplash.com/photo-1600431521340-491eca880813?q=80&w=1738&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        padding: 40px 20px;
        color: white;
    ">
    
    <nav class="main-nav">
        <ul>
            <li><a href="{{ route('student.e_library') }}">Home</a></li>
            <li>
                <a href="#">Catalog</a>
                <ul>
                    <li><a href="{{ route('student.search_book') }}">Search Books</a></li>
                    <li><a href="{{ route('library.categories') }}">Browse Categories</a></li>
                    <li><a href="{{ route('library.e_journals') }}">E Journals</a></li>
                </ul>
            </li>
            <li><a href="{{ route('student.past_exam_paper') }}">Past Exam Papers</a></li>
            <li><a href="{{ route('student.researches') }}">Researches</a></li>
            <li><a href="{{ route('student.lab_rules') }}">Rules & Regulations</a></li>
            <li><a href="{{ route('zist.contact_us') }}">Contact Us</a></li>
        </ul>
    </nav>