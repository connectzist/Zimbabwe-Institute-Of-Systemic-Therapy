@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Exam Paper Details</h4>
        </div>

        <div class="card-body">
            <!-- Exam Title -->
            <div class="form-label">
                <label for="exam_title"><strong>Exam Title</strong></label>
                <p id="exam_title">{{ $paper->exam_title }}</p>
            </div>

            <!-- Department / Course Name -->
            <div class="mb-3">
                <label for="department"><strong>Department</strong></label>
                <p id="department">{{ $paper->course->title ?? 'N/A' }}</p>
            </div>

            <!-- Module Name -->
            <div class="form-group">
                <label for="module_name"><strong>Module Name</strong></label>
                <p id="module_name">{{ $paper->module_name ?? 'N/A' }}</p>
            </div>

            <!-- Exam Year -->
            <div class="form-group">
                <label for="exam_year"><strong>Exam Year</strong></label>
                <p id="exam_year">{{ $paper->exam_year }}</p>
            </div>

            <!-- File Download -->
            <div class="form-group">
                <label for="file_path"><strong>Uploaded Exam Paper</strong></label>
                <p id="file_path">
                    @if($paper->file_path)
                        <a href="{{ asset('storage/' . $paper->file_path) }}" target="_blank">View / Download PDF</a>
                    @else
                        No file uploaded.
                    @endif
                </p>
            </div> 

            <a href="{{ route('library.past_exam_papers') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
