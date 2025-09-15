@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Records for {{ $student->first_name }} {{ $student->last_name }} - {{ $module->module_name }}</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Student Info --}}
            <table class="table table-bordered mb-4">
                <tr>
                    <th>Candidate Number</th>
                    <td>{{ $student->candidate_number }}</td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td>{{ $student->course }}</td>
                </tr>
                <tr>
                    <th>Module</th>
                    <td>{{ $module->module_name }}</td>
                </tr>
            </table>

            {{-- Results Table --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course Module</th>
                        <th>Mark</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advanceddiplomaResults as $result)
                        <tr>
                            <td>{{ $result->courseModule->course_title ?? 'No course module' }}</td>
                            <td>{{ $result->mark }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('advanced_diploma_results.advancededit', ['student_id' => $result->student_id, 'course_module' => $result->course_module]) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('advanced_diploma_results.advanced_diploma_resultdelete', $result->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="m-0 p-0">
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

            @if($module->module_name === 'Module 5' && $finalEvaluation)
                <hr>
                <h5>Final Evaluations</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Applied Research In Family Therapy</th>
                            <th>Final Theory</th>
                            <th>Clinicals</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $finalEvaluation->adft110_internal }}</td>
                            <td>{{ $finalEvaluation->final_theory }}</td>
                            <td>{{ $finalEvaluation->clinicals }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('advanced_diploma_results.evaluationsedit', $finalEvaluation->id) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('advanced_final_evaluations.evaluationsdestroy', $finalEvaluation->id) }}"
                                                method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="m-0 p-0">
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
                    </tbody>
                </table>
            @endif

            <a href="{{ route('advanced_diploma.advanced_coursework') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
