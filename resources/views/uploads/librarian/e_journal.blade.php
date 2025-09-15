@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Upload Journal</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('library.journal.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Journal Title -->
                <div class="form-group">
                    <label for="title">Journal Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Author -->
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" class="form-control" value="{{ old('author') }}" required>
                    @error('author')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Subject -->
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') }}">
                    @error('subject')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File -->
                <div class="form-group">
                    <label for="file">Upload Journal File (PDF only)</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".pdf" required>
                    @error('file')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Custom URL-->
                <div class="form-group">
                    <label for="url">URL (optional)</label>
                    <input type="text" id="url" name="url" class="form-control" value="{{ old('url') }}">
                    <small class="text-muted">Example: enter "my-journal-2025" to make URL look like /journals/my-journal-2025</small>
                    @error('url')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary mt-3">Upload</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('zist.library') }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>
