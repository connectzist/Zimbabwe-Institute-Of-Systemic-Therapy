@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Diploma Result Details</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @foreach($diplomaResults->groupBy('student_id') as $student_results)
                @php
                    $first_result = $student_results->first();
                @endphp

                <!-- Student Info -->
                <table class="table table-bordered">
                    <tr>
                        <th>Candidate Number</th>
                        <td>{{ $first_result->student->candidate_number }}</td>
                    </tr>
                    <tr>
                        <th>Candidate Name</th>
                        <td>{{ $first_result->student->first_name }} {{ $first_result->student->last_name }}</td>
                    </tr>
                    <tr>
                        <th>Course Name</th>
                        <td>{{ $first_result->student->course }}</td>
                    </tr>
                </table>

                <!-- Module Marks Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Course Module</th>
                            <th>Theory Mark</th>
                            <th>Practical Mark</th>
                            <th>Exam Mark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student_results as $diploma_result)
                            <tr>
                                <td>{{ $diploma_result->courseModule->module_name ?? 'No module assigned' }}</td>
                                <td>{{ $diploma_result->theory_mark ?? 'N/A' }}</td>
                                <td>{{ $diploma_result->practical_mark ?? 'N/A' }}</td>
                                <td>{{ $diploma_result->exam_mark ?? 'N/A' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('diploma_results.diplomaedit', $diploma_result->id) }}">
                                                    <i class="fas fa-edit me-2"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('diploma_results.diplomadelete', $diploma_result->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash-alt me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('diploma.diploma_coursework') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
<script src="{{ asset('js/success-message.js') }}"></script>
@endsection
