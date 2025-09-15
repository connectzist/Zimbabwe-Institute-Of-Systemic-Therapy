@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Book</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('library.book.update', $book->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" id="author" name="author" class="form-control" value="{{ old('author', $book->author) }}" required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" id="category" name="category" class="form-control" value="{{ old('category', $book->category) }}">
                </div>

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="form-control" value="{{ old('isbn', $book->isbn) }}">
                </div>

                <div class="mb-3">
                    <label for="year_of_publication" class="form-label">Year of Publication</label>
                    <input type="number" id="year_of_publication" name="year_of_publication" class="form-control" value="{{ old('year_of_publication', $book->year_of_publication) }}">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('library.all.books') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success">Update Book</button>
                </div>
            </form>
        </div>
    </div>
</div>
