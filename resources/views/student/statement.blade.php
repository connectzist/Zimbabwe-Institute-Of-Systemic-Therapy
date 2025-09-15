@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Fees Statement</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <h5 class="mb-0">
                    Total Balance: 
                    <span class="{{ $totalBalance >0 ? 'text-danger' : 'text-success' }}">
                        {{ $totalBalance }} USD
                    </span>
                </h5>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fees as $fee)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($fee->payment_date)->format('Y-m-d') }}</td>
                        <td>{{ $fee->description }}</td>
                        <td>{{ $fee->amount }} USD</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
