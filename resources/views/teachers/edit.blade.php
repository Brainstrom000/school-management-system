@extends('layouts.star')

@section('title', 'Edit Teacher')

@section('content')

<h1>Edit Teacher</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('teachers.update', $teacher) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Name</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ $teacher->user->name }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ $teacher->user->email }}">
            </div>

            <div class="mb-3">
                <label>Subject</label>
                <input type="text"
                       name="subject"
                       class="form-control"
                       value="{{ $teacher->subject }}">
            </div>

            <div class="mb-3">
                <label>Salary</label>
                <input type="number"
                       name="salary"
                       class="form-control"
                       value="{{ $teacher->salary }}">
            </div>

            <button class="btn btn-primary">
                Update Teacher
            </button>

        </form>

    </div>
</div>

@endsection
