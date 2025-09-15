@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Journal</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('library.journal.update', $journal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Journal Title -->
                <div class="form-group">
                    <label for="title">Journal Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $journal->title) }}" required>
                    @error('title')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Author -->
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" class="form-control" value="{{ old('author', $journal->author) }}" required>
                    @error('author')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Subject -->
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject', $journal->subject) }}">
                    @error('subject')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control">{{ old('description', $journal->description) }}</textarea>
                    @error('description')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File Upload  -->
                <div class="form-group">
                    <label for="file">Replace Journal File</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".pdf">
                    <small class="text-muted">Leave blank if you don't want to change the file.</small>
                    @error('file')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- URL Slug -->
                <div class="form-group">
                    <label for="url">Custom URL (optional)</label>
                    <input type="text" id="url" name="url" class="form-control" value="{{ old('url', $journal->url) }}">
                    <small class="text-muted">Example: "my-journal-2025" → /journals/my-journal-2025</small>
                    @error('url')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-success mt-3">Update Journal</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('library.journals') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</div>
