@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Book Details</h4>
        </div>

        <div class="card-body">
            <div class="form-label">
                <label for="book_title"><strong>Book Title</strong></label>
                <p id="research_title">{{ $book->title }}</p>
            </div>

            <div class="mb-3">
                <label for="author"><strong>Author</strong></label>
                <p id="author">{{ $book->author }}</p>
            </div>

            <div class="form-group">
                <label for="category"><strong>Category</strong></label>
                <p id="category">{{ $book->category }}</p>
            </div>

            <div class="form-group">
                <label for="isbn"><strong>ISBN</strong></label>
                <p id="isbn">{{ $book->isbn }}</p>
            </div>

            <div class="form-group">
                <label for="year_of_publication"><strong>Year of Publication</strong></label>
                <p id="year_of_publication">{{ $book->year_of_publication }}</p>
            </div>

            <div class="form-group">
                <label for="file"><strong>Uploaded File</strong></label>
                <p id="file">
                    @if($book->file_path)
                        <a href="{{ asset('storage/' . $book->file_path) }}" target="_blank">Download File</a>
                    @else
                        No file uploaded.
                    @endif
                </p>
            </div> 
            
            <a href="{{ route('zist.library') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
