@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Research Details</h4>
        </div>

        <div class="card-body">
            <div class="form-label">
                <label for="research_title"><strong>Research Title</strong></label>
                <p id="research_title">{{ $research->research_title }}</p>
            </div>

            <div class="mb-3">
                <label for="researcher"><strong>Researcher</strong></label>
                <p id="researcher">{{ $research->researcher }}</p>
            </div>

            <div class="form-group">
                <label for="year_of_research"><strong>Year of Research</strong></label>
                <p id="year_of_research">{{ $research->year_of_research }}</p>
            </div>

            <div class="form-group">
                <label for="student_type"><strong>Department</strong></label>
                <p id="student_type">{{ ucfirst($research->student_type) }}</p>
            </div>

            <div class="form-group">
                <label for="file"><strong>Uploaded File</strong></label>
                <p id="file">
                    @if($research->file_path)
                        <a href="{{ asset('storage/' . $research->file_path) }}" target="_blank">Download File</a>
                    @else
                        No file uploaded.
                    @endif
                </p>
            </div>
            
            <a href="{{ route('uploads.research') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
