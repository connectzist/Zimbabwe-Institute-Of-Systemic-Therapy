@extends('student.layouts.app')

@section('content')
    @include('student.layouts.lib_nav')

    @php use Illuminate\Support\Str; @endphp

    <div class="container my-5">
        <h2 class="text-center mb-4 font-weight-bold" style="color: #660033;">Book Categories</h2>

        <form action="{{ route('library.categories') }}" method="GET" class="d-flex justify-content-center mb-4">
            <input
                type="text"
                name="search"
                class="form-control w-50 me-2"
                placeholder="Enter category you want..."
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        @if(request('search'))
            <div class="card shadow-sm mt-5 p-4">
                @if($books->count() > 0)
                    <h4 class="mb-3 text-dark">Books under "<span class="text-primary">{{ request('search') }}</span>" category:</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>ISBN</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->isbn }}</td>
                                    <td>
                                        @if($book->file_path)
                                            <a href="{{ asset('storage/' . $book->file_path) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               download="{{ Str::slug($book->title) . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION) }}">
                                                Download
                                            </a>
                                        @else
                                            <span class="text-muted">No file</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-dark">No books found in "<strong>{{ request('search') }}</strong>" category.</p>
                @endif
            </div>
        @endif
    </div>
@endsection
