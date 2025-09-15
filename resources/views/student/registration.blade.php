@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Registration</h4>
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

            @if($modules->isEmpty())
                <p>No modules available for registration at the moment.</p>
            @else
                <div class="list-group mt-1">
                    @foreach($modules as $module)
                        <a class="list-group-item list-group-item-action">
                            {{ $module->module_name }}
                        </a>

                        <!-- Check if registration is open  -->
                        @if(now()->between($module->reg_start, $module->reg_due))
                            <!-- If the student is already registered -->
                            @if($module->is_registered)
                                <p>You have registered for this module.</p>
                            @else
                                <p>
                                    <strong>Registration for <span>{{ $module->module_name }}</span></strong> is open 
                                    and it closes on <span>{{ \Carbon\Carbon::parse($module->reg_due)->format('F j, Y') }}</span> .
                                </p>
                                <form action="{{ route('student.register.module', ['module' => $module->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Register</button>
                                </form>
                            @endif
                        @else
                            <p>Registration for {{ $module->module_name }} is closed.</p>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
<script src="{{ asset('js/studentHideSuccess.js') }}"></script>
@endsection
