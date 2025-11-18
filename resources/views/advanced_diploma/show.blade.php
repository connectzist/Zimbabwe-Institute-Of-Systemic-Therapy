@include('layoutss.aheader')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Advanced Diploma Topic Details</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Course Code:</strong> {{ $module->course_code }}
            </div>
            <div class="mb-3">
                <strong>Course Title:</strong> {{ $module->course_title }}
            </div>
            <div class="mb-3">
                <strong>Credits:</strong> {{ $module->credits }}
            </div>
            <a href="{{ route('advanced_diploma.index') }}" class="btn btn-secondary">Back to Topics</a>
        </div>
    </div>
</div>
