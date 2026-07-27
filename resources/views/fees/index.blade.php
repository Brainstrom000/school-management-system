@extends('layouts.star')

@section('title', 'Fees')

@push('styles')
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
@endpush

@section('content')

<div class="page-header-row">
    <h3 class="mb-0">{{ auth()->user()->role === 'admin' ? 'Fees Management' : 'My Fees' }}</h3>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('fees.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus"></i> Add Fee
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body">

                <div class="row g-2 mb-4">
                    <div class="col-auto">
                        <select id="status-filter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="fees-table" class="table table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>Student</th>
                                @endif
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th width="260">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows are rendered entirely by DataTables via AJAX --}}
                        </tbody>
                    </table>
                </div>

            </div>
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
            const feesTable = AppDataTable.init('#fees-table', {
                ajaxUrl: @json(route('fees.datatable')),
                extra: {
                    ajax: {
                        url: @json(route('fees.datatable')),
                        type: 'GET',
                        data: function (d) {
                            d.status_filter = $('#status-filter').val();
                        }
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    @if(auth()->user()->role === 'admin')
                        { data: 'student', name: 'student', orderable: false },
                    @endif
                    { data: 'title', name: 'title' },
                    { data: 'amount', name: 'amount', orderable: false },
                    { data: 'due_date', name: 'due_date' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // Re-query the server whenever the status filter changes
            $('#status-filter').on('change', function () {
                feesTable.ajax.reload();
            });
        });
    </script>
@endpush
