@extends('layouts.star')
 
@section('title', 'Classes')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
@endpush
 
@section('content')
 
<div class="page-header-row">
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
 
        <div class="table-responsive">
<table id="classes-table" class="table table-bordered" style="width:100%">
 
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Class Name</th>
                    <th>Monthly Fee</th>
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
            AppDataTable.init('#classes-table', {
                ajaxUrl: @json(route('classes.datatable')),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'fee_amount', name: 'fee_amount', orderable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush