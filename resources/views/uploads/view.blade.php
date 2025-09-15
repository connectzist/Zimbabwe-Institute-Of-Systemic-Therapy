@extends('layouts.app')

@section('content')
<div class="container-fluid my-5">
    <div class="card shadow-lg">
        <div class="card-header">
            <h4>Student Uploads</h4>
        </div>

        <!-- Navigation Bar -->
        <div class="card-body">
            <nav class="nav nav-pills nav-fill mb-4">
                <a class="nav-item nav-link rounded-pill py-2 px-3 bg-light text-primary font-weight-bold hover-shadow" href="{{ route('timetables.view') }}">Timetables</a>
                <a class="nav-item nav-link rounded-pill py-2 px-3 bg-light text-primary font-weight-bold hover-shadow" href="{{ route('notices.view') }}">Notices</a>
                <a class="nav-item nav-link rounded-pill py-2 px-3 bg-light text-primary font-weight-bold hover-shadow" href="{{ route('zist.library') }}">Libray</a>
                
            </nav>
        </div>
    </div>
</div>
