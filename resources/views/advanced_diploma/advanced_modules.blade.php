@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Advanced Diploma Topics</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Credits</th>
                    </tr>
                </thead>
                <tbody>
                    @if($advanceddiplomaModules->isNotEmpty())
                        @foreach ($advanceddiplomaModules as $module)
                            <tr>
                                <td>{{ $module->id }}</td>
                                <td>{{ $module->course_code }}</td>
                                <td>{{ $module->course_title }}</td>
                                <td>{{ $module->credits }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No Advanced Diploma topics found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
