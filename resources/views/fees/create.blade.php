@extends('layouts.star')

@section('title', 'Add Fee')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-0">Add Fee</h3>
    <a href="{{ route('fees.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="mdi mdi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-7 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body">

                <form method="POST" action="{{ route('fees.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                        data-fee="{{ $classFees[$student->class] ?? 0 }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }} ({{ $student->class }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fee Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Tuition Fee - August 2026" value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount (Rs)</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
                        <small class="text-muted">Auto-filled from the student's class fee — you can override it.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Save Fee
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('student_id').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const fee = selected.getAttribute('data-fee');
        const amountField = document.getElementById('amount');

        if (fee && parseFloat(fee) > 0 && !amountField.value) {
            amountField.value = fee;
        }
    });
</script>

@endsection
