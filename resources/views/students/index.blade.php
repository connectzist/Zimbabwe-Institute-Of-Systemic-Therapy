@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
            <h4 class="mb-0 text-primary">All Students</h4>
            <a href="{{ route('students.create') }}" class="btn btn-outline-primary">New Student</a>
        </div>

        <!-- Search -->
        <div class="card-body">
            <form action="{{ route('students.index') }}" method="GET" class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, candidate number, email, or course" value="{{ request()->input('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Sortable Table --}}
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        @php
                            function sort_link($field, $label, $currentField, $currentDirection) {
                                $direction = ($currentField === $field && $currentDirection === 'asc') ? 'desc' : 'asc';
                                $icon = $currentField === $field ? ($currentDirection === 'asc' ? '▲' : '▼') : '';
                                $url = request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]);
                                return "<a href='{$url}' class='text-decoration-none text-dark'>{$label} {$icon}</a>";
                            }
                        @endphp

                        <th>{!! sort_link('first_name', 'First Name', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('last_name', 'Last Name', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('candidate_number', 'Candidate Number', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('email', 'Email', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('course', 'Course', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->candidate_number }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->course }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton{{ $student->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $student->id }}">
                                        <li><a class="dropdown-item" href="{{ route('students.show', $student->id) }}"><i class="fas fa-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="{{ route('students.edit', $student->id) }}"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li>
                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i>Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/hideError.js') }}"></script>