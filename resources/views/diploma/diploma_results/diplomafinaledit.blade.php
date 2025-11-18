@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Diploma Result</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('diploma_results.finalmoduleupdate', $diplomaResult->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="module_id">Course Module</label>
                    <select name="module_id" id="module_id" class="form-control" required>
                        <option value="" disabled>Select Module</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->id }}" {{ $diplomaResult->module_id == $module->id ? 'selected' : '' }}>
                                {{ $module->module_name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('module_id'))
                        <span class="text-danger">{{ $errors->first('module_id') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="research_mark">Research Mark</label>
                    <input type="number" name="research_mark" id="research_mark" class="form-control" min="0" max="100" value="{{ $diplomaResult->research_mark }}" required>
                </div>

                <div class="form-group">
                    <label for="practical_mark">Practical Mark</label>
                    <input type="number" name="practical_mark" id="practical_mark" class="form-control" min="0" max="100" value="{{ $diplomaResult->practical_mark }}" required>
                </div>

                <div class="form-group">
                    <label for="exam_mark">Exam Mark</label>
                    <input type="number" name="exam_mark" id="exam_mark" class="form-control" min="0" max="100" value="{{ $diplomaResult->exam_mark }}" required>
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
