@extends('layouts.star')

@section('title', 'Add Subject')

@section('content')

<h1>Add Subject</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('subjects.store') }}" method="POST">

            @csrf

            <div class="mb-3">
                <label>Subject Name</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Enter Subject Name">

                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label>Select Class</label>

                <select name="school_class_id" class="form-control">

                    <option value="">Select Class</option>

                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">
                            {{ $class->name }}
                        </option>
                    @endforeach

                </select>

                @error('school_class_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Save Subject
            </button>

        </form>

    </div>
</div>

@endsection
