@extends('layouts.star')

@section('title', 'Edit Student')

@section('content')

<h1>Edit Student</h1>

<div class="card">

    <div class="card-header">
        <h3>Edit Student Information</h3>
    </div>

    <form action="{{ route('students.update',$student->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Name</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ $student->user->name }}">
            </div>

            <div class="form-group">
                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ $student->user->email }}">
            </div>

            <div class="form-group">
                <label>Phone</label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ $student->phone }}">
            </div>

            <div class="form-group">
                <label>Address</label>

                <textarea name="address"
                          class="form-control">{{ $student->address }}</textarea>
            </div>

            <div class="form-group">
                <label>Class</label>

                <select name="class" class="form-control">
                    @foreach($classes as $c)
                        <option value="{{ $c->name }}" {{ old('class', $student->class) == $c->name ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">

                <label>Profile Image</label>

                <input type="file"
                       name="profile_image"
                       class="form-control">

                <br>

                @if($student->profile_image)

                    <img src="{{ asset('students/'.$student->profile_image) }}"
                         width="100">

                @endif

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-success">

                Update Student

            </button>

            <a href="{{ route('students.index') }}"
               class="btn btn-secondary">

                Back

            </a>

        </div>

    </form>

</div>

@endsection
