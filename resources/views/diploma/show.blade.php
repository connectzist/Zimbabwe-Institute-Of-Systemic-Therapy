@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Diploma Topic Details</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label"><strong>Course Code:</strong></label>
                <p>{{ $module->course_code }}</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><strong>Course Title:</strong></label>
                <p>{{ $module->course_title }}</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><strong>Credits:</strong></label>
                <p>{{ $module->credits }}</p>
            </div>

            <div class="d-flex justify-content-start">
                <a href="{{ route('diploma.edit', $module->id) }}" class="btn btn-warning me-2">Edit</a>
                <form action="{{ route('diploma.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this module?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <a href="{{ route('diploma.index') }}" class="btn btn-secondary ms-2">Back to Topics</a>
            </div>
        </div>
    </div>
</div>
