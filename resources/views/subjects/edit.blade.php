@extends('layouts.star')

@section('title', 'Edit Subject')

@section('content')

<h1>Edit Subject</h1>

<div class="card">

    <div class="card-body">



        <form action="{{ route('subjects.update', $subject->id) }}" method="POST">



            @csrf

            @method('PUT')



            <div class="mb-3">

                <label>Subject Name</label>



                <input type="text"

                       name="name"

                       class="form-control"

                       value="{{ $subject->name }}">



                @error('name')

                    <span class="text-danger">{{ $message }}</span>

                @enderror

            </div>



            <div class="mb-3">

                <label>Select Class</label>



                <select name="school_class_id" class="form-control">



                    @foreach($classes as $class)

                        <option value="{{ $class->id }}"

                            {{ $subject->school_class_id == $class->id ? 'selected' : '' }}>

                            {{ $class->name }}

                        </option>

                    @endforeach



                </select>



                @error('school_class_id')

                    <span class="text-danger">{{ $message }}</span>

                @enderror

            </div>



            <button type="submit" class="btn btn-primary">

                Update Subject

            </button>



            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">

                Back

            </a>



        </form>



    </div>

</div>

@endsection
