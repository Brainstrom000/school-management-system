@extends('layouts.star')

@section('title', 'Student Details')

@section('content')

<h1>Student Details</h1>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th>ID</th>
                <td>{{ $student->id }}</td>
            </tr>

            <tr>
                <th>Name</th>
                <td>{{ $student->user->name }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $student->user->email }}</td>
            </tr>

            <tr>
                <th>Phone</th>
                <td>{{ $student->phone }}</td>
            </tr>

            <tr>
                <th>Address</th>
                <td>{{ $student->address }}</td>
            </tr>

            <tr>
                <th>Class</th>
                <td>{{ $student->class }}</td>
            </tr>

            <tr>
                <th>Profile Image</th>
                <td>
                    @if($student->profile_image)
                        <img src="{{ asset('students/'.$student->profile_image) }}"
                             width="100">
                    @else
                        No Image
                    @endif
                </td>
            </tr>

        </table>

        <br>

        <a href="{{ route('students.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </div>
</div>

@endsection
