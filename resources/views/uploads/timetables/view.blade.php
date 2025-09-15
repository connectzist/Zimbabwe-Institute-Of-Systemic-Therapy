@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Uploaded Timetables</h4>
            <a href="{{ route('upload.timetable') }}" class="btn btn-primary">Upload New</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Department</th>
                        <th>File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timetables as $timetable)
                        <tr>
                            <td>{{ $timetable->title }}</td>
                            <td>{{ ucfirst($timetable->student_type) }}</td>
                            <td><a href="{{ asset('storage/' . $timetable->file_path) }}" target="_blank">Download</a></td>
                            <td>
                                <!-- Three Dots Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton{{ $timetable->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $timetable->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('timetables.edit', $timetable->id) }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('timetables.destroy', $timetable->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this timetable?');">
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

        <div class="card-footer text-right">
            <a href="{{ route('uploads.view') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

<script src="{{ asset('js/hideError.js') }}"></script>
