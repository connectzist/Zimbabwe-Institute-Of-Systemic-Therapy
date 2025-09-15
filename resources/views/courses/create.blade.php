@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Add New Course</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="title">ID</label>
                    <input type="text" name="course_id" id="course_id" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="duration">Duration</label>
                    <input type="text" name="duration" id="duration" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="instructor">Instructor</label>
                    <input type="text" name="instructor" id="instructor" class="form-control" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success mt-3">Add Course</button>
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

