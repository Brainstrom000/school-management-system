@extends('layouts.star')

@section('title', 'Subject Details')

@section('content')

<h1>Subject Details</h1>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Subject Information</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th>ID</th>
                <td>{{ $subject->id }}</td>
            </tr>

            <tr>
                <th>Subject Name</th>
                <td>{{ $subject->name }}</td>
            </tr>

            <tr>
                <th>Class</th>
                <td>{{ $subject->schoolClass->name }}</td>
            </tr>

        </table>

        <br>

        <a href="{{ route('subjects.index') }}"
           class="btn btn-primary">
            Back
        </a>

    </div>

</div>

@endsection
