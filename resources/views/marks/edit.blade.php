@extends('layouts.star')

@section('title', 'Edit Marks')

@section('content')

<h1>Edit Marks</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('marks.update', $mark->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Select Student</label>

                <select name="student_id" class="form-control">

                    @foreach($students as $student)
                        <option value="{{ $student->id }}"
                            {{ $mark->student_id == $student->id ? 'selected' : '' }}>
                            {{ $student->user->name ?? 'Student '.$student->id }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label>Select Subject</label>

                <select name="subject_id" class="form-control">

                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            {{ $mark->subject_id == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label>Obtained Marks</label>

                <input type="number"
                       name="marks"
                       class="form-control"
                       value="{{ $mark->marks }}">
            </div>

            <div class="mb-3">
                <label>Total Marks</label>

                <input type="number"
                       name="total_marks"
                       class="form-control"
                       value="{{ $mark->total_marks }}">
            </div>

            <button type="submit" class="btn btn-primary">
                Update Marks
            </button>

            <a href="{{ route('marks.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </form>

    </div>
</div>

@endsection
