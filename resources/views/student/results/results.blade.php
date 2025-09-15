@extends('student.layouts.app')

@section('content')
    <div class="container mt-5">

        {{-- Error --}}
        @if(isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @else

            {{-- Certificate --}}
            @if(isset($certificate_results) && count($certificate_results) > 0)
                <h5>Certificate In Systemic Family Counselling</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Module Name</th>
                            <th>Mark Title</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificate_results as $result)
                            <tr>
                                <td rowspan="3">{{ $result['module_name'] }}</td>
                                <td>Theory</td>
                                <td>{{ $result['theory_grade'] }}</td>
                            </tr>
                            <tr>
                                <td>Practical</td>
                                <td>{{ $result['practical_grade'] }}</td>
                            </tr>
                            <tr>
                                <td>Exam</td>
                                <td>{{ $result['exam_grade'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Overall Pass</strong></td>
                                <td><strong>{{ $result['overall_grade'] }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Diploma --}}
            @if(isset($diploma_results) && count($diploma_results) > 0)
                <h5>Diploma In Systemic Family Counselling</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Module Name</th>
                            <th>Mark Title</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diploma_results as $result)
                            <tr>
                                <td rowspan="3">{{ $result['module_name'] }}</td>
                                <td>
                                    {{ $result['module_name'] === 'Module 4' ? 'Research' : 'Theory' }}
                                </td>
                                <td>
                                    {{ $result['module_name'] === 'Module 4' ? $result['research_grade'] : $result['theory_grade'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Practical</td>
                                <td>{{ $result['practical_grade'] }}</td>
                            </tr>
                            <tr>
                                <td>Exam</td>
                                <td>{{ $result['exam_grade'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Overall Pass</strong></td>
                                <td><strong>{{ $result['overall_grade'] }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Advanced Diploma --}}
            @if(isset($advanced_diploma_results_grouped) && count($advanced_diploma_results_grouped) > 0)
                <h5>Advanced Diploma In Systemic Family Therapy</h5>
                <hr>
                @php
                    // Building a mapping of module_name => moduleId
                    $moduleSortMap = [];

                    foreach ($advanced_diploma_results_grouped as $moduleId => $results) {
                        $name = strtolower($results[0]['module_name'] ?? '');
                        $moduleSortMap[$moduleId] = $name;
                    }

                    // Sort based on natural order of module_names
                    uasort($moduleSortMap, function ($a, $b) {
                        // Extract numeric part for sorting
                        preg_match('/\d+/', $a, $matchA);
                        preg_match('/\d+/', $b, $matchB);
                        return ((int)($matchA[0] ?? 99)) <=> ((int)($matchB[0] ?? 99));
                    });
                @endphp

                @foreach($moduleSortMap as $moduleId => $moduleName)
                    @php
                        $results = $advanced_diploma_results_grouped[$moduleId];
                        preg_match('/\d+/', $moduleName, $semesterMatch);
                        $semesterNumber = $semesterMatch[0] ?? 'N/A';
                    @endphp

                    <h6>Semester {{ $semesterNumber }}</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td>{{ $result['course_code'] }}</td>
                                    <td>{{ $result['course_title'] }}</td>
                                    <td>{{ $result['grade'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @if(isset($overallGrades[$moduleId]))
                                <tr>
                                    <th colspan="2" class="text-left">Overall Pass</th>
                                    <td><strong>{{ $overallGrades[$moduleId] }}</strong></td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                @endforeach
            @endif

            {{-- No results --}}
            @if(
                empty($certificate_results) && 
                empty($diploma_results) && 
                empty($advanced_diploma_results_grouped)
            )
                <p>No results found for this student.</p>
            @endif

        @endif
    </div>
@endsection
