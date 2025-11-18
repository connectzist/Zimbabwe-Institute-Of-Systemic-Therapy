@include('layoutss.dheader')
@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Diploma Result Details</h4>
        </div>
        <div class="card-body">
            
            <!-- Success section -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error section -->
            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @foreach($diplomaResults->groupBy('student_id') as $student_results)
                <!--student details Display-->
                @php
                    $first_result = $student_results->first();
                @endphp
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

                <!-- Course Module, Mark Title and Grades Table -->
                <table class="table table-bordered">
    <thead>
        <tr>
            <th>Course Module</th>
            <th>Mark Title</th>
            <th>Grade</th>
        </tr>
    </thead>
    <tbody>
        {{-- Loop over regular diploma modules --}}
        @foreach($diplomaResults as $diploma_result)
            <tr>
                <td rowspan="3">{{ $diploma_result->courseModule->module_name ?? 'No module assigned' }}</td>
                <td>Theory Mark</td>
                <td>{{ $diploma_result->theory_grade ?? '-' }}</td>
            </tr>
            <tr>
                <td>Practical Mark</td>
                <td>{{ $diploma_result->practical_grade ?? '-' }}</td>
            </tr>
            <tr>
                <td>Exam Mark</td>
                <td>{{ $diploma_result->exam_grade ?? '-' }}</td>
            </tr>
        @endforeach

        {{-- Loop over final modules (e.g., Module 4) --}}
        @foreach($finalModules as $final_module)
            <tr>
                <td rowspan="3">{{ $final_module->module->module_name ?? 'No module assigned' }}</td>
                <td>Research Mark</td>
                <td>{{ $final_module->research_grade ?? '-' }}</td>
            </tr>
            <tr>
                <td>Practical Mark</td>
                <td>{{ $final_module->practical_grade ?? '-' }}</td>
            </tr>
            <tr>
                <td>Exam Mark</td>
                <td>{{ $final_module->exam_grade ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


            @endforeach
        </div>

        <!-- Back button-->
        <div class="card-footer text-right">
            <a href="{{ route('certificate.cert_results') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
