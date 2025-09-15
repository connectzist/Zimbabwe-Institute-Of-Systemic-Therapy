@extends('student.layouts.app')

@section('content')
    @include('student.layouts.lib_nav')

    @php use Illuminate\Support\Str; @endphp

    <div class="container my-5">
        <h2 class="text-center mb-4 font-weight-bold" style="color: #660033;">E Journals</h2>

        <form action="{{ route('library.e_journals') }}" method="GET" class="d-flex justify-content-center mb-4">
            <input
                type="text"
                name="search"
                class="form-control w-50 me-2"
                placeholder="Enter journal title..."
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        @if(request('search'))
            <div class="card shadow-sm mt-5 p-4">
                @if($journals->count() > 0)
                    <h4 class="mb-3 text-dark">Journals matching "<span class="text-primary">{{ request('search') }}</span>":</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Subject</th>
                                <th>Published</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($journals as $journal)
                                <tr>
                                    <td>{{ $journal->title }}</td>
                                    <td>{{ $journal->author }}</td>
                                    <td>{{ $journal->subject }}</td>
                                    <td>{{ $journal->published_at ? $journal->published_at->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        @if($journal->file_path)
                                            <a href="{{ asset('storage/' . $journal->file_path) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               download="{{ Str::slug($journal->title) . '.' . pathinfo($journal->file_path, PATHINFO_EXTENSION) }}">
                                                Download
                                            </a>
                                        @elseif($journal->url)
                                            <a href="{{ $journal->url }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                View Online
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
                    <p class="text-dark">No journals found for "<strong>{{ request('search') }}</strong>"</p>
                @endif
            </div>
        @endif
    </div>
@endsection
