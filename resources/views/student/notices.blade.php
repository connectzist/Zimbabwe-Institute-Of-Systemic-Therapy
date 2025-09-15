@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Notices</h4>
        </div>

        <div class="card-body">
            @if($notices->isEmpty())
                <p>No notices available</p>
            @else
                <ul>
                    @foreach($notices as $notice)
                        <li>
                            <strong>{{ $notice->title }}</strong><br>
                            {{ $notice->content }}<br>
                            @if($notice->file_path)
                                <a href="{{ Storage::url($notice->file_path) }}" target="_blank">Download</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
</div>
@endsection
