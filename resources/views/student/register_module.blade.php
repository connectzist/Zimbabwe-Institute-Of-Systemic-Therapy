@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Registration Requirements</h4>
        </div>
        <div class="card-body">

            <!-- Success section -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error section -->
            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4">
                <h5 class="font-weight-bold">Student Information</h5>
                <ul class="list-unstyled">
                    <li><strong>Name:</strong> {{ $student->name }}</li>
                    <li><strong>Candidate Number:</strong> {{ $candidateNumber }}</li>
                </ul>
            </div>

            <hr>

            @if($selectedModule)
                <div class="mb-4">
                    <h5 class="font-weight-bold">Module Details</h5>
                    <ul class="list-unstyled">
                        <li><strong>Module Name:</strong> {{ $selectedModule->module_name }}</li>
                        <li><strong>Course:</strong> {{ $courseName }}</li>
                    </ul>
                </div>
            @else
                <p>No modules available for registration at this time.</p>
            @endif

            <hr>
            <div class="mb-4">
                <h5 class="font-weight-bold">Fees Information</h5>
                <ul class="list-unstyled">
                    <li><strong>Balance Required:</strong> USD$ {{ $requiredBalance }}</li>
                    <li><strong>Your Balance:</strong> USD$ {{ $totalBalance }}</li>
                </ul>
            </div>

            <hr>
            <div class="alert alert-success mt-4">
                <h6 class="font-weight-bold">Please Note</h6>
                <p>Kindly check the above information and make sure it belongs to you before Registering. If not, please stop and contact us.</p>
            </div>

            <form action="{{ route('student.register') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Register</button>
            </form>

        </div>
    </div>
</div>
<script src="{{ asset('js/studentHideError.js') }}"></script>
@endsection
