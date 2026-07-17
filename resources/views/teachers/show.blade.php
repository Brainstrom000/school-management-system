@extends('layouts.star')

@section('title', 'Teacher Details')

@section('content')

<h1>Teacher Details</h1>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Teacher Information</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th>ID</th>
                <td>{{ $teacher->id }}</td>
            </tr>

            <tr>
                <th>Name</th>
                <td>{{ $teacher->user->name }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $teacher->user->email }}</td>
            </tr>

            <tr>
                <th>Subject</th>
                <td>{{ $teacher->subject }}</td>
            </tr>

            <tr>
                <th>Salary</th>
                <td>{{ $teacher->salary }}</td>
            </tr>

        </table>

        <a href="{{ route('teachers.index') }}" class="btn btn-primary">
            Back
        </a>

    </div>

</div>

@endsection
