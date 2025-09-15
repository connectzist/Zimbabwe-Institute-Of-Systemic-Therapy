@extends('layoutss.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit End of Semester Exam Result</h4>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body">
            <form action="{{ route('advanced_diploma_exam.update', $examResult->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Module -->
                <div class="form-group">
                    <label for="module_id">Module</label>
                    <select name="module_id" id="module_id" class="form-control">
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ $examResult->module_id == $module->id ? 'selected' : '' }}>
                                {{ $module->module_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Exam Mark -->
                <div class="form-group">
                    <label for="exam_mark">Exam Mark</label>
                    <input type="number" name="exam_mark" id="exam_mark" class="form-control" value="{{ $examResult->exam_mark }}">
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Exam</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</div>
@endsection
