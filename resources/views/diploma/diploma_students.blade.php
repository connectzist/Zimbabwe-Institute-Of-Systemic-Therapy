@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Diploma Students</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Candidate Number</th>
                        <th>Email</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($diplomaStudents as $student)
                        <tr>
                            <td>{{ $student->first_name }}</td>   
                            <td>{{ $student->last_name }}</td>  
                            <td>{{ $student->candidate_number }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->course }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
