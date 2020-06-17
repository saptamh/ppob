@extends('layouts.default')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Payment</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment List</h6>
            @if(session()->has('message'))
                <div class="alert alert-success alert-sm">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="DataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Project</th>
                        <th>Status</th>
                        <th>Nominal</th>
                        <th>Source</th>
                        <th>Paid Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
    <link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('script')
<script src="{{ URL::asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    var t = $('#DataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("payment.data-table") }}',
            dataType: 'json',
            data:{ _token: "{{csrf_token()}}"}
        },
        columnDefs: [{
            targets: [ 0 ],
            visible: true,
            searchable: false,
            sortable: false,
        },
        {
            targets: [ 8 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center><button class='btn btn-warning btn-sm' id='edit_btn'>Detail</button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "payment_name"},
            {data: "payment_type"},
            {data: "project.name", name: "Project.name", render: function(data, type, row) {
                if (data) {
                    return data;
                }

                return "-";
            }},
            {data: "payment_status"},
            {data: "payment_total", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "source.name", name: 'Source.name', render: function(data, type, row) {
                if (data) {
                    return data;
                }

                return "OFFICE";
            }},
            {data: "paid_date"},
        ],
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    }).draw();

    $('#DataTable tbody').on('click', '#edit_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        window.location.href = baseUrl + "/payment/edit/" + data_row.id;
    });

});
</script>
@endpush
