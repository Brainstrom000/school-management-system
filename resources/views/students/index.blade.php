@extends('layouts.star')

@section('title', 'Students')

@push('styles')
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
@endpush

@section('content')

<div class="page-header-row">
    <h1>Students List</h1>

    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Add Student
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div class="card-header">
        <h3 class="card-title">All Students</h3>
    </div>

    <div class="card-body">

        <table id="students-table" class="table table-bordered table-hover" style="width:100%">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Class</th>
                    <th width="250">Action</th>
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
            AppDataTable.init('#students-table', {
                ajaxUrl: @json(route('students.datatable')),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address' },
                    { data: 'class', name: 'class' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
