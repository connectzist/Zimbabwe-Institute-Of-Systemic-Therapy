@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Upload Research</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('uploads.research.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
            
                <!-- Research Title -->
                <div class="form-group">
                    <label for="research_title" class="fw-bold">Research Title</label>
                    <input type="text" id="research_title" name="research_title" class="form-control" value="{{ old('research_title') }}" required>
                    @error('research_title')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            
                <!-- Researcher -->
                <div class="form-group">
                    <label for="researcher" class="fw-bold">Researcher</label>
                    <input type="text" id="researcher" name="researcher" class="form-control" value="{{ old('researcher') }}" required>
                    @error('researcher')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            
                <!-- Year of Research -->
                <div class="form-group">
                    <label for="year_of_research" class="fw-bold">Year of Research</label>
                    <input type="number" id="year_of_research" name="year_of_research" class="form-control" value="{{ old('year_of_research') }}" required>
                    @error('year_of_research')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            
                <!-- Department -->
                <div class="form-group">
                    <label for="student_type" class="fw-bold">Department</label>
                    <select name="student_type" id="student_type" class="form-control" required>
                        <option value="certificate" {{ old('student_type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="diploma" {{ old('student_type') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                        <option value="advanced" {{ old('student_type') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('student_type')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            
                <!-- File Upload -->
                <div class="form-group">
                    <label for="file" class="fw-bold">Upload File</label>
                    <input type="file" id="file" name="file" class="form-control" required>
                    @error('file')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-3">Upload Research</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('uploads.view') }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>
<script src="{{ asset('js/hideError.js') }}"></script>
