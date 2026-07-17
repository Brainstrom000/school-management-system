@extends('layouts.star')

@section('title', 'Marks List')

@section('content')

<h1>Marks List</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-header">
        <a href="{{ route('marks.create') }}"
           class="btn btn-primary">
            Add Marks
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Total</th>
                    <th>Grade</th>
                    <th width="250">Action</th>
                </tr>
            </thead>

            <tbody>

            @forelse($marks as $mark)

                <tr>

                    <td>{{ $mark->id }}</td>

                    <td>
                        {{ $mark->student->user->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $mark->subject->name }}
                    </td>

                    <td>{{ $mark->marks }}</td>

                    <td>{{ $mark->total_marks }}</td>

                    <td>{{ $mark->grade }}</td>

                    <td>

                        <a href="{{ route('marks.show', $mark->id) }}"
                           class="btn btn-info btn-sm">
                            View
                        </a>

                        <a href="{{ route('marks.edit', $mark->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('marks.destroy', $mark->id) }}"
                              method="POST"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this mark?')">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center">
                        No Marks Found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

        <br>

        {{ $marks->links() }}

    </div>

</div>

@endsection
