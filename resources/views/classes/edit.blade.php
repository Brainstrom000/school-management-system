@extends('layouts.star')

@section('title', 'Edit Class')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-0">Edit Class</h3>
    <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="mdi mdi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body">

                <form action="{{ route('classes.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Class Name</label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $class->name) }}">

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
                               value="{{ old('fee_amount', $class->fee_amount) }}">

                        @error('fee_amount')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Update Class
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
