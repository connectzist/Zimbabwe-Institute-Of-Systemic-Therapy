@include('layoutss.cheader')
@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Certificate Students Records</h4>
            <a href="{{ route('certificate_results.create') }}" class="btn btn-primary">Add New</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Candidate Number</th>
                        <th>Candidate Name</th>
                        <th>Course Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentsData as $data)
                        <tr>
                            <td>{{ $data['candidate_number'] }}</td>
                            <td>{{ $data['candidate_name'] }}</td>
                            <td>{{ $data['course_name'] }}</td>
                            <td>
                                <!-- Bootstrap 5 Dropdown (Ellipsis Menu) -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('certificate_results.show', $data['id']) }}"><i class="fas fa-eye me-2"></i>View</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('certificate_results.destroy', $data['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i>Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script for fading success message -->
<script src="{{ asset('js/success-message.js') }}"></script>
@endsection
