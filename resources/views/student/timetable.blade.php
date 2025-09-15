@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Timetable(s)</h4>
        </div>

        <div class="card-body">
            <!-- List Group for Timetables -->
            @if($timetables->isEmpty())
                <p>No Timetables at the moment.</p>
            @else
                <div class="list-group mt-1">
                    @foreach($timetables as $timetable)
                        <a href="{{ asset('storage/' . $timetable->file_path) }}" class="list-group-item list-group-item-action" target="_blank">
                            {{ basename($timetable->file_path) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
