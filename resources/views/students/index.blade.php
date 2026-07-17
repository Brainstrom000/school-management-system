@extends('layouts.star')

@section('title', 'Students')

@section('content')

<div class="d-flex justify-content-between">
    <h1>Students List</h1>

    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Add Student
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div class="card-header">
        <h3 class="card-title">All Students</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Class</th>
                    <th width="250">Action</th>
                </tr>
            </thead>

            <tbody>

            @forelse($students as $student)

                <tr>

                    <td>{{ $student->id }}</td>

                    <td>
                        @if($student->profile_image)
                            <img src="{{ asset('students/'.$student->profile_image) }}"
                                 width="60"
                                 height="60"
                                 class="rounded-circle">
                        @else
                            No Image
                        @endif
                    </td>

                    <td>{{ $student->user->name }}</td>

                    <td>{{ $student->user->email }}</td>

                    <td>{{ $student->phone }}</td>

                    <td>{{ $student->address }}</td>

                    <td>{{ $student->class }}</td>

                    <td>

                        <a href="{{ route('students.show',$student->id) }}"
                           class="btn btn-info btn-sm">
                            View
                        </a>

                        <a href="{{ route('students.edit',$student->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('students.destroy',$student->id) }}"
                              method="POST"
                              style="display:inline-block;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this student?')">

                                Delete

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" class="text-center">
                        No Students Found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

        <div class="mt-3">
            {{ $students->links() }}
        </div>

    </div>

</div>

@endsection
