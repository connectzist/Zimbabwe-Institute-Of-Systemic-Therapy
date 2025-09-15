@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Upload Book</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('uploads.book.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Book Title Input -->
                <div class="form-group">
                    <label for="title">Book Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Author Name Input -->
                <div class="form-group">
                    <label for="author">Author Name</label>
                    <input type="text" id="author" name="author" class="form-control" value="{{ old('author') }}" required>
                    @error('author')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category Input -->
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" class="form-control" value="{{ old('category') }}" required>
                    @error('category')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ISBN Input -->
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="form-control" value="{{ old('isbn') }}" required>
                    @error('isbn')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Year of Publication Input -->
                <div class="form-group">
                    <label for="year_of_publication">Year of Publication</label>
                    <input type="text" id="year_of_publication" name="year_of_publication" class="form-control" value="{{ old('year_of_publication') }}" required>
                    @error('year_of_publication')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File Input -->
                <div class="form-group">
                    <label for="file">Upload (PDF, DOC, DOCX, or PPT)</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx">
                    @error('file')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-3">Upload</button>
            </form>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('zist.library') }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </div>
</div>
