@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Certificate Topics</h4>
            <a href="{{ route('certificate.create') }}" class="btn btn-primary">New Topic</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Credits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($certificateModules->isNotEmpty())
                        @foreach ($certificateModules as $module)
                            <tr>
                                <td>{{ $module->course_code }}</td>
                                <td>{{ $module->course_title }}</td>
                                <td>{{ $module->credits }}</td>
                                <td>
                                    <!-- Bootstrap 5 Dropdown Menu -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('certificate.edit', $module->id) }}">
                                                    <i class="fas fa-edit me-2"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('certificate.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this topic?');" class="m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash-alt me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">No certificate topics found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Back Button -->
        <div class="card-footer text-end">
            <a href="{{ route('course_modules.course_modules') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
