@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Student Results</h4>
        </div>

        <!-- Navigation Bar -->
        <div class="card-body">
            <nav class="nav nav-pills nav-fill mb-4">
                <a class="nav-item nav-link" href="{{ route('students_results.certificate.cert_results') }}" >Certificate Results</a>
                <a class="nav-item nav-link" href="{{ route('students_results.diploma.diploma_results') }}">Diploma Results</a>
                <a class="nav-item nav-link" href="{{ route('students_results.advanced_diploma.advanced_diplomaresults') }}">Advanced Diploma Results</a>
            </nav>
        </div>
    </div>
</div>
