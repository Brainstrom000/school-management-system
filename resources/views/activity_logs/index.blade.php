@extends('layouts.star')

@section('title', 'Activity Logs')

@push('styles')
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
@endpush

@section('content')

<div class="page-header-row">
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

        <table id="activity-logs-table" class="table table-bordered table-hover" style="width:100%">

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
                {{-- Rows are rendered entirely by DataTables via AJAX --}}
            </tbody>

        </table>

    </div>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('star/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('star/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/app-datatable.js') }}"></script>

    <script>
        $(function () {
            AppDataTable.init('#activity-logs-table', {
                ajaxUrl: @json(route('activity.logs.datatable')),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user', name: 'user', orderable: false },
                    { data: 'role', name: 'role', orderable: false, searchable: false },
                    { data: 'module', name: 'module' },
                    { data: 'action', name: 'action', searchable: false },
                    { data: 'description', name: 'description' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });
    </script>
@endpush
