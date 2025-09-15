@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Course</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $course->title }}" required>
                </div>

                <div class="form-group">
                    <label for="course_id">Course ID</label>
                    <input type="text" name="course_id" id="course_id" class="form-control" value="{{ $course->course_id }}" required>
                </div>

                <div class="form-group">
                    <label for="duration">Duration</label>
                    <input type="text" name="duration" id="duration" class="form-control" value="{{ $course->duration }}" required>
                </div>

                <div class="form-group">
                    <label for="instructor">Instructor</label>
                    <input type="text" name="instructor" id="instructor" class="form-control" value="{{ $course->instructor }}" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success mt-2">Update Course</button>
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
