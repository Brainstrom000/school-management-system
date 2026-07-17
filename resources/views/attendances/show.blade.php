@extends('layouts.star')

@section('title', 'Attendance Details')

@section('content')

<h1>Attendance Details</h1>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Attendance Information</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th>ID</th>
                <td>{{ $attendance->id }}</td>
            </tr>

            <tr>
                <th>Student</th>
                <td>{{ $attendance->student->user->name }}</td>
            </tr>

            <tr>
                <th>Date</th>
                <td>{{ $attendance->date }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>{{ $attendance->status }}</td>
            </tr>

        </table>

        <br>

        <a href="{{ route('attendances.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </div>

</div>

@endsection
