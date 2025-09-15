@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Module</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('modules.update', $module->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Module Name Input -->
                <div class="form-group">
                    <label for="module_name">Module Name</label>
                    <input type="text" id="module_name" name="module_name" class="form-control" value="{{ old('module_name', $module->module_name) }}" required>
                </div>

                <!-- Select Course -->
                <div class="form-group">
                    <label for="course_id">Select Course</label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $module->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Registration Start Date -->
                <div class="form-group">
                    <label for="reg_start">Registration Start Date</label>
                    <input type="date" id="reg_start" name="reg_start" class="form-control" value="{{ old('reg_start', $module->reg_start) }}" required>
                </div>

                <!-- Registration Due Date -->
                <div class="form-group">
                    <label for="reg_due">Registration Due Date</label>
                    <input type="date" id="reg_due" name="reg_due" class="form-control" value="{{ old('reg_due', $module->reg_due) }}" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-3">Update Module</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('modules.view_modules') }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>
