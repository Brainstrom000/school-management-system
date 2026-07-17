@extends('layouts.star')

@section('title', 'Edit Fee')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-0">Edit Fee</h3>
    <a href="{{ route('fees.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="mdi mdi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-7 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body">

                <form method="POST" action="{{ route('fees.update', $fee->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-select" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $fee->student_id == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }} ({{ $student->class }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fee Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $fee->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount (Rs)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $fee->amount) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $fee->due_date->format('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="unpaid" {{ $fee->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ $fee->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Update Fee
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
