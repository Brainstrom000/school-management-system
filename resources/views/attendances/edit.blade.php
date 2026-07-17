@extends('layouts.star')

@section('title', 'Edit Attendance')

@section('content')

<h1>Edit Attendance</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Select Student</label>

                <select name="student_id" class="form-control">

                    @foreach($students as $student)

                        <option value="{{ $student->id }}"
                            {{ $attendance->student_id == $student->id ? 'selected' : '' }}>

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
                       class="form-control"
                       value="{{ $attendance->date }}">

                @error('date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select name="status" class="form-control">

                    <option value="Present"
                        {{ $attendance->status == 'Present' ? 'selected' : '' }}>
                        Present
                    </option>

                    <option value="Absent"
                        {{ $attendance->status == 'Absent' ? 'selected' : '' }}>
                        Absent
                    </option>

                    <option value="Leave"
                        {{ $attendance->status == 'Leave' ? 'selected' : '' }}>
                        Leave
                    </option>

                </select>

                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Update Attendance
            </button>

            <a href="{{ route('attendances.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </form>

    </div>
</div>

@endsection
