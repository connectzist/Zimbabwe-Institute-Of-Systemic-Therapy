@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Search for Student Transcript</h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('transcripts.search') }}">
        @csrf
        <div class="form-group">
            <label for="search">Search</label>
            <input type="text" class="form-control" id="search" name="search" placeholder="Enter student name, registration number, or course" value="{{ request('search') }}">
        </div>
        <div class="form-group">
            <label for="type">Search By</label>
            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="">Select a criterion</option>
                <option value="first_name" {{ request('type') == 'first_name' ? 'selected' : '' }}>First Name</option>
                <option value="last_name" {{ request('type') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                <option value="candidate_number" {{ request('type') == 'candidate_number' ? 'selected' : '' }}>Registration Number</option>
                <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="course" {{ request('type') == 'course' ? 'selected' : '' }}>Course</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-3">Search</button>
    </form>

    <hr>

        <!-- Error Section -->
        @if ($errors->has('error'))
        <div class="alert alert-danger" role="alert" style="margin-left: auto; margin-right: auto; width: 50%; padding: 2px 5px; border-radius: 5px;">
            <ul>
                <li>{{ $errors->first('error') }}</li>
            </ul>
        </div>
    @endif
    
    <!-- Search results display-->
    @if (isset($students))
        <h3>Students Transcript Retrieved</h3>
        @if($students->isEmpty())
            <p>No students found.</p>
        @else
            <ul class="list-group">
                @foreach ($students as $student)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $student->first_name }} {{ $student->last_name }} - {{ $student->candidate_number }}</span>
                        <div class="ml-auto">
                            <a href="{{ route('transcripts.pdf', $student->id) }}" class="btn btn-success btn-sm">Preview</a>
                            <button onclick="printTranscript({{ $student->id }})" class="btn btn-info btn-sm">Print</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</div>

<script>
    function printTranscript(student_id) {
        var printWindow = window.open("{{ route('transcripts.pdf', '') }}/" + student_id, '_blank');
    }
</script>
<script src="{{ asset('js/hideError.js') }}"></script>