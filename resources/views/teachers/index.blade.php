@extends('layouts.star')

@section('title', 'Teachers')

@section('content')

<div class="d-flex justify-content-between align-items-center">

    <h1>Teachers List</h1>

    <div>

        <a href="{{ route('teachers.trash') }}" class="btn btn-danger">
            <i class="fa fa-trash"></i> Trash
        </a>

        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Teacher
        </a>

    </div>

</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div class="card-header">

        <form method="GET" action="{{ route('teachers.index') }}">

            <div class="row">

                <div class="col-md-4">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search Teacher Name"
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-4">
                    <input type="text"
                           name="subject"
                           class="form-control"
                           placeholder="Filter By Subject"
                           value="{{ request('subject') }}">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary">
                        Search
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('teachers.index') }}"
                       class="btn btn-secondary">
                        Reset
                    </a>
                </div>

            </div>

        </form>

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

                        <a href="{{ route('teachers.show',$teacher) }}"
                           class="btn btn-info btn-sm">
                            View
                        </a>

                        <a href="{{ route('teachers.edit',$teacher) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('teachers.destroy',$teacher) }}"
                              method="POST"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Move this teacher to Trash?')">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center">
                        No Teachers Found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

        <div class="mt-3">
            {{ $teachers->links() }}
        </div>

    </div>

</div>

@endsection
