@extends('layouts.star')

@section('title', 'Add Marks')

@section('content')

<h1>Add Marks</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('marks.store') }}" method="POST">

            @csrf

            <div class="mb-3">
                <label>Select Student</label>

                <select name="student_id" class="form-control">
                    <option value="">Select Student</option>

                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->user->name ?? 'Student '.$student->id }}
                        </option>
                    @endforeach
                </select>

                @error('student_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label>Select Subject</label>

                <select name="subject_id" class="form-control">
                    <option value="">Select Subject</option>

                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>

                @error('subject_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label>Obtained Marks</label>

                <input type="number"
                       name="marks"
                       class="form-control">

                @error('marks')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label>Total Marks</label>

                <input type="number"
                       name="total_marks"
                       value="100"
                       class="form-control">

                @error('total_marks')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Save Marks
            </button>

            <a href="{{ route('marks.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </form>

    </div>
</div>

@endsection
