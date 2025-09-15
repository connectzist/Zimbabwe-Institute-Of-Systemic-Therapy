@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Fee Details</h4>
        </div>
        <div class="card-body">
            <h5>Fee Information</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Student Name</th>
                    <td>{{ $fee->student->first_name }} {{ $fee->student->last_name }}</td>
                </tr>
                <tr>
                    <th>Enrolment ID</th>
                    <td>{{ $fee->student->id }}</td>
                </tr>
                <tr>
                    <th>Candidate Number</th>
                    <td>{{ $fee->student->candidate_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td>{{ $fee->student->course ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>${{ $fee->amount }}</td>
                </tr>
                <tr>
                    <th>Fee Description</th>
                    <td>{{ $fee->description }}</td>
                </tr>
                <tr>
                    <th>Payment Date</th>
                    <td>{{ $fee->payment_date }}</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $fee->created_at }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $fee->updated_at }}</td>
                </tr>
            </table>

            <div class="form-group mt-3">
                <a href="{{ route('fees.index') }}" class="btn btn-secondary">Back to All Fees</a>
            </div>
        </div>
    </div>
</div>
