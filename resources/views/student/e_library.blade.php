@extends('student.layouts.app')

@section('content')
    @include('student.layouts.lib_nav')

    <div class="text-center my-5">
        <h1 style="color: #660033;" class="display-4 font-weight-bold">The Institute Library</h1>
    </div>

    {{-- A-Z Filter --}}
    <div class="container mb-4">
        <div class="p-3 rounded shadow-sm bg-light text-center">
            <h5 class="mb-3" style="color: #660033;">Filter Books by Alphabet</h5>
            @foreach (range('A', 'Z') as $char)
                <a href="{{ route('student.e_library', ['letter' => $char]) }}"
                class="mx-2 d-inline-block fw-semibold"
                style="font-size: 1.25rem; color: {{ request('letter') === $char ? '#660033' : '#333' }};">
                {{ $char }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Book List --}}
    <div class="container">
        @if($books->count())
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center mb-4" style="color: #660033;">Available Books</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($books as $book)
                        <li class="list-group-item text-dark">
                            📘 {{ $book->title }}
                            <a href="{{ asset('storage/' . $book->file_path) }}"
                                class="btn btn-sm btn-outline-primary"
                                download="{{ $book->title }}">
                                Download
                             </a>
                             
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $books->appends(['letter' => request('letter')])->links() }}
                </div>
            </div>
        </div>
    
        @else
        <div class="container mb-4">
            <div id="no-books-message" class="pt-5">
                <p class="text-center fw-semibold mt-5" style="font-size: 1.25rem; color: #000;">
                    No books found{{ $letter ? " starting with '$letter'" : '' }}.
                </p>
            </div>
        </div>
           
        {{---hide script --}}
        <script>
            setTimeout(() => {
                const msg = document.getElementById('no-books-message');
                if (msg) {
                    msg.style.display = 'none';
                }
            }, 5000);
        </script>        
        
        @endif
    </div>
</div>

@endsection
