@extends('student.layouts.app')

@section('content')
    @include('student.layouts.lib_nav')

    @php use Illuminate\Support\Str; @endphp

    <div class="container my-5">
        <h2 class="text-center mb-4 font-weight-bold" style="color: #660033;">Past Exam Papers</h2>

        <form action="{{ route('student.past_exam_paper') }}" method="GET" class="d-flex justify-content-center mb-4">
            <input
                type="text"
                name="search"
                class="form-control w-50 me-2"
                 placeholder="Search by title, module, course, or year..."
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        @if(request('search'))
            <div class="card shadow-sm mt-5 p-4">
                @if($papers->count() > 0)
                    <h4 class="mb-3 text-dark">Results for "<span class="text-primary">{{ request('search') }}</span>":</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Exam Title</th>
                                <th>Module Name</th>
                                <th>Exam Year</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($papers as $paper)
                                <tr>
                                    <td>{{ $paper->exam_title }}</td>
                                    <td>{{ $paper->module_name }}</td>
                                    <td>{{ $paper->exam_year }}</td>
                                    <td>
                                        @if($paper->file_path)
                                            <a href="{{ asset('storage/' . $paper->file_path) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               download="{{ Str::slug($paper->exam_title) . '.' . pathinfo($paper->file_path, PATHINFO_EXTENSION) }}">
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
                    <p class="text-dark">No exam papers found for "<strong>{{ request('search') }}</strong>"</p>
                @endif
            </div>
        @endif
    </div>
@endsection
