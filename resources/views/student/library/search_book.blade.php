@extends('student.layouts.app')

@section('content')
    @include('student.layouts.lib_nav')

    @php use Illuminate\Support\Str; @endphp

    <div class="container my-5">
        <h2 class="text-center mb-4" style="color: #660033;">Search Book</h2>

        <form action="{{ route('student.search_book') }}" method="GET" class="d-flex justify-content-center mb-4">
            <input
                type="text"
                name="search"
                class="form-control w-50 me-2"
                placeholder="Enter book title..."
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        @if(request('search'))
            <div class="card shadow-sm mt-5 p-4">
                @if($books->count() > 0)
                    <h4 class="text-dark" >Search Results:</h4>
                    <table class="table table-bordered mt-3">
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
                                               class="btn btn-sm btn-success"
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
                <p class="text-dark">No books found for "<strong>{{ request('search') }}</strong>"</p>
                @endif
            </div>
        @endif
    </div>
@endsection
