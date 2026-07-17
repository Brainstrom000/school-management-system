@extends('layouts.star')

@section('title', 'Add Class')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-0">Add Class</h3>
    <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="mdi mdi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body">

                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Class Name</label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="e.g. Class 6">

                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Monthly Fee (Rs)</label>

                        <input type="number"
                               step="0.01"
                               name="fee_amount"
                               class="form-control"
                               value="{{ old('fee_amount') }}"
                               placeholder="e.g. 5000">

                        <small class="text-muted">Standard fee for students in this class. Used to auto-fill fee amounts.</small>

                        @error('fee_amount')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Save Class
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
