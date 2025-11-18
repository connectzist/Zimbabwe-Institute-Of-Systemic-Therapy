@include('layoutss.cheader')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Certificate Topic Details</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Course Code:</strong> {{ $module->course_code }}
            </div>
            <div class="mb-3">
                <strong>Course Title:</strong> {{ $module->course_title }}
            </div>

            <div class="mb-3">
                <strong>Credits:</strong> {{ $module->credits }}
            </div>
            <div class="form-group text-right">
                <a href="{{ route('certificate.edit', $module->id) }}" class="btn btn-warning">Edit Topic</a>
                <form action="{{ route('certificate.destroy', $module->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Topic</button>
                </form>
                <a href="{{ route('certificate.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
