@include('layoutss.dheader')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">New Diploma Topic</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('diploma.store') }}" method="POST">
                @csrf

                <!-- Course Code Field -->
                <div class="form-group">
                    <label for="course_code">Course Code</label>
                    <input type="text" name="course_code" id="course_code" class="form-control" required>
                    @if ($errors->has('course_code'))
                        <span class="text-danger">{{ $errors->first('course_code') }}</span>
                    @endif
                </div>

                <!-- Course Title Field -->
                <div class="form-group mt-3">
                    <label for="course_title">Course Title</label>
                    <input type="text" name="course_title" id="course_title" class="form-control" required>
                    @if ($errors->has('course_title'))
                        <span class="text-danger">{{ $errors->first('course_title') }}</span>
                    @endif
                </div>

                <!-- Module Selection -->
                <div class="form-group mt-3">
                    <label for="module_id">Select Module</label>
                    <select name="module_id" id="module_id" class="form-control" required>
                        <option value="" disabled selected>Select Module</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->module_name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('module_id'))
                        <span class="text-danger">{{ $errors->first('module_id') }}</span>
                    @endif
                </div>

                <!-- Credits Field -->
                <div class="form-group mt-3">
                    <label for="credits">Credits</label>
                    <input type="number" name="credits" id="credits" class="form-control" value="10" required>
                    @if ($errors->has('credits'))
                        <span class="text-danger">{{ $errors->first('credits') }}</span>
                    @endif
                </div>

                <!-- Submit & Cancel Buttons -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Save Topic</button>
                    <a href="{{ route('diploma.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>