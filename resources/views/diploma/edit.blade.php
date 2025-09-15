@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Edit Diploma Topic</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('diploma.update', $diplomaModule->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="course_code">Course Code</label>
                    <input type="text" name="course_code" id="course_code" class="form-control" value="{{ old('course_code', $diplomaModule->course_code) }}" required>
                    @if ($errors->has('course_code'))
                        <span class="text-danger">{{ $errors->first('course_code') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="course_title">Course Title</label>
                    <input type="text" name="course_title" id="course_title" class="form-control" value="{{ old('course_title', $diplomaModule->course_title) }}" required>
                    @if ($errors->has('course_title'))
                        <span class="text-danger">{{ $errors->first('course_title') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="module_id">Select Module</label>
                    <select name="module_id" id="module_id" class="form-control" required>
                        <option value="" disabled>Select Module</option>
                        @foreach ($modules as $mod)
                            <option value="{{ $mod->id }}" {{ old('module_id', $diplomaModule->module_id) == $mod->id ? 'selected' : '' }}>
                                {{ $mod->module_name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('module_id'))
                        <span class="text-danger">{{ $errors->first('module_id') }}</span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="credits">Credits</label>
                    <input type="number" name="credits" id="credits" class="form-control" value="{{ old('credits', $diplomaModule->credits) }}" required>
                    @if ($errors->has('credits'))
                        <span class="text-danger">{{ $errors->first('credits') }}</span>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Topic</button>
                <a href="{{ route('diploma.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>
