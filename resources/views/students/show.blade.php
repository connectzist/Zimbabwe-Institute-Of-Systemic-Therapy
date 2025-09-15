@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Student Details</h4>
        </div>
        <div class="card-body">
            <h5>Student Information</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Enrolment ID</th>
                    <td>{{ $student->id }}</td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td>{{ $student->first_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>{{ $student->last_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ ucfirst($student->gender) ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Candidate Number</th>
                    <td>{{ $student->candidate_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $student->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td>{{ $student->course ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Enrollment Date</th>
                    <td>{{ $student->enrollment_date ? \Carbon\Carbon::parse($student->enrollment_date)->format('d-m-Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Cell Number</th>
                    <td>{{ $student->cell_No ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $student->address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Group</th>
                    <td>{{ $student->group ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Occupation</th>
                    <td>{{ $student->occupation ?? 'N/A' }}</td>
                </tr>
                <!-- New Row for ID Number -->
                <tr>
                    <th>ID Number</th>
                    <td>{{ $student->id_number ?? 'N/A' }}</td>
                </tr>
                <!-- New Row for Nationality -->
                <tr>
                    <th>Nationality</th>
                    <td>{{ $student->nationality ?? 'N/A' }}</td>
                </tr>
                <!-- New Row for Emergency Contact -->
                <tr>
                    <th>Emergency Contact</th>
                    <td>{{ $student->emergency_contact ?? 'N/A' }}</td>
                </tr>
                <!-- New Row for Profile Picture -->
                <tr>
                    <th>Profile Picture</th>
                    <td>
                        @if($student->profile_picture)
                            <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="Profile Picture" class="img-fluid" style="max-width: 150px; height: auto;">
                        @else
                            <span>N/A</span>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="form-group mt-3">
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to Students</a>
            </div>
        </div>
    </div>
</div>
