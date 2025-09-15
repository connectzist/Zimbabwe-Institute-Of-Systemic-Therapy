@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Add New Fee Record</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('fees.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="student_search">Student Candidate Number</label>
                    <input type="text" id="student_search" class="form-control" placeholder="Type candidate number...">
                    <input type="hidden" name="student_id" id="student_id" required>
                    <div id="student_suggestions" class="list-group mt-1"></div>
                </div>

                <div class="form-group">
                    <label for="course">Enrolled Course</label>
                    <input type="text" id="course" name="course_display" class="form-control" readonly>
                    <input type="hidden" name="course_id" id="course_id">
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success mt-3">Add Fee</button>
                    <a href="{{ route('fees.index') }}" class="btn btn-secondary mt-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for autocomplete and course fetching --}}
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
                    if (course) {
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
