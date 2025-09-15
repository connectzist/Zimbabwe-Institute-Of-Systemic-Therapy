@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
            <h4 class="mb-0 text-primary">Past Exam Papers</h4>
            <a href="{{ route('library.newPastExamPaper') }}" class="btn btn-outline-primary">Upload Exam Paper</a>
        </div>

        <!-- Body -->
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Search Bar -->
            <form action="{{ route('library.past_exam_papers') }}" method="GET" class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by title, module, or department" value="{{ request()->input('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Exam Title</th>
                        <th>Department</th>
                        <th>Module Name</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pastExamPapers as $paper)
                        <tr>
                            <td>{{ $paper->exam_title }}</td>
                            <td>{{ $paper->course->title ?? 'N/A' }}</td>
                            <td>{{ $paper->module_name ?? 'N/A' }}</td>
                            <td>{{ $paper->exam_year }}</td>
                            <td>
                                <!-- Dropdown for Actions -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" id="dropdownPaper{{ $paper->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownPaper{{ $paper->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('library.past_exam_papers.show', $paper->id) }}">
                                                <i class="fas fa-eye me-2"></i> View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('library.past_exam_papers.edit', $paper->id) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('library.past_exam_papers.delete', $paper->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this paper?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i class="fas fa-trash me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No past exam papers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $pastExamPapers->appends(['search' => request()->input('search')])->links() }}
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-right">
            <a href="{{ route('zist.library') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/hideError.js') }}"></script>
