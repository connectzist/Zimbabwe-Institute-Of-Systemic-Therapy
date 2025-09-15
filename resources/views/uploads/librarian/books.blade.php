@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
            <h4 class="mb-0 text-primary">Uploaded Books</h4>
            <a href="{{ route('library.newBook') }}" class="btn btn-outline-primary">Upload Book</a>
        </div>

        <!-- Search -->
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Search Form -->
            <form action="{{ route('library.all.books') }}" method="GET" class="mt-3">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by title, author, category, or ISBN" value="{{ request()->input('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Sortable Table -->
            <table class="table table-striped table-hover mt-3">
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

                        <th>{!! sort_link('title', 'Title', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('author', 'Author', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('category', 'Category', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('isbn', 'ISBN', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('year_of_publication', 'Published Date', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->category }}</td>
                            <td>{{ $book->isbn }}</td>
                            <td>{{ $book->year_of_publication }}</td>
                            <td>
                                <!-- 3-dots dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton{{ $book->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $book->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('uploads.book.show', $book->id) }}">
                                                <i class="fas fa-eye me-2"></i>View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('library.book.edit_book', $book->id) }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('uploads.book.delete', $book->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
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
                        <tr>
                            <td colspan="6" class="text-center text-muted">No books found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $books->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-right">
            <a href="{{ route('zist.library') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="{{ asset('js/hideError.js') }}"></script>
