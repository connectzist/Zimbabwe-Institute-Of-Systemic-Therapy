@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Journal Details</h4>
        </div>

        <div class="card-body">
            <div class="form-label">
                <label for="journal_title"><strong>Journal Title</strong></label>
                <p id="journal_title">{{ $journal->title }}</p>
            </div>

            <div class="mb-3">
                <label for="author"><strong>Author</strong></label>
                <p id="author">{{ $journal->author }}</p>
            </div>

            <div class="form-group">
                <label for="subject"><strong>Subject</strong></label>
                <p id="subject">{{ $journal->subject }}</p>
            </div>

            <div class="form-group">
                <label for="description"><strong>Description</strong></label>
                <p id="description">{{ $journal->description }}</p>
            </div>

            <div class="form-group">
                <label for="published"><strong>Published Date</strong></label>
                <p id="published">{{ $journal->published_at->format('F d, Y') }}</p>
            </div>

            <div class="form-group">
                <label for="file"><strong>Uploaded File</strong></label>
                <p id="file">
                    @if($journal->file_path)
                        <a href="{{ asset('storage/' . $journal->file_path) }}" target="_blank">Download File</a>
                    @else
                        No file uploaded.
                    @endif
                </p>
            </div>

            <a href="{{ route('library.journals') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
