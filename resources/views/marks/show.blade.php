@extends('layouts.star')

@section('title', 'View Marks')

@section('content')

<h1>Marks Details</h1>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th>Student</th>
                <td>{{ $mark->student->user->name ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th>Subject</th>
                <td>{{ $mark->subject->name }}</td>
            </tr>

            <tr>
                <th>Obtained Marks</th>
                <td>{{ $mark->marks }}</td>
            </tr>

            <tr>
                <th>Total Marks</th>
                <td>{{ $mark->total_marks }}</td>
            </tr>

            <tr>
                <th>Grade</th>
                <td>{{ $mark->grade }}</td>
            </tr>

            <tr>
                <th>Percentage</th>
                <td>
                    {{ round(($mark->marks / $mark->total_marks) * 100, 2) }}%
                </td>
            </tr>

        </table>

        <br>

        <a href="{{ route('marks.index') }}"
           class="btn btn-secondary">
            Back
        </a>

        <a href="{{ route('marks.edit', $mark->id) }}"
           class="btn btn-warning">
            Edit
        </a>

    </div>
</div>

@endsection
