@extends('layouts.star')

@section('title', 'Attendance List')

@section('content')

<h1>Attendance List</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-header">

        <a href="{{ route('attendances.create') }}"
           class="btn btn-primary">
            Add Attendance
        </a>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th width="250">Action</th>
                </tr>
            </thead>

            <tbody>

            @forelse($attendances as $attendance)

                <tr>

                    <td>{{ $attendance->id }}</td>

                    <td>{{ $attendance->student->user->name }}</td>

                    <td>{{ $attendance->date }}</td>

                    <td>
                        {{ $attendance->status }}
                    </td>

                    <td>

                        <a href="{{ route('attendances.show', $attendance->id) }}"
                           class="btn btn-info btn-sm">
                            View
                        </a>

                        <a href="{{ route('attendances.edit', $attendance->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('attendances.destroy', $attendance->id) }}"
                              method="POST"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete attendance record?')">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center">
                        No Attendance Records Found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

        <br>

        {{ $attendances->links() }}

    </div>

</div>

@endsection
