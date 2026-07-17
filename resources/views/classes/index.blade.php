@extends('layouts.star')

@section('title', 'Classes')

@section('content')

<div class="d-flex justify-content-between">
    <h1>Classes List</h1>

    <a href="{{ route('classes.create') }}" class="btn btn-primary">
        Add Class
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div class="card-header">
        <h3 class="card-title">All Classes</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Class Name</th>
                    <th>Monthly Fee</th>
                    <th width="220">Action</th>
                </tr>
            </thead>

            <tbody>

            @forelse($classes as $class)

                <tr>
                    <td>{{ $class->id }}</td>
                    <td>{{ $class->name }}</td>
                    <td>Rs {{ number_format($class->fee_amount, 0) }}</td>

                    <td>
                        <a href="{{ route('classes.show', $class) }}"
                           class="btn btn-info btn-sm">
                            View
                        </a>

                        <a href="{{ route('classes.edit', $class) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('classes.destroy', $class) }}"
                              method="POST"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">
                                Delete
                            </button>

                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" class="text-center">
                        No Classes Found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

        <div class="mt-3">
            {{ $classes->links() }}
        </div>

    </div>

</div>

@endsection
