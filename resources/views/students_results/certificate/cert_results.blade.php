@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Certificate Students Results</h4>

         <!-- Form to Upload -->
         <div class="d-flex justify-content-end mt-3">
            <form action="{{ url('/c_upload') }}" method="get">
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 30px; padding: 8px 10px; font-weight: bold; background-color: #007bff; border: none;">
                    Upload
                </button>
            </form>
        </div>

        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th>Candidate Number</th>
                        <th>Candidate Name</th>
                        <th>Course Name</th>
                        {{-- <th>Course Module</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentsData as $data)
                        <tr>
                            <td>{{ $data['candidate_number'] }}</td> 
                            <td>{{ $data['candidate_name'] }}</td>  
                            <td>{{ $data['course_name'] }}</td>      
                  {{-- <td>{{ $data['course_module'] }}</td>            --}}
                            <td>
                                <a href="{{ route('students_results.certificate_view', $data['id']) }}" class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Back button-->
        <div class="card-footer text-right">
            <a href="{{ route('students_results.students_results') }}" class="btn btn-secondary">Back</a>
        </div>

        </div>
    </div>
</div>
