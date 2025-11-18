@include('layoutss.aheader')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Advanced Diploma Topic</h4>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('advanced_diploma.update', $module->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="course_code" class="form-label">Course Code</label>
                    <input type="text" class="form-control @error('course_code') is-invalid @enderror" 
                           id="course_code" name="course_code" 
                           value="{{ old('course_code', $module->course_code) }}" required>
                    @error('course_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="course_title" class="form-label">Course Title</label>
                    <input type="text" class="form-control @error('course_title') is-invalid @enderror" 
                           id="course_title" name="course_title" 
                           value="{{ old('course_title', $module->course_title) }}" required>
                    @error('course_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="module_id" class="form-label">Select Module</label>
                    <select class="form-control @error('module_id') is-invalid @enderror" name="module_id" id="module_id" required>
                        @foreach ($modules as $mod)
                            <option value="{{ $mod->id }}" {{ old('module_id', $module->module_id) == $mod->id ? 'selected' : '' }}>
                                {{ $mod->module_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('module_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="credits" class="form-label">Credits</label>
                    <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                           id="credits" name="credits" 
                           value="{{ old('credits', $module->credits) }}" required min="1">
                    @error('credits')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Topic</button>
                <a href="{{ route('advanced_diploma.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
