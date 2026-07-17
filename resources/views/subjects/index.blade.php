@extends('layouts.star')

@section('title', 'Subjects')

@section('content')

<h1>Subjects List</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-header">

        <a href="{{ route('subjects.create') }}"
           class="btn btn-primary">
            Add Subject
        </a>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Class</th>
                    <th width="250">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($subjects as $subject)

                    <tr>

                        <td>{{ $subject->id }}</td>

                        <td>{{ $subject->name }}</td>

                        <td>{{ $subject->schoolClass->name }}</td>

                        <td>

                            <a href="{{ route('subjects.show', $subject->id) }}"
                               class="btn btn-info btn-sm">
                                View
                            </a>

                            <a href="{{ route('subjects.edit', $subject->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('subjects.destroy', $subject->id) }}"
                                  method="POST"
                                  style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this subject?')">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center">
                            No Subjects Found
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <br>

        {{ $subjects->links() }}

    </div>

</div>

@endsection
