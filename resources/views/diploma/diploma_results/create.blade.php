@include('layoutss.dheader')
@extends('layoutss.app')

@section('content')
<div class="container">
    <h2>Add New Record</h2>

    @if(session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    @endif

    <form id="resultForm" method="POST">
        @csrf
        <div class="row">
            <!-- Student and Module Info -->
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

                <div class="form-group">
                    <label for="candidate_number">Candidate Number</label>
                    <input type="text" id="candidate_number" class="form-control" readonly required>
                </div>

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

            <!-- Marks Section -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exam_mark">Exam Mark</label>
                    <input type="number" id="exam_mark" class="form-control" min="0" max="100" required>
                </div>

                <div class="form-group">
                    <label for="practical_mark">Practical Mark</label>
                    <input type="number" id="practical_mark" class="form-control" min="0" max="100" required>
                </div>

                <!-- Theory Mark (shown for all modules except Module 4) -->
                <div class="form-group" id="theory_mark_group" style="display: block;">
                    <label for="theory_mark">Theory Mark</label>
                    <input type="number" id="theory_mark" class="form-control" min="0" max="100" required>
                </div>

                <!-- Research Mark (only for Module 4) -->
                <div class="form-group" id="research_mark_group" style="display: none;">
                    <label for="research_mark">Research Mark</label>
                    <input type="number" id="research_mark" class="form-control" min="0" max="100">
                </div>

                <button type="button" class="btn btn-success mt-3" id="add_record_table" >Add</button>
                <button type="button" class="btn btn-dark mt-3" id="clear_record">Clear</button>
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
                <th id="research_th">Research</th>
                <th id="theory_th">Theory/ Research</th>
                <th>Practical</th>
                <th>Exam</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <button type="button" class="btn btn-primary" id="saveResultBtn" style="display: none;">Save Result</button>
    <a href="{{ route('diploma.diploma_coursework') }}" class="btn btn-secondary">Cancel</a>

<script>
    // Populate candidate number on student select
    document.getElementById('student_id').addEventListener('change', function () {
        const candidate = this.options[this.selectedIndex].getAttribute('data-candidate');
        document.getElementById('candidate_number').value = candidate || '';
    });

    // Update the table headers and rows based on module selection
    function updateHeadersByModule(moduleName) {
        const researchTh = document.getElementById('research_th');
        const theoryTh = document.getElementById('theory_th');
        const rows = document.querySelectorAll('#resultTable tbody tr');

        if (moduleName.toLowerCase() === 'module 4') {
            // Show Research, hide Theory header
            researchTh.style.display = '';
            theoryTh.style.display = 'none';

            // Show Research cells, hide Theory cells in all rows
            rows.forEach(row => {
                row.cells[3].style.display = ''; // Research cell
                row.cells[4].style.display = 'none'; // Theory cell
            });
        } else {
            // Show Theory, hide Research header
            theoryTh.style.display = '';
            researchTh.style.display = 'none';

            // Show Theory cells, hide Research cells in all rows
            rows.forEach(row => {
                row.cells[4].style.display = ''; // Theory cell
                row.cells[3].style.display = 'none'; // Research cell
            });
        }
    }

    // Show theory or research mark based on module selection
    document.getElementById('module_id').addEventListener('change', function () {
        const selectedText = this.options[this.selectedIndex].text.trim();
        const isModule4 = selectedText.toLowerCase() === 'module 4';

        const researchGroup = document.getElementById('research_mark_group');
        const researchInput = document.getElementById('research_mark');
        const theoryGroup = document.getElementById('theory_mark_group');
        const theoryInput = document.getElementById('theory_mark');

        if (isModule4) {
            researchGroup.style.display = 'block';
            researchInput.required = true;

            theoryGroup.style.display = 'none';
            theoryInput.required = false;
            theoryInput.value = '';
        } else {
            theoryGroup.style.display = 'block';
            theoryInput.required = true;

            researchGroup.style.display = 'none';
            researchInput.required = false;
            researchInput.value = '';
        }

        // Update headers and rows dynamically
        updateHeadersByModule(selectedText);
    });

    // Clear form inputs and reset theory/research display
    document.getElementById('clear_record').addEventListener('click', function () {
        document.querySelectorAll('#resultForm input, #resultForm select').forEach(e => e.value = '');

        // Reset marks visibility to Theory by default
        document.getElementById('theory_mark_group').style.display = 'block';
        document.getElementById('theory_mark').required = true;

        document.getElementById('research_mark_group').style.display = 'none';
        document.getElementById('research_mark').required = false;

        // Reset table headers to Theory visible by default
        updateHeadersByModule(''); // Pass empty string to default to Theory
    });

    // Add record to table with validations
    document.getElementById('add_record_table').addEventListener('click', function () {
        const studentSelect = document.getElementById('student_id');
        const moduleSelect = document.getElementById('module_id');

        const studentName = studentSelect.options[studentSelect.selectedIndex]?.text;
        const studentId = studentSelect.value;
        const candidateNumber = document.getElementById('candidate_number').value;
        const moduleName = moduleSelect.options[moduleSelect.selectedIndex]?.text;
        const moduleId = moduleSelect.value;

        const examMark = parseInt(document.getElementById('exam_mark').value);
        const practicalMark = parseInt(document.getElementById('practical_mark').value);
        const researchVisible = document.getElementById('research_mark_group').style.display !== 'none';
        const theoryVisible = document.getElementById('theory_mark_group').style.display !== 'none';

        const researchMark = researchVisible ? parseInt(document.getElementById('research_mark').value) : null;
        const theoryMark = theoryVisible ? parseInt(document.getElementById('theory_mark').value) : null;

        if (!studentId || !moduleId ||
            isNaN(examMark) || examMark < 0 || examMark > 100 ||
            isNaN(practicalMark) || practicalMark < 0 || practicalMark > 100 ||
            (researchVisible && (isNaN(researchMark) || researchMark < 0 || researchMark > 100)) ||
            (theoryVisible && (isNaN(theoryMark) || theoryMark < 0 || theoryMark > 100))
        ) {
            return alert('Please complete all fields correctly.');
        }

        const table = document.querySelector('#resultTable tbody');
        const row = table.insertRow();

        // Insert cells manually for control (to keep correct column indices)
        row.insertCell(0).textContent = studentName;
        row.insertCell(1).textContent = candidateNumber;
        row.insertCell(2).textContent = moduleName;

        // Research and Theory cells
        const researchCell = row.insertCell(3);
        const theoryCell = row.insertCell(4);

        if (researchMark !== null) {
            researchCell.textContent = researchMark;
            theoryCell.textContent = researchMark;
            theoryCell.style.display = 'none';
            researchCell.style.display = '';
        } else if (theoryMark !== null) {
            theoryCell.textContent = theoryMark;
            researchCell.textContent = '-';
            researchCell.style.display = 'none';
            theoryCell.style.display = '';
        } else {
            researchCell.textContent = '-';
            theoryCell.textContent = '-';
            researchCell.style.display = '';
            theoryCell.style.display = '';
        }

        row.insertCell(5).textContent = practicalMark;
        row.insertCell(6).textContent = examMark;

        // Actions cell with delete button
        const actionsCell = row.insertCell(7);
        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'btn btn-danger btn-sm';
        deleteBtn.textContent = 'Delete';
        actionsCell.appendChild(deleteBtn);

        // Store data attributes for later use
        row.dataset.studentId = studentId;
        row.dataset.moduleId = moduleId;
        row.dataset.examMark = examMark;
        row.dataset.practicalMark = practicalMark;
        row.dataset.researchMark = researchMark ?? '';
        row.dataset.theoryMark = theoryMark ?? '';

        // Delete row handler
        deleteBtn.onclick = function () {
            row.remove();
            if (!document.querySelector('#resultTable tbody tr')) {
                document.getElementById('saveResultBtn').style.display = 'none';
            }
        };

        document.getElementById('saveResultBtn').style.display = 'inline-block';

        // Clear form after adding record, reset marks visibility based on module
        document.getElementById('clear_record').click();
    });

        document.getElementById('resultForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form from being submitted
    });


    // Save all records via AJAX to backend
    document.getElementById('saveResultBtn').addEventListener('click', function () {
        const results = Array.from(document.querySelectorAll('#resultTable tbody tr')).map(row => ({
            student_id: row.dataset.studentId,
            module_id: row.dataset.moduleId,
            exam_mark: row.dataset.examMark,
            practical_mark: row.dataset.practicalMark,
            research_mark: row.dataset.researchMark,
            theory_mark: row.dataset.theoryMark
        }));

        fetch("{{ route('diploma_results.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ results })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                window.location.href = "{{ route('diploma.diploma_coursework') }}";
            } else {
                alert(data.error || 'Error saving data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error submitting data.");
        });
    });

    // On page load: set headers according to selected module if any
    window.addEventListener('DOMContentLoaded', () => {
        const moduleSelect = document.getElementById('module_id');
        const selectedText = moduleSelect.options[moduleSelect.selectedIndex]?.text || '';
        updateHeadersByModule(selectedText);
    });
</script>
</div>
@endsection
