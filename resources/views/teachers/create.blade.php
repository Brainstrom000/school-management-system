@extends('layouts.star')

@section('title', 'Add Teacher')

@section('content')

<h1>Add Teacher</h1>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Teacher Information</h3>
    </div>

    <form action="{{ route('teachers.store') }}" method="POST">

        @csrf

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <label>Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}">

                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label>Email</label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email') }}">

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-6">
                    <label>Password</label>

                    <input type="password"
                           name="password"
                           class="form-control">

                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label>Subject</label>

                    <input type="text"
                           name="subject"
                           class="form-control"
                           value="{{ old('subject') }}">

                    @error('subject')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <br>

            <label>Salary</label>

            <input type="number"
                   name="salary"
                   class="form-control"
                   value="{{ old('salary') }}">

            @error('salary')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>

        <div class="card-footer">

            <button class="btn btn-success">
                Save Teacher
            </button>

            <a href="{{ route('teachers.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection
