@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Advanced Diploma Exam Result Details</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $first_result = $examResults->first();
            @endphp

            <!-- Student Details Table -->
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
                    <th>Module</th>
                    <td>{{ $first_result->module ? $first_result->module->module_name : 'Module Missing' }}</td>
                </tr>
                 <tr>
                    <th>Course Name</th>
                    <td>{{ $first_result->student->course }}</td>
                </tr>
            </table>
            <hr>
            <!-- Modules and Marks Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Module Name</th>
                        <th>Exam Mark</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examResults as $result)
                        <tr>
                            <td>{{ $result->module ? $result->module->module_name : 'Module Missing' }}</td>
                            <td>{{ $result->exam_mark }}</td>
                             <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('advanced_diploma_results.advancedexamedit',  $result->id) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('advanced_diploma_results.advanced_diploma_examdelete', $result->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="m-0 p-0">
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
        </div>

        <!-- Back Button -->
        <div class="card-footer text-right">
            <a href="{{ route('advanced_diploma.advanced_coursework') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
<script src="{{ asset('js/success-message.js') }}"></script>
@endsection
