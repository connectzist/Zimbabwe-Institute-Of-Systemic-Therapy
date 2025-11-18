@include('layoutss.aheader')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Add New Advanced Diploma Topic</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('advanced_diploma.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="course_code" class="form-label">Course Code</label>
                    <input type="text" class="form-control @error('course_code') is-invalid @enderror" 
                           id="course_code" name="course_code" value="{{ old('course_code') }}" required>
                    @error('course_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="course_title" class="form-label">Course Title</label>
                    <input type="text" class="form-control @error('course_title') is-invalid @enderror"
                           id="course_title" name="course_title" value="{{ old('course_title') }}" required>
                    @error('course_title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="module_id" class="form-label">Select Module</label>
                    <select class="form-control @error('module_id') is-invalid @enderror" 
                            id="module_id" name="module_id" required>
                        <option value="" disabled {{ old('module_id') ? '' : 'selected' }}>Select Module</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                {{ $module->module_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('module_id')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="credits" class="form-label">Credits</label>
                    <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                           id="credits" name="credits" value="{{ old('credits', 10) }}" min="1" required>
                    @error('credits')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Save Topic</button>
                <a href="{{ route('advanced_diploma.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>