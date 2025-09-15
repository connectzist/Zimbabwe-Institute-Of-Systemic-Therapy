@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success" id="success-message">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Student Payments</h4>
            <a href="{{ route('fees.create') }}" class="btn btn-primary">New Fee</a>
        </div>

        <div class="card-body">

            <!-- Search -->
            <form action="{{ route('fees.index') }}" method="GET" class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by candidate number, amount, description, or status" value="{{ request()->input('search') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Sortable Table -->
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        @php
                            function sort_link($field, $label, $currentField, $currentDirection) {
                                $direction = ($currentField === $field && $currentDirection === 'asc') ? 'desc' : 'asc';
                                $icon = ($currentField === $field) ? ($currentDirection === 'asc' ? '▲' : '▼') : '';
                                $url = request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]);
                                return "<a href='{$url}' class='text-decoration-none text-dark'>{$label} {$icon}</a>";
                            }
                        @endphp

                        <th>{!! sort_link('course_id', 'Candidate Number', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('amount', 'Amount', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('description', 'Description', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('payment_date', 'Payment Date', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>{!! sort_link('status', 'Status', $sortField ?? '', $sortDirection ?? '') !!}</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($fees as $fee)
                        <tr>
                            <td>{{ $fee->student->candidate_number ?? 'N/A' }}</td>
                            <td>${{ number_format($fee->amount, 2) }}</td>
                            <td>{{ $fee->description }}</td>
                            <td>{{ \Carbon\Carbon::parse($fee->payment_date)->format('d-m-Y') }}</td>
                            <td>
                                @if($fee->status)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">Unpaid</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('fees.show', $fee->id) }}">
                                                <i class="fas fa-eye me-2"></i> View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('fees.edit', $fee->id) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('fees.destroy', $fee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash-alt me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No fee records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $fees->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/success-message.js') }}"></script>
<script src="{{ asset('js/hideError.js') }}"></script>
