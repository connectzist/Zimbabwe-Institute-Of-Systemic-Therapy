@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Results</h4>
        </div>

        <div class="card-body">
            <!-- List Group for Programs -->
            <div class="list-group mt-1">
                <a href="{{ route('program.details', ['selected_programme' => $course]) }}" class="list-group-item list-group-item-action">
                    {{ $course }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
