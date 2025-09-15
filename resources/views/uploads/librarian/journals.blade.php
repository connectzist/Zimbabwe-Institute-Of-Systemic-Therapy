@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
            <h4 class="mb-0 text-primary">Uploaded Journals</h4>
            <a href="{{ route('library.newJournal') }}" class="btn btn-outline-success me-2">Upload Journal</a>
        </div>

        <!-- Search Bar -->
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('library.journals') }}" method="GET" class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by title, author, or subject" value="{{ request()->input('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>            

            <!-- Journal Table -->
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($journals as $journal)
                        <tr>
                            <td>{{ $journal->title }}</td>
                            <td>{{ $journal->author }}</td>
                            <td>{{ Str::limit($journal->description, 50) }}</td>
                            <td>
                                <!-- Dropdown Menu for Actions -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" id="dropdownJournal{{ $journal->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownJournal{{ $journal->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('library.journal.show', $journal->id) }}">
                                                <i class="fas fa-eye me-2"></i>View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('library.journal.edit', $journal->id) }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('library.journal.delete', $journal->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this journal?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No journals found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $journals->appends(['search' => request()->input('search')])->links() }}
            </div>
        </div>

        <!-- Back Button -->
        <div class="card-footer text-right">
            <a href="{{ route('zist.library') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/hideError.js') }}"></script>
