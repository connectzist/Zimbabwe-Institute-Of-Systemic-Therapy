@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">

        {{-- Show all validation errors at the top --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-header">
            <h4 class="mb-0">Edit Final Evaluation - {{ $evaluation->student->first_name }} {{ $evaluation->student->last_name }}</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('advanced_diploma_results.evaluationsupdate', $evaluation->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Candidate Number (Read-only) --}}
                <div class="form-group">
                    <label>Candidate Number</label>
                    <input type="text" class="form-control" value="{{ $evaluation->student->candidate_number }}" readonly>
                </div>

                {{-- Student Name (Read-only) --}}
                <div class="form-group">
                    <label>Student Name</label>
                    <input type="text" class="form-control" value="{{ $evaluation->student->first_name }} {{ $evaluation->student->last_name }}" readonly>
                </div>

                {{-- ADFT111 Internal --}}
                <div class="form-group">
                    <label for="adft110_internal">Applied Research In Family Therapy</label>
                    <input type="number" name="adft110_internal" id="adft110_internal" class="form-control" value="{{ old('adft110_internal', $evaluation->adft110_internal) }}">
                    @error('adft110_internal')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Final Theory --}}
                <div class="form-group">
                    <label for="final_theory">Final Theory</label>
                    <input type="number" name="final_theory" id="final_theory" class="form-control" value="{{ old('final_theory', $evaluation->final_theory) }}">
                    @error('final_theory')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Clinicals --}}
                <div class="form-group">
                    <label for="clinicals">Clinicals</label>
                    <input type="number" name="clinicals" id="clinicals" class="form-control" value="{{ old('clinicals', $evaluation->clinicals) }}">
                    @error('clinicals')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</div>
@endsection
