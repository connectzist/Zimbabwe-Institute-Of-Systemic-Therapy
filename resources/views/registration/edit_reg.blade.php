@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Registration</h2>
    <form action="{{ route('registrations.update', $registration->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="student_id">Student</label>
            <select name="student_id" id="student_id" class="form-control" required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ $student->id == $registration->student_id ? 'selected' : '' }}>
                        {{ $student->first_name }} {{ $student->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="module_id">Module</label>
            <select name="module_id" id="module_id" class="form-control" required>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ $module->id == $registration->module_id ? 'selected' : '' }}>
                        {{ $module->module_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="registered">Registration Status</label> <br>
            <input type="checkbox" name="registered" id="registered" value="1" {{ $registration->registered ? 'checked' : '' }}>
            <label for="registered">Active</label>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
