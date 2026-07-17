@extends('layouts.star')

@section('title', 'Add Student')

@section('content')

<h1>Add Student</h1>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Student Information</h3>
    </div>

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="row mt-3">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">

                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">

                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="form-group mt-3">
                <label>Address</label>

                <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>

                @error('address')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label>Class</label>

                <select name="class" class="form-control">
                    <option value="">Select Class</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->name }}" {{ old('class') == $c->name ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>

                @error('class')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label>Profile Image</label>

                <input type="file" name="profile_image" class="form-control">

                @error('profile_image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

        </div>

        <div class="card-footer">

            <button type="submit" class="btn btn-success">
                Save Student
            </button>

            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection
