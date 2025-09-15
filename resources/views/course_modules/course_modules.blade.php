@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Course Modules</h4>
        </div>

        <!-- Navigation Bar  -->
        <div class="card-body">
            <nav class="nav nav-pills nav-fill mb-4">
                <a class="nav-item nav-link" href="{{ route('certificate.index') }}" >Certificate Topics</a>
                <a class="nav-item nav-link" href="{{ route('diploma.index') }}">Diploma Topics</a>
                <a class="nav-item nav-link" href="{{ route('advanced_diploma.index') }}">Advanced Diploma Topics</a>
            </nav>
        </div>
    </div>
</div>
