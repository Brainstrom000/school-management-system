@extends('layouts.star')

@section('title', 'Deleted Teachers')

@section('content')

<div class="d-flex justify-content-between">

    <h1>Deleted Teachers</h1>

    <a href="{{ route('teachers.index') }}" class="btn btn-primary">
        <i class="fa fa-arrow-left"></i> Back
    </a>

</div>

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Trash Teachers</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Salary</th>
                    <th width="220">Action</th>
                </tr>

            </thead>

            <tbody>

            @forelse($teachers as $teacher)

                <tr>

                    <td>{{ $teacher->id }}</td>

                    <td>{{ $teacher->user->name }}</td>

                    <td>{{ $teacher->user->email }}</td>

                    <td>{{ $teacher->subject }}</td>

                    <td>{{ $teacher->salary }}</td>

                    <td>

                        <form action="{{ route('teachers.restore',$teacher->id) }}"
                              method="POST"
                              style="display:inline-block;">

                            @csrf
                            @method('PUT')

                            <button class="btn btn-success btn-sm">
                                Restore
                            </button>

                        </form>

                        <form action="{{ route('teachers.forceDelete',$teacher->id) }}"
                              method="POST"
                              style="display:inline-block;">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Permanently delete this teacher?')">

                                Delete Permanently

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">

                        Trash is Empty.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
