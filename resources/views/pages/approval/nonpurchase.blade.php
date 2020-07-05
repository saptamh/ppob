@extends('layouts.default')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Nonpurchase</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Nonpurchase List</h6>
            @if(session()->has('message'))
                <div class="alert alert-success alert-sm">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" id="DataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Payment Total</th>
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
            url: '{{ route("approval.nonpurchase") }}',
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
            targets: [ 4 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center><button class='btn btn-warning btn-sm' id='edit_btn'>Approve</button> " +
                "<button class='btn btn-danger btn-sm' id='remove_btn'>Reject</button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "payment_name"},
            {data: "payment_type"},
            {data: "payment_total", render: $.fn.dataTable.render.number( '.', '.', 0, '' )},,
        ]
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    }).draw();

    $('#DataTable tbody').on('click', '#edit_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        var c = confirm('Approve ' + data_row.payment_name + ' ?');
        if (c) {
            var data = {
                id: data_row.id,
                type: "nonpurchase",
                status: "APPROVED",
                payment_id: data_row.payment_id,
                reason: '',
            };
            approvalAjax(data);
        }
    });

    $('#DataTable tbody').on('click', '#remove_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        var c = confirm('Reject ' + data_row.payment_name + ' ?');
        if (c) {
           var reason = prompt("Please enter your reason", "");
           var data = {
                id: data_row.id,
                type: "nonpurchase",
                status: "REJECT",
                payment_id: data_row.payment_id,
                reason: reason,
            };
            approvalAjax(data);
        }
    });
});

function approvalAjax(data) {
    $.ajaxSetup({
        headers: {
            'Authorization':'Basic xxxxxxxxxxxxx',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: baseUrl + "/approval/store/",
        method: 'post',
        data: data,
        success: function(data){
            $('#DataTable').DataTable().ajax.reload();
        }, error($data) {
            alert('This Process Is Not Allowed');
        }
    });
}
</script>
@endpush
