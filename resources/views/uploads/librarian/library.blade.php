@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
            <h4 class="mb-0 text-primary">Library</h4>
            <div>
                <a href="{{ route('library.newBook') }}" class="btn btn-outline-primary me-2">Upload Book</a>
                <a href="{{ route('library.newJournal') }}" class="btn btn-outline-success me-2">Upload Journal</a>
                <a href="{{ route('library.newPastExamPaper') }}" class="btn btn-outline-info">Upload Exam Papers</a>
                
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-center mt-4 mb-4">
                <div class="col-md-6">
                    <div class="form-group text-center">
                        <label for="library_option"><strong>Select what to show:</strong></label>
                        <select id="library_option" name="library_option" class="form-control text-center">
                            <option value="">-- Select Option --</option>
                            <option value="books">Books</option>
                            <option value="research">Research</option>
                            <option value="journals">Journals</option>
                            <option value="exams">Past Exam Papers</option>
                        </select>
                    </div>
                </div>
            </div>            

            <div id="info-message" class="mt-3 text-muted" style="display: none;">
                Nothing to show or selected option is not available.
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('uploads.view') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('library_option').addEventListener('change', function () {
        const selected = this.value;
        const info = document.getElementById('info-message');

        if (selected === 'books') {
            window.location.href = "{{ route('library.all.books') }}";
        } else if (selected === 'journals') {
            window.location.href = "{{ route('library.journals') }}";
        }else if (selected === 'research') {
            window.location.href = "{{ route('research.view') }}";
        } else if (selected === 'exams') {
            window.location.href = "{{ route('library.past_exam_papers') }}";
        } else {
            info.style.display = 'block';
        }
    });
</script>
<script src="{{ asset('js/hideError.js') }}"></script>