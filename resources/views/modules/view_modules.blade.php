@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Modules List</h4>
            <a href="{{ route('modules.create_module') }}" class="btn btn-primary">New Module</a>
        </div>

        <div class="card-body">

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Module Name</th>
                        <th>Course</th>
                        <th>Registration Start</th>
                        <th>Registration Due</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $module)
                        <tr>
                            <td>{{ $module->module_name }}</td>
                            <td>{{ $module->course->title }}</td>
                            <td>{{ $module->reg_start }}</td>
                            <td>{{ $module->reg_due }}</td>
                            <td>
                                <!-- Bootstrap 5 Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('modules.edit', $module->id) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this module?');" class="m-0 p-0">
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
                </tbody>
            </table>

            <a href="{{ route('courses.index') }}" class="btn btn-secondary float-end">Back</a>
        </div>
    </div>
</div>

<script src="{{ asset('js/hideError.js') }}"></script>