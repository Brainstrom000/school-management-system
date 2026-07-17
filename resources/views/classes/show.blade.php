@extends('layouts.star')

@section('title', 'Class Details')

@section('content')

<h1>Class Details</h1>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Class Information</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th>ID</th>
                <td>{{ $class->id }}</td>
            </tr>

            <tr>
                <th>Class Name</th>
                <td>{{ $class->name }}</td>
            </tr>

            <tr>
                <th>Monthly Fee</th>
                <td>Rs {{ number_format($class->fee_amount, 0) }}</td>
            </tr>

        </table>

        <a href="{{ route('classes.index') }}" class="btn btn-primary">
            Back
        </a>

    </div>
</div>

@endsection
