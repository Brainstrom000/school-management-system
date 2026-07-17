@extends('layouts.star')

@section('title', 'Deleted Students')

@section('content')

<div class="d-flex justify-content-between">
    <h1>Deleted Students</h1>

    <a href="{{ route('students.index') }}" class="btn btn-primary">
        <i class="fa fa-arrow-left"></i> Back to Students
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Soft Deleted Students</h3>
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
                    <th>Class</th>
                    <th width="220">Action</th>
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

                    <td>{{ $student->user->name ?? 'N/A' }}</td>

                    <td>{{ $student->user->email ?? 'N/A' }}</td>

                    <td>{{ $student->phone }}</td>

                    <td>{{ $student->class }}</td>

                    <td>

                        <form action="{{ route('students.restore',$student->id) }}"
                              method="POST"
                              style="display:inline;">

                            @csrf
                            @method('PUT')

                            <button class="btn btn-success btn-sm">
                                Restore
                            </button>

                        </form>

                        <form action="{{ route('students.forceDelete',$student->id) }}"
                              method="POST"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete permanently?')">
                                Delete Permanently
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center">
                        No Deleted Students Found.
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
