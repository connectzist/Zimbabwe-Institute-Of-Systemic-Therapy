@extends('student.layouts.app')

@section('content')
    @include('student.layouts.lib_nav')

    @php use Illuminate\Support\Str; @endphp

    <div class="container my-5">
        <h2 class="text-center mb-4 font-weight-bold" style="color: #660033;">Student Research Repository</h2>

        {{-- Search + Filters --}}
        <form action="{{ route('student.researches') }}" method="GET" class="row justify-content-center mb-4 g-2">

            {{-- Search Field --}}
            <div class="col-md-4">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search by title, researcher..."
                    value="{{ request('search') }}"
                >
            </div>

            {{-- Year Dropdown --}}
            <div class="col-md-2">
                <select name="year" class="form-select">
                    <option value="">All Years</option>
                    @foreach($allYears as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Student Type Dropdown --}}
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($allTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        {{-- Results --}}
        @if(request()->anyFilled(['search', 'year', 'type']))
            <div class="card shadow-sm mt-4 p-4">
                @if($researches->count() > 0)
                    <h5 class="mb-3">Showing {{ $researches->count() }} result(s)</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Researcher</th>
                                <th>Year</th>
                                <th>Student Type</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($researches as $research)
                                <tr>
                                    <td>{{ $research->research_title }}</td>
                                    <td>{{ $research->researcher }}</td>
                                    <td>{{ $research->year_of_research }}</td>
                                    <td>{{ $research->student_type }}</td>
                                    <td>
                                        @if($research->file_path)
                                            <a href="{{ asset('storage/' . $research->file_path) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-info me-1">
                                                Preview
                                            </a>
                                            <a href="{{ asset('storage/' . $research->file_path) }}"
                                               download="{{ Str::slug($research->research_title) . '.' . pathinfo($research->file_path, PATHINFO_EXTENSION) }}"
                                               class="btn btn-sm btn-outline-primary">
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
                    <p class="text-dark mb-0">No research found matching your filters.</p>
                @endif
            </div>
        @endif
    </div>
@endsection
