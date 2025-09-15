@extends('layouts.app')


@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Timetable</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('timetables.update', $timetable->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="form-group">
                    <label for="title">Title (e.g., Exam Timetable)</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $timetable->title) }}" required>
                </div>


                <div class="form-group">
                    <label for="student_type">Select Student Type</label>
                    <select name="student_type" id="student_type" class="form-control" required>
                        <option value="certificate" {{ $timetable->student_type == 'certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="diploma" {{ $timetable->student_type == 'diploma' ? 'selected' : '' }}>Diploma</option>
                        <option value="advanced" {{ $timetable->student_type == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="file">Upload New Timetable (PDF, DOC, DOCX) [Optional]</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                </div>


                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>


        <div class="card-footer text-right">
            <a href="{{ route('timetables.view') }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>







