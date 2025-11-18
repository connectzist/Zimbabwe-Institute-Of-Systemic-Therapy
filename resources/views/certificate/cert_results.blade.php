@include('layoutss.cheader')
@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Certificate Students Results</h4>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th>Candidate Number</th>
                        <th>Candidate Name</th>
                        <th>Course Name</th>
                        {{-- <th>Course Module</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentsData as $data)
                        <tr>
                            <td>{{ $data['candidate_number'] }}</td> 
                            <td>{{ $data['candidate_name'] }}</td>  
                            <td>{{ $data['course_name'] }}</td>      
                            {{-- <td>{{ $data['course_module'] }}</td>  --}}
                            <td>
                                <a href="{{ route('certificate_results.certshow', $data['id']) }}" class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
