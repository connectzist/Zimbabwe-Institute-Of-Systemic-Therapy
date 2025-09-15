@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Certificate Result Details</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $firstResult = $certificateResults->first();
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

            <!-- Loop through each module result -->
            @foreach($certificateResults as $result)
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
                            <td rowspan="3">{{ $result->courseModule->module_name ?? 'No module assigned' }}</td>
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
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right"><strong>Overall Pass</strong></td>
                            <td colspan="2">{{ $result->module_grade ?? 'N/A' }}</td>
                            </tr>
                        </tr>
                    </tfoot>
                </table>
            @endforeach
        </div>

        <!-- Back button-->
        <div class="card-footer text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>