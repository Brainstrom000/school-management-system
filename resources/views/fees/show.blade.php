@extends('layouts.star')

@section('title', 'Fee Details')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-0">Fee Details</h3>
    <a href="{{ route('fees.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="mdi mdi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-7 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body">

                <table class="table table-borderless">
                    <tr>
                        <th width="180">Student</th>
                        <td>{{ $fee->student->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <td>{{ $fee->title }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>Rs {{ number_format($fee->amount, 0) }}</td>
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        <td>{{ $fee->due_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($fee->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-danger">Unpaid</span>
                            @endif
                        </td>
                    </tr>
                    @if($fee->status === 'paid')
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ ucfirst($fee->payment_method) }}</td>
                        </tr>
                        <tr>
                            <th>Transaction ID</th>
                            <td>{{ $fee->transaction_id }}</td>
                        </tr>
                        <tr>
                            <th>Paid At</th>
                            <td>{{ $fee->paid_at?->format('d M Y, h:i A') }}</td>
                        </tr>
                    @endif
                </table>

                @if($fee->status === 'unpaid')
                    <a href="{{ route('fees.pay', $fee->id) }}" class="btn btn-success">
                        <i class="mdi mdi-credit-card-outline"></i> Pay Now
                    </a>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
