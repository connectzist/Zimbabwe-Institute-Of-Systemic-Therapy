@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    {{-- Error --}}
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Student</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required>
                    @if ($errors->has('first_name'))
                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" required>
                    @if ($errors->has('last_name'))
                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @if ($errors->has('gender'))
                        <span class="text-danger">{{ $errors->first('gender') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="candidate_number">Candidate Number</label>
                    <input type="text" name="candidate_number" id="candidate_number" class="form-control" value="{{ old('candidate_number', $student->candidate_number) }}" required>
                    @if ($errors->has('candidate_number'))
                        <span class="text-danger">{{ $errors->first('candidate_number') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="course_id">Select Course</label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        <option value="" disabled selected>Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('course_id'))
                        <span class="text-danger">{{ $errors->first('course_id') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth', $student->date_of_birth) }}" required>
                </div>

                <div class="form-group">
                    <label for="enrollment_date">Enrollment Date</label>
                    <input type="date" name="enrollment_date" id="enrollment_date" class="form-control" value="{{ old('enrollment_date', $student->enrollment_date) }}" required>
                </div>

                <div class="form-group">
                    <label for="cell_No">Cell Number (e.g., +263712345678)</label>
                    <input type="text" name="cell_No" id="cell_No" class="form-control" value="{{ old('cell_No', $student->cell_No) }}" required placeholder="e.g., +263712345678">
                    @if ($errors->has('cell_No'))
                        <span class="text-danger">{{ $errors->first('cell_No') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $student->address) }}" required>
                    @if ($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="group">Group</label>
                    <input type="text" name="group" id="group" class="form-control" value="{{ old('group', $student->group) }}" required>
                    @if ($errors->has('group'))
                        <span class="text-danger">{{ $errors->first('group') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="occupation">Occupation</label>
                    <input type="text" name="occupation" id="occupation" class="form-control" value="{{ old('occupation', $student->occupation) }}" required>
                    @if ($errors->has('occupation'))
                        <span class="text-danger">{{ $errors->first('occupation') }}</span>
                    @endif
                </div>

                 <div class="form-group">
                    <label for="id_number">ID Number</label>
                    <input type="text" name="id_number" id="id_number" class="form-control" value="{{ old('id_number', $student->id_number) }}" required>
                    @if ($errors->has('id_number'))
                        <span class="text-danger">{{ $errors->first('id_number') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="nationality">Nationality</label>
                    <input type="text" name="nationality" id="nationality" class="form-control" value="{{ old('nationality', $student->nationality) }}">
                    @if ($errors->has('nationality'))
                        <span class="text-danger">{{ $errors->first('nationality') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="emergency_contact">Next of Kin Contact</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" value="{{ old('emergency_contact', $student->emergency_contact) }}">
                    @if ($errors->has('emergency_contact'))
                        <span class="text-danger">{{ $errors->first('emergency_contact') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                    @if ($errors->has('profile_picture'))
                        <span class="text-danger">{{ $errors->first('profile_picture') }}</span>
                    @endif
                    @if($student->profile_picture)
                        <p>Current Profile Picture: <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="Profile Picture" width="100px"></p>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success mt-3">Update Student</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary mt-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // This script updates the email field based on the candidate number entered by the user
    document.getElementById('candidate_number').addEventListener('input', function() {
        let candidateNumber = this.value.toLowerCase(); // Convert to lowercase
        let email = candidateNumber + '@connect.org.zw';
        document.getElementById('email').value = email;
    });
</script>
