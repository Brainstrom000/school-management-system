@extends('layouts.star')

@section('title', 'Add Attendance')

@section('content')

<h1>Add Attendance</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('attendances.store') }}" method="POST">

            @csrf

            <div class="mb-3">
                <label>Select Student</label>

                <select name="student_id" class="form-control">

                    <option value="">Select Student</option>

                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->user->name }}
                        </option>
                    @endforeach

                </select>

                @error('student_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label>Date</label>

                <input type="date"
                       name="date"
                       class="form-control">

                @error('date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Leave">Leave</option>
                </select>

                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Save Attendance
            </button>

            <a href="{{ route('attendances.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </form>

    </div>
</div>

@endsection
