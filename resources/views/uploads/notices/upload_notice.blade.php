@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Upload Notice</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('store.notice') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Title Input -->
                <div class="form-group">
                    <label for="title">Notice Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <!-- Notice Content -->
                <div class="form-group">
                    <label for="content">Notice Content (Optional)</label>
                    <textarea id="content" name="content" class="form-control" rows="4">{{ old('content') }}</textarea>
                </div>

                 <!-- Student Department Type -->
                 <div class="form-group">
                    <label for="student_type">Select Department</label>
                    <select name="student_type" id="student_type" class="form-control" required>
                        <option value="All" {{ old('student_type') == 'All' ? 'selected' : '' }}>All</option>
                        <option value="certificate" {{ old('student_type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="diploma" {{ old('student_type') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                        <option value="advanced" {{ old('student_type') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                <!-- File Input -->
                <div class="form-group">
                    <label for="file">Upload Notice (PDF, DOC, DOCX, or Image)</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>

                <!-- Expiry Date Input -->
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-3">Upload Notice</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('notices.view') }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>