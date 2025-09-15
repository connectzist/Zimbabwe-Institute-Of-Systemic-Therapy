@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">All Courses</h4>
            <div>
                <a href="{{ route('courses.create') }}" class="btn btn-primary">New Course</a>
                <a href="{{ route('modules.view_modules') }}" class="btn btn-secondary ms-2">Modules</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Duration</th>
                        <th>Instructor</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($courses->isNotEmpty())
                        @foreach ($courses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->duration }}</td>
                                <td>{{ $course->instructor }}</td>
                                <td>
                                    <!-- Bootstrap 5 Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('courses.show', $course->id) }}"><i class="fas fa-eye me-2"></i>View</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('courses.edit', $course->id) }}"><i class="fas fa-edit me-2"></i>Edit</a>
                                            </li>
                                            <li>
                                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="m-0 p-0">
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
                    @else
                        <tr>
                            <td colspan="4" class="text-center">No course data found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
