@extends('layouts.app')

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

            <!-- Student Info -->
            <table class="table table-bordered">
                <tr>
                    <th>Candidate Number</th>
                    <td>{{ $student->candidate_number }}</td>
                </tr>
                <tr>
                    <th>Candidate Name</th>
                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                </tr>
                <tr>
                    <th>Course Name</th>
                    <td>{{ $student->course }}</td>
                </tr>
            </table>

            <!-- Results by Module -->
            @foreach($moduleData as $module)
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th colspan="2">Course Module: {{ $module['module_name'] }}</th>
                        </tr>
                        <tr>
                            <th>Course Title</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($module['course_titles'] as $title)
                            <tr>
                                <td>{{ $title['title'] }}</td>
                                <td>{{ $title['grade'] }}</td>
                            </tr>
                        @endforeach

                        @if($module['exam_grade'] !== 'N/A')
                            <tr>
                                <td><strong>End of Module Exam</strong></td>
                                <td>{{ $module['exam_grade'] }}</td>
                            </tr>
                        @endif

                        @if(!empty($module['evaluation_grades']))
                            @foreach($module['evaluation_grades'] as $title => $grade)
                                <tr>
                                    <td>{{ $title }}</td>
                                    <td>{{ $grade }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Overall Pass</th>
                            <td>{{ $module['overall_grade'] }}</td>
                        </tr>
                    </tfoot>
                </table>
            @endforeach
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('students_results.advanced_diploma.advanced_diplomaresults') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>