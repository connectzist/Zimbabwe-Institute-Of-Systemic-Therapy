@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Registration</h2>
    <form action="{{ route('registrations.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="student_id">Student</label>
            <select name="student_id" id="student_id" class="form-control" required>
                <option value="" disabled selected>Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" data-course-id="{{ $student->course_id }}">
                        {{ $student->candidate_number }}  |  ({{ $student->course }})
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="module_id">Module</label>
            <select name="module_id" id="module_id" class="form-control" required>
                <option value="" disabled selected>Select Module</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}">{{ $module->module_name }}</option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-success mt-3">Save</button>
        <a href="{{ route('registration.registered_students') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>

<script>
    //Update module list based => course_id
    document.getElementById('student_id').addEventListener('change', function() {
        var studentId = this.value;
        var courseId = this.options[this.selectedIndex].getAttribute('data-course-id');
        
        // Fetch modules <=> AJAX
        if (studentId) {
            fetchModules(courseId);
        } else {
            document.getElementById('module_id').innerHTML = '<option value="">Select Module</option>';
        }
    });

    function fetchModules(courseId) {
        if (!courseId) return;

        fetch('/modules-by-course/' + courseId)
            .then(response => response.json())
            .then(data => {
                var moduleSelect = document.getElementById('module_id');
                moduleSelect.innerHTML = '<option value="" disabled selected >Select Module</option>';

                data.modules.forEach(function(module) {
                    var option = document.createElement('option');
                    option.value = module.id;
                    option.textContent = module.module_name;
                    moduleSelect.appendChild(option);
                });
            });
    }
</script>
