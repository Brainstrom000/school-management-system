@extends('layouts.star')
 
@section('title', 'Teachers')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
@endpush
 
@section('content')
 
<div class="page-header-row">
 
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
        <h3 class="card-title">All Teachers</h3>
    </div>
 
    <div class="card-body">
 
        <div class="table-responsive">
<table id="teachers-table" class="table table-bordered table-hover" style="width:100%">
 
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
            AppDataTable.init('#teachers-table', {
                ajaxUrl: @json(route('teachers.datatable')),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'subject', name: 'subject' },
                    { data: 'salary', name: 'salary' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush