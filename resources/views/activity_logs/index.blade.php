@extends('layouts.star')

@section('title', 'Activity Logs')

@section('content')

<div class="d-flex justify-content-between">
    <h1>Activity Logs</h1>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div class="card-header">
        <h3 class="card-title">System Activity Logs</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

                @forelse($logs as $log)

                <tr>

                    <td>{{ $log->id }}</td>

                    <td>{{ $log->user->name ?? 'Deleted User' }}</td>

                    <td>
                        <span class="badge badge-info">
                            {{ ucfirst($log->user->role ?? 'N/A') }}
                        </span>
                    </td>

                    <td>{{ $log->module }}</td>

                    <td>
                        @if($log->action == 'Create')
                            <span class="badge badge-success">Create</span>

                        @elseif($log->action == 'Update')
                            <span class="badge badge-primary">Update</span>

                        @elseif($log->action == 'Delete')
                            <span class="badge badge-warning">Delete</span>

                        @elseif($log->action == 'Restore')
                            <span class="badge badge-info">Restore</span>

                        @elseif($log->action == 'Force Delete')
                            <span class="badge badge-danger">Force Delete</span>

                        @else
                            <span class="badge badge-secondary">
                                {{ $log->action }}
                            </span>
                        @endif
                    </td>

                    <td>{{ $log->description }}</td>

                    <td>{{ $log->created_at->format('d M Y h:i A') }}</td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center">
                        No Activity Logs Found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

        <div class="mt-3">
            {{ $logs->links() }}
        </div>

    </div>

</div>

@endsection
