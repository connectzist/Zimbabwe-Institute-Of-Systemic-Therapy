@include('layoutss.aheader')
@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Diploma Result</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('advanced_diploma_results.update', $advanceddiplomaResult->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="course_module">Course Module</label>
                    <select name="course_module" id="course_module" class="form-control">
                        @foreach($courseModules as $module)
                            <option value="{{ $module->id }}" {{ $advanceddiplomaResult->courseModule->id == $module->id ? 'selected' : '' }}>
                                {{ $module->course_title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="mark">Mark</label>
                    <input type="number" name="mark" id="mark" class="form-control" value="{{ $advanceddiplomaResult->mark }}">
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Result</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>
@endsection
