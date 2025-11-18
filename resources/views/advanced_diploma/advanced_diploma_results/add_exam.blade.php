@include('layoutss.aheader')
@extends('layoutss.app')

@section('content')
<div class="container">
    <h2>Add Semester Exam Record</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Result Form -->
    <form id="resultForm" method="POST">
        @csrf

        {{-- Student Selection --}}
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

        {{-- Module and Mark --}}
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

            <div class="col-md-6">
                <div class="form-group">
                    <label for="exam_mark">Exam Mark</label>
                    <div class="input-group">
                        <input type="number" name="exam_mark" id="exam_mark" class="form-control" min="0" max="100" required>
                        <div class="input-group-append">
                            <button type="button" id="add_record" class="btn btn-success ml-2">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Records Table --}}
    <h3 class="mt-5">Entered Records</h3>
    <table class="table table-bordered" id="resultTable">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Candidate Number</th>
                <th>Module</th>
                <th>Exam Mark</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    {{-- Save Button --}}
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

    // Add record to table
    document.getElementById('add_record').addEventListener('click', function () {
        const studentSelect = document.getElementById('student_id');
        const studentName = studentSelect.options[studentSelect.selectedIndex]?.text;
        const studentId = studentSelect.value;
        const candidateNumber = document.getElementById('candidate_number').value;

        const moduleSelect = document.getElementById('module_id');
        const moduleText = moduleSelect.options[moduleSelect.selectedIndex]?.text;
        const moduleId = moduleSelect.value;

        const mark = document.getElementById('exam_mark').value;

        if (!studentId || !moduleId || mark === '') {
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
        row.insertCell(3).textContent = mark;

        const actionsCell = row.insertCell(4);
        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'btn btn-danger btn-sm';
        deleteBtn.textContent = 'Delete';
        deleteBtn.onclick = () => row.remove();
        actionsCell.appendChild(deleteBtn);

        row.setAttribute('data-student-id', studentId);
        row.setAttribute('data-module-id', moduleId);

        // Reset form
        studentSelect.value = '';
        document.getElementById('candidate_number').value = '';
        moduleSelect.value = '';
        document.getElementById('exam_mark').value = '';

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
            exam_mark: row.cells[3].textContent
        }));

        fetch("{{ route('advanced_diploma_results.examstore') }}", {
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
                window.location.href = "{{ route('advanced_diploma.advanced_semester_exams') }}";
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
