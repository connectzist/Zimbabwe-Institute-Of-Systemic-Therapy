@extends('student.layouts.app')

@section('content')
        @include('student.layouts.lib_nav')

        <div class="container my-5">
            <h2 class="text-center mb-4 font-weight-bold" style="color: #660033;">Library Rules And Regulations</h2>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body text-dark">
                            <h5 class="card-title text-center mb-3">Library Notice</h5>
                            <p class="text-center fs-5">
                                Kindly find the attached <strong>Rules and Regulations</strong> for the use of <strong>CONNECT E-LIBRARY</strong>.
                            </p>
        
                            <div class="text-center mt-4">
                                <a href="{{ asset('storage/e_library_rules/rules_and_regulations.pdf') }}" 
                                   class="btn btn-outline-primary" 
                                   download>
                                    📥 Download Rules & Regulations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
@endsection
