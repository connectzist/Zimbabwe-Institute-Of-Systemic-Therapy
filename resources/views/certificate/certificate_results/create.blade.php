@include('layoutss.cheader')
@extends('layoutss.app')

@section('content')
<div class="container">
    <h2>Add New Record</h2>

    <!-- Success Section -->
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form to add a new result -->
        <form id="resultForm" method="POST">
            @csrf
            <div class="row">
                <!-- Left Column: Student and Module -->
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
                        <input type="text" name="candidate_number" id="candidate_number" class="form-control" readonly required>
                    </div>

                    <div class="form-group">
                        <label for="module_id">Module</label>
                        <select name="module_id" id="module_id" class="form-control" required>
                            <option value="" disabled selected>Select Module</option>
                            @foreach ($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->module_name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('module_id'))
                            <span class="text-danger">{{ $errors->first('module_id') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Marks -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="theory_mark">Theory Mark</label>
                        <input type="number" name="theory_mark" id="theory_mark" class="form-control" min="0" max="100" required>
                    </div>

                    <div class="form-group">
                        <label for="practical_mark">Practical Mark</label>
                        <input type="number" name="practical_mark" id="practical_mark" class="form-control" min="0" max="100" required>
                    </div>

                    <div class="form-group">
                        <label for="exam_mark">Exam Mark</label>
                        <input type="number" name="exam_mark" id="exam_mark" class="form-control" min="0" max="100" required>
                    </div>

                    <button type="button" id="add_record" class="btn btn-success mt-2">Add</button>
                    <button type="button" id="clear_record" class="btn btn-dark mt-2">Clear</button>
                </div>
            </div>
        </form>

        <!-- Results display -->
        <h3 class="mt-5">Entered Records</h3>
        <table class="table table-bordered" id="resultTable">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Candidate Number</th>
                    <th>Module</th>
                    <th>Theory Mark</th>
                    <th>Practical Mark</th>
                    <th>Exam Mark</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- dynamic rows -->
            </tbody>
        </table>

        <!-- Save and Cancel buttons -->
        <div class="form-group">
            <button type="button" class="btn btn-primary" id="saveResultBtn" style="display:none;">Save Result</button>
            <a href="{{ route('certificate.cert_coursework') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Auto-fill candidate number
        document.getElementById('student_id').addEventListener('change', function () {
            const candidateNumber = this.options[this.selectedIndex].getAttribute('data-candidate');
            document.getElementById('candidate_number').value = candidateNumber || '';
        });
        
        // Clear button - properly placed
        document.getElementById('clear_record').addEventListener('click', function () {
            document.getElementById('student_id').value = '';
            document.getElementById('candidate_number').value = '';
            document.getElementById('module_id').value = '';
            document.getElementById('theory_mark').value = '';
            document.getElementById('practical_mark').value = '';
            document.getElementById('exam_mark').value = '';
        });

        // Add record to table
        document.getElementById('add_record').addEventListener('click', function () {
            const studentSelect = document.getElementById('student_id');
            const moduleSelect = document.getElementById('module_id');
            const studentName = studentSelect.options[studentSelect.selectedIndex]?.text;
            const studentId = studentSelect.value;
            const candidateNumber = document.getElementById('candidate_number').value;
            const moduleName = moduleSelect.options[moduleSelect.selectedIndex]?.text;
            const moduleId = moduleSelect.value;

            const theoryMark = parseInt(document.getElementById('theory_mark').value);
            const practicalMark = parseInt(document.getElementById('practical_mark').value);
            const examMark = parseInt(document.getElementById('exam_mark').value);

            if (!studentId || !moduleId) {
                alert('Please fill all fields.');
                return;
            }

            if ([theoryMark, practicalMark, examMark].some(m => isNaN(m) || m < 0 || m > 100)) {
                alert('All marks must be between 0 and 100.');
                return;
            }


            const averageMark = Math.round((theoryMark + practicalMark + examMark) / 3);


            const table = document.querySelector('#resultTable tbody');
            const row = table.insertRow();

            row.innerHTML = `
                <td>${studentName}</td>
                <td>${candidateNumber}</td>
                <td>${moduleName}</td>
                <td>${theoryMark}</td>
                <td>${practicalMark}</td>
                <td>${examMark}</td>
                <td><button class="btn btn-danger btn-sm">Delete</button></td>
            `;

            row.dataset.studentId = studentId;
            row.dataset.moduleId = moduleId;
            row.dataset.theoryMark = theoryMark;
            row.dataset.practicalMark = practicalMark;
            row.dataset.examMark = examMark;
            row.dataset.averageMark = averageMark;

            row.querySelector('button').onclick = function () {
                row.remove();
                if (!document.querySelector('#resultTable tbody tr')) {
                    document.getElementById('saveResultBtn').style.display = 'none';
                }
            };

            document.getElementById('saveResultBtn').style.display = 'inline-block';

            // Clear inputs
            studentSelect.value = '';
            document.getElementById('candidate_number').value = '';
            moduleSelect.value = '';
            document.getElementById('theory_mark').value = '';
            document.getElementById('practical_mark').value = '';
            document.getElementById('exam_mark').value = '';
        });

        // Save records
        document.getElementById('saveResultBtn').addEventListener('click', function (e) {
            e.preventDefault();

            const results = [];
            document.querySelectorAll('#resultTable tbody tr').forEach(row => {
                results.push({
                    student_id: row.dataset.studentId,
                    module_id: row.dataset.moduleId,
                    theory_mark: row.dataset.theoryMark,
                    practical_mark: row.dataset.practicalMark,
                    exam_mark: row.dataset.examMark,
                    average_mark: row.dataset.averageMark
                });
            });

            if (!results.length) {
                alert('Please add at least one record.');
                return;
            }

            fetch("{{ route('certificate_results.store') }}", {
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
                    window.location.href = "{{ route('certificate.cert_coursework') }}";
                } else {
                    alert(data.error || 'Error occurred.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Submission failed.');
            });
        });
    </script>
    <script src="{{ asset('js/success-message.js') }}"></script>
</div>
@endsection
