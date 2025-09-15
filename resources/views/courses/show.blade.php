@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Course Details</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label"><strong>Course Title:</strong></label>
                <p>{{ $course->title }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Course ID:</strong></label>
                <p>{{ $course->course_id }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Duration:</strong></label>
                <p>{{ $course->duration }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Instructor:</strong></label>
                <p>{{ $course->instructor }}</p>
            </div>

            <div class="mt-4">
                <a href="{{ route('courses.index') }}" class="btn btn-secondary">Back to Courses</a>
            </div>

        </div>
    </div>
</div>
