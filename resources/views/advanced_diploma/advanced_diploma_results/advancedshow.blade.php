@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Advanced Diploma Result Details</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @foreach($advanced_diplomaResults->groupBy('student_id') as $student_results)
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
                        <th>Module</th>
                        <td>{{ $first_result->module ? $first_result->module->module_name : 'Module Missing' }}</td>
                    </tr>
                    <tr>
                        <th>Course Name</th>
                        <td>{{ $first_result->student->course }}</td>
                    </tr>
                </table>
                <hr>
                <!-- Course Modules and Grades Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Course Module</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student_results as $advanced_diploma_result)
                            <tr>
                                <td>{{ $advanced_diploma_result->courseModule ? $advanced_diploma_result->courseModule->course_title : 'No module assigned' }}</td>
                                <td>{{ $advanced_diploma_result->grade }}</td>
                            </tr>
                        @endforeach

                        <!-- End of Module Exam row -->
                        @php
                            $moduleId = $first_result->module_id ?? null;
                        @endphp

                        @if($moduleId && isset($examGrades[$moduleId]))
                            <tr>
                                <td><strong>End of Module Exam</strong></td>
                                <td>{{ $examGrades[$moduleId] }}</td>
                            </tr>
                        @endif

                        <!-- Final Evaluation rows (Module 5 only) -->
                        @if(isset($finalEvaluationGrades) && count($finalEvaluationGrades))
                            @foreach($finalEvaluationGrades as $component => $grade)
                                <tr>
                                    <td>{{ $component }}</td>
                                    <td>{{ $grade }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <!-- Overall Pass Grade -->
                        <tr>
                            <th>Overall Pass</th>
                            <td>{{ $overallGrade }}</td>
                        </tr>
                    </tfoot>
                </table>


            @endforeach
        </div>

        <!-- Back button-->
        <div class="card-footer text-right">
            <a href="{{ route('advanced_diploma.advanced_results') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
