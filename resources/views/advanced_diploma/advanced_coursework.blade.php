@include('layoutss.aheader')
@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Advanced Diploma Students Records</h4>
            <a href="{{ route('advanced_diploma_results.create') }}" class="btn btn-primary">Add New</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Candidate Number</th>
                        <th>Candidate Name</th>
                        <th>Course Name</th>
                        <th>Course Module</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentsData as $data)
                        <tr>
                            <td>{{ $data['candidate_number'] }}</td>
                            <td>{{ $data['candidate_name'] }}</td>
                            <td>{{ $data['course_name'] }}</td>
                            <td>{{ $data['course_module'] }}</td>
                            <td>
                                <a href="{{ route('advanced_diploma_results.show', ['student_id' => $data['id'], 'module_id' => $data['module_id']]) }}" class="btn btn-info btn-sm">View</a>

                                {{-- <form action="{{ route('advanced_diploma_results.destroy', $data['id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ asset('js/success-message.js') }}"></script>
@endsection
