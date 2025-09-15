@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Research Documents</h4>
            <a href="{{ route('research.new') }}" class="btn btn-primary">Upload New</a>
        </div>

        <div class="card-body">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Research Table --}}
            <table class="table table-striped table-hover mt-3">
                <thead>
                    <tr>
                        <th>Research Title</th>
                        <th>Researcher</th>
                        <th>Year</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($researches as $research)
                        <tr>
                            <td>{{ $research->research_title }}</td>
                            <td>{{ $research->researcher }}</td>
                            <td>{{ $research->year_of_research }}</td>
                            <td>{{ ucfirst($research->student_type) }}</td>
                            <td>
                                <!-- 3-Dots Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton{{ $research->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $research->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('uploads.research.show', $research->id) }}">
                                                <i class="fas fa-eye me-2"></i>View
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('uploads.research.delete', $research->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this research?');">
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
                            <td colspan="5" class="text-center text-muted">No research documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Back Button --}}
        <div class="card-footer text-right">
            <a href="{{ route('uploads.view') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>


<script src="{{ asset('js/hideError.js') }}"></script>
