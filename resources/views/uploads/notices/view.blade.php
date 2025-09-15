@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Notices</h4>
            <a href="{{ route('notices.create') }}" class="btn btn-primary">New Notice</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Department(s)</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notices as $notice)
                        <tr>
                            <td>{{ $notice->title }}</td>
                            <td>{{ ucfirst($notice->student_type) }}</td>
                            <td>{{ $notice->expiry_date }}</td>
                            <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton{{ $notice->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $notice->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('notices.edit', $notice->id) }}">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('notices.destroy', $notice->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash-alt me-2"></i>Delete
                                            </button>
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
        
        {{-- Back Button --}}
        <div class="card-footer text-right">
            <a href="{{ route('uploads.view') }}" class="btn btn-secondary">Back</a>
        </div>   

    </div>
</div>
<script src="{{ asset('js/hideError.js') }}"></script>
