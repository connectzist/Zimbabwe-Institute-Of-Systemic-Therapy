@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Past Exam Paper</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('library.past_exam_papers.update', $paper->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="exam_title" class="form-label">Exam Title</label>
                    <input type="text" name="exam_title" class="form-control" value="{{ old('exam_title', $paper->exam_title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="course_id" class="form-label">Department</label>
                    <select name="course_id" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->course_id }}" {{ $paper->course_id == $course->course_id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="module_name" class="form-label">Module Name</label>
                    <input type="text" name="module_name" class="form-control" value="{{ old('module_name', $paper->module_name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="exam_year" class="form-label">Year</label>
                    <input type="number" name="exam_year" class="form-control" value="{{ old('exam_year', $paper->exam_year) }}" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('library.past_exam_papers') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
