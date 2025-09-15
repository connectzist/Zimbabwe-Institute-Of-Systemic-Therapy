@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Upload Past Exam Paper</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('library.library.past_exam_papers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Course -->
                <div class="form-group">
                    <label for="course_id">Course</label>
                    <select id="course_id" name="course_id" class="form-control" required>
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->course_id }}" {{ old('course_id') == $course->course_id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Exam Title -->
                <div class="form-group">
                    <label for="exam_title">Exam Title</label>
                    <input type="text" id="exam_title" name="exam_title" class="form-control" value="{{ old('exam_title') }}" required>
                    @error('exam_title')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Exam Year -->
                <div class="form-group">
                    <label for="exam_year">Exam Year</label>
                    <input type="number" id="exam_year" name="exam_year" class="form-control" value="{{ old('exam_year') }}" min="1900" max="{{ date('Y') }}" required>
                    @error('exam_year')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Module Name -->
                <div class="form-group">
                    <label for="module_name">Module Name </label>
                    <input type="text" id="module_name" name="module_name" class="form-control" value="{{ old('module_name') }}">
                    @error('module_name')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="form-group">
                    <label for="file_path">Upload Exam Paper (PDF only)</label>
                    <input type="file" id="file_path" name="file_path" class="form-control" accept=".pdf" required>
                    @error('file_path')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary mt-3">Upload</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('zist.library') }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>
