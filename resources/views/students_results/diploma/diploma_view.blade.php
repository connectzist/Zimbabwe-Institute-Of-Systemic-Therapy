@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Diploma Result Details</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $firstResult = $allResults->first();
            @endphp

            @if($firstResult)
                <!-- Student Details -->
                <table class="table table-bordered">
                    <tr>
                        <th>Candidate Number</th>
                        <td>{{ $firstResult->student->candidate_number }}</td>
                    </tr>
                    <tr>
                        <th>Candidate Name</th>
                        <td>{{ $firstResult->student->first_name }} {{ $firstResult->student->last_name }}</td>
                    </tr>
                    <tr>
                        <th>Course Name</th>
                        <td>{{ $firstResult->student->course }}</td>
                    </tr>
                </table>
            @endif

            <!-- Loop through all results -->
            @foreach($allResults as $result)
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Course Module</th>
                            <th>Mark Title</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="3">
                                {{ $result->courseModule->module_name ?? $result->module->module_name ?? 'No module assigned' }}
                            </td>

                            @if($result->is_final_module)
                                <td>Practical Mark</td>
                                <td>{{ $result->practical_grade ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Exam Mark</td>
                            <td>{{ $result->exam_grade ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Research Mark</td>
                            <td>{{ $result->research_grade ?? '-' }}</td>
                        </tr>
                            @else
                                <td>Theory Mark</td>
                                <td>{{ $result->theory_grade ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Practical Mark</td>
                            <td>{{ $result->practical_grade ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Exam Mark</td>
                            <td>{{ $result->exam_grade ?? '-' }}</td>
                        </tr>
                            @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right"><strong>Overall Pass</strong></td>
                            <td>{{ $result->module_grade ?? 'N/A' }}</td>
                        </tr>
                    </tfoot>
                </table>
            @endforeach
        </div>

        <div class="card-footer text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
