@extends('layouts.star')
 
@section('title', 'Attendance List')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
@endpush
 
@section('content')
 
<div class="page-header-row">
    <h1>Attendance List</h1>
 
    <a href="{{ route('attendances.create') }}" class="btn btn-primary">
        <i class="mdi mdi-plus"></i> Add Attendance
    </a>
</div>
 
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
 
<div class="card">
 
    <div class="card-header">
        <h3 class="card-title mb-0">All Attendance Records</h3>
    </div>
 
    <div class="card-body">
 
        <div class="table-responsive">
<table id="attendances-table" class="table table-bordered" style="width:100%">
 
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th width="250">Action</th>
                </tr>
            </thead>
 
            <tbody>
                {{-- Rows are rendered entirely by DataTables via AJAX --}}
            </tbody>
 
        </table>
</div>
 
    </div>
 
</div>
 
@endsection
 
@push('scripts')
    <script src="{{ asset('star/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('star/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/app-datatable.js') }}"></script>
 
    <script>
        $(function () {
            AppDataTable.init('#attendances-table', {
                ajaxUrl: @json(route('attendances.datatable')),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'student', name: 'student', orderable: false },
                    { data: 'date', name: 'date' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
 






