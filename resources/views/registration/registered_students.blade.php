@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Registered Students</h4>
            <a href="{{ route('registration.create_reg') }}" class="btn btn-primary">Add New</a>
        </div>

        <div class="card-body">

            {{-- Success and error messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Search Form --}}
            <form action="{{ route('registration.registered_students') }}" method="GET" class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by candidate number, name, course, or module" value="{{ request()->input('search') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Sortable Table --}}
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        @php
                            function sort_link($field, $label, $currentField, $currentDirection) {
                                $direction = ($currentField === $field && $currentDirection === 'asc') ? 'desc' : 'asc';
                                $icon = ($currentField === $field) ? ($currentDirection === 'asc' ? '▲' : '▼') : '';
                                $url = request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]);
                                return "<a href='{$url}' class='text-decoration-none text-dark'>{$label} {$icon}</a>";
                            }
                        @endphp

                        <th>{!! sort_link('candidate_number', 'Candidate Number', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('student_name', 'Candidate Name', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('course', 'Course Name', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('module_name', 'Module Registered', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->student->candidate_number }}</td>
                            <td>{{ $registration->student->first_name }} {{ $registration->student->last_name }}</td>
                            <td>{{ $registration->student->course }}</td>
                            <td>{{ $registration->module->module_name ?? 'N/A' }}</td>
                            <td>
                                <!-- 3-dot dropdown menu -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton{{ $registration->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $registration->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('registrations.edit', $registration->id) }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Delete this registration?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i class="fas fa-trash-alt me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No registered students found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="{{ asset('js/hideError.js') }}"></script>
