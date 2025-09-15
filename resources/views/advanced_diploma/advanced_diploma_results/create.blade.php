@extends('layoutss.app')

@section('content')
<div class="container">
    <h2>Add New Record</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form id="resultForm" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select name="student_id" id="student_id" class="form-control" required>
                        <option value="">Select Student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" data-candidate="{{ $student->candidate_number }}">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="candidate_number">Candidate Number</label>
                    <input type="text" id="candidate_number" class="form-control" readonly required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="module_id">Module</label>
                    <select name="module_id" id="module_id" class="form-control" required>
                        <option value="" disabled selected>Select Module</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->module_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="course_module">Course Module</label>
                    <select name="course_module" id="course_module" class="form-control" required>
                        <option value="">Select Course Module</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="mark">Mark</label>
                    <div class="input-group">
                        <input type="number" name="mark" id="mark" class="form-control" min="0" max="100" required>
                        <div class="input-group-append">
                            <button type="button" id="add_record" class="btn btn-success ml-2">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <h3 class="mt-5">Entered Records</h3>
    <table class="table table-bordered" id="resultTable">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Candidate Number</th>
                <th>Module</th>
                <th>Course Module</th>
                <th>Mark</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="form-group">
        <button type="button" class="btn btn-primary" id="saveResultBtn" style="display:none;">Save Result</button>
        <a href="{{ route('advanced_diploma.advanced_coursework') }}" class="btn btn-secondary">Cancel</a>
    </div>
</div>

<script>
    // Auto-fill candidate number
    document.getElementById('student_id').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        document.getElementById('candidate_number').value = selected.getAttribute('data-candidate') || '';
    });

    // Load course modules and inject Final Exams for Module 5
    document.getElementById('module_id').addEventListener('change', function () {
        const moduleId = this.value;
        const moduleText = this.options[this.selectedIndex]?.text?.trim();
        const courseModuleSelect = document.getElementById('course_module');

        courseModuleSelect.innerHTML = '<option value="">Loading...</option>';

        if (!moduleId) {
            courseModuleSelect.innerHTML = '<option value="">Select Course Module</option>';
            return;
        }

        fetch(`/get-course-modules/${moduleId}`)
            .then(res => res.json())
            .then(data => {
                courseModuleSelect.innerHTML = '<option value="">Select Course Module</option>';

                if (data.length > 0) {
                    data.forEach(course => {
                        let option = document.createElement('option');
                        option.value = course.id;
                        option.text = `${course.course_code} - ${course.course_title}`;
                        courseModuleSelect.appendChild(option);
                    });
                } else {
                    courseModuleSelect.innerHTML = '<option value="">No Course Modules found</option>';
                }

                // Add final exams if Module 5 is selected
                if (moduleText.toLowerCase() === 'module 5') {
                    let finalGroup = document.createElement('optgroup');
                    finalGroup.label = 'Final Examinations';

                    const extras = [
                        { id: 'final_theory', title: 'Final Theory Exam' },
                        { id: 'clinicals', title: 'Clinicals' }
                    ];

                    extras.forEach(extra => {
                        let option = document.createElement('option');
                        option.value = extra.id;
                        option.text = extra.title;
                        finalGroup.appendChild(option);
                    });

                    courseModuleSelect.appendChild(finalGroup);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Failed to load course modules.");
                courseModuleSelect.innerHTML = '<option value="">Select Course Module</option>';
            });
    });

    // Add record
    document.getElementById('add_record').addEventListener('click', function () {
        const studentSelect = document.getElementById('student_id');
        const studentName = studentSelect.options[studentSelect.selectedIndex]?.text;
        const studentId = studentSelect.value;
        const candidateNumber = document.getElementById('candidate_number').value;

        const moduleSelect = document.getElementById('module_id');
        const moduleText = moduleSelect.options[moduleSelect.selectedIndex]?.text;
        const moduleId = moduleSelect.value;

        const courseSelect = document.getElementById('course_module');
        const courseText = courseSelect.options[courseSelect.selectedIndex]?.text;
        const courseId = courseSelect.value;

        const mark = document.getElementById('mark').value;

        if (!studentId || !moduleId || !courseId || mark === '') {
            alert("Please fill all fields.");
            return;
        }

        if (mark < 0 || mark > 100) {
            alert("Mark must be between 0 and 100.");
            return;
        }

        const tableBody = document.querySelector('#resultTable tbody');
        const row = tableBody.insertRow();

        row.insertCell(0).textContent = studentName;
        row.insertCell(1).textContent = candidateNumber;
        row.insertCell(2).textContent = moduleText;
        row.insertCell(3).textContent = courseText;
        row.insertCell(4).textContent = mark;

        const actionsCell = row.insertCell(5);
        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'btn btn-danger btn-sm';
        deleteBtn.textContent = 'Delete';
        deleteBtn.onclick = () => row.remove();
        actionsCell.appendChild(deleteBtn);

        row.setAttribute('data-student-id', studentId);
        row.setAttribute('data-module-id', moduleId);
        row.setAttribute('data-course-id', courseId);

        // Reset fields
        studentSelect.value = '';
        document.getElementById('candidate_number').value = '';
        moduleSelect.value = '';
        courseSelect.innerHTML = '<option value="">Select Course Module</option>';
        document.getElementById('mark').value = '';

        document.getElementById('saveResultBtn').style.display = 'inline-block';
    });

    // Save all results
    document.getElementById('saveResultBtn').addEventListener('click', function () {
        const rows = document.querySelectorAll('#resultTable tbody tr');
        if (rows.length === 0) {
            alert("Please add at least one record.");
            return;
        }

        const results = Array.from(rows).map(row => ({
            student_id: row.getAttribute('data-student-id'),
            module_id: row.getAttribute('data-module-id'),
            course_module: row.getAttribute('data-course-id'),
            mark: row.cells[4].textContent
        }));

        fetch("{{ route('advanced_diploma_results.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ results })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                window.location.href = "{{ route('advanced_diploma.advanced_coursework') }}";
            } else {
                alert(data.error || "Something went wrong.");
            }
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Error submitting data.");
        });
    });
</script>
@endsection
