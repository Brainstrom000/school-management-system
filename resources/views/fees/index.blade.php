@extends('layouts.star')

@section('title', 'Fees')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-0">{{ auth()->user()->role === 'admin' ? 'Fees Management' : 'My Fees' }}</h3>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('fees.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus"></i> Add Fee
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body">

                <form method="GET" action="{{ route('fees.index') }}" class="row g-2 mb-4">
                    <div class="col-auto">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>Student</th>
                                @endif
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th width="260">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fees as $fee)
                                <tr>
                                    <td>{{ $fee->id }}</td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>{{ $fee->student->user->name ?? 'N/A' }}</td>
                                    @endif
                                    <td>{{ $fee->title }}</td>
                                    <td>Rs {{ number_format($fee->amount, 0) }}</td>
                                    <td>{{ $fee->due_date->format('d M Y') }}</td>
                                    <td>
                                        @if($fee->status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('fees.show', $fee->id) }}" class="btn btn-info btn-sm">View</a>

                                        @if($fee->status === 'unpaid')
                                            <a href="{{ route('fees.pay', $fee->id) }}" class="btn btn-success btn-sm">Pay Now</a>
                                        @endif

                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('fees.edit', $fee->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                            <form action="{{ route('fees.destroy', $fee->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this fee record?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Fee Records Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $fees->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
