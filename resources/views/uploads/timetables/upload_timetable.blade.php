@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Upload Timetable</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('store.timetable') }}" method="POST" enctype="multipart/form-data">
                @csrf


                <!-- Title Input -->
                <div class="form-group">
                    <label for="title">Title (e.g., Exam Timetable)</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>


                <!-- Student Department Type -->
                <div class="form-group">
                    <label for="student_type">Select Department</label>
                    <select name="student_type" id="student_type" class="form-control" required>
                        <option value="certificate" {{ old('student_type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="diploma" {{ old('student_type') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                        <option value="advanced" {{ old('student_type') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>


                <!-- File Input -->
                <div class="form-group">
                    <label for="file">Upload Timetable (PDF, DOC, DOCX)</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
                </div>


                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-3">Upload</button>
            </form>
        </div>


        <div class="card-footer text-right">
            <a href="{{ route('timetables.view') }}"  class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>





