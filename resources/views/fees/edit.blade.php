@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Fee Record</h4>
        </div>
    
        <div class="card-body">
            <form action="{{ route('fees.update', $fee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="student_search">Student Candidate Number</label>
                    <input type="text" id="student_search" class="form-control" value="{{ $fee->student->candidate_number }}" placeholder="Type candidate number...">
                    <input type="hidden" name="student_id" id="student_id" value="{{ $fee->student->id }}" required>
                    <div id="student_suggestions" class="list-group mt-1"></div>
                </div>

                <div class="form-group">
                    <label for="course">Enrolled Course</label>
                    <input type="text" id="course" name="course_display" class="form-control" value="{{ $fee->student->course ?? 'Not enrolled' }}" readonly>
                    <input type="hidden" name="course_id" id="course_id" value="{{ $fee->student->course_id ?? '' }}">
                </div>


                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" value="{{ $fee->amount }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Fee Description</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ $fee->description }}" placeholder="Enter fee description" required>
                </div>

                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ $fee->payment_date->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="1" {{ $fee->status ? 'selected' : '' }}>Paid</option>
                        <option value="0" {{ !$fee->status ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success mt-3">Update Fee</button>
                    <a href="{{ route('fees.index') }}" class="btn btn-secondary mt-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const studentSearch = document.getElementById('student_search');
        const studentIdInput = document.getElementById('student_id');
        const studentSuggestions = document.getElementById('student_suggestions');
        const courseInput = document.getElementById('course');
        const courseIdInput = document.getElementById('course_id');

        studentSearch.addEventListener('input', function () {
            const query = this.value;
            if (query.length < 1) {
                studentSuggestions.innerHTML = '';
                return;
            }

            fetch(`/finance/students/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    studentSuggestions.innerHTML = '';
                    if (data.length) {
                        data.forEach(student => {
                            const div = document.createElement('div');
                            div.classList.add('list-group-item', 'list-group-item-action');
                            div.textContent = student.candidate_number;
                            div.dataset.id = student.id;
                            studentSuggestions.appendChild(div);
                        });
                    } else {
                        const noResult = document.createElement('div');
                        noResult.classList.add('list-group-item');
                        noResult.textContent = 'No matching student found.';
                        studentSuggestions.appendChild(noResult);
                    }
                });
        });

        studentSuggestions.addEventListener('click', function (e) {
            if (e.target && e.target.dataset.id) {
                const selectedId = e.target.dataset.id;
                const selectedText = e.target.textContent;

                studentIdInput.value = selectedId;
                studentSearch.value = selectedText;
                studentSuggestions.innerHTML = '';

                // Fetch and display course
                fetch(`/finance/students/${selectedId}/course`)
                    .then(response => response.json())
                    .then(course => {
                        if (course && course.course_id) {
                            courseInput.value = course.title;
                            courseIdInput.value = course.course_id;
                        } else {
                            courseInput.value = 'Not enrolled';
                            courseIdInput.value = '';
                        }
                    });
            }
        });
    });
</script>
