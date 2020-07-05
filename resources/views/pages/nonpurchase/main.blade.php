@extends('layouts.default')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Non Purchase</h1>
    @can('nonpurchase-create')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('nonpurchase.add') }}" class="btn btn-primary btn-circle">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>
    @endcan
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Non Purchase List</h6>
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
                        <th>Code</th>
                        <th>Type</th>
                        <th>Anggaran</th>
                        <th>Project</th>
                        <th>Date</th>
                        <th>Nominal</th>
                        <th>Payment Status</th>
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
            url: '{{ route("nonpurchase.data-table") }}',
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
        }],
        columns: [
            {data: "id"},
            {data: "number"},
            {data: "type", render: function(data, type, row) {
                switch (data) {
                    case "1":
                        return "Overhead";
                    break;
                    case "2":
                        return "Marketing";
                    break;
                    case "3":
                        return "Plemary";
                    break;
                    case "4":
                        return "Rumah Tangga";
                    break;
                    case "5":
                        return "Lainnya";
                    break;
                }
            }},
            {data: "type_object"},
            {data: "project.name", name: "Project.name", render: function(data, type, row) {
                if (data) {
                    return data;
                }

                return "-";
            }},
            {data: "date"},
            {data: "nominal", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "payment_process_status"},
            {data: "payment_process_status", render: function(data, type, row) {
                if (data == "REJECT") {
                    return "<center>@can('nonpurchase-edit')<button class='btn btn-warning btn-sm' id='edit_btn'>Edit</button>@endcan " +
                    "@can('nonpurchase-delete')<button class='btn btn-danger btn-sm' id='remove_btn'>Delete</button>@endcan</center>";
                }
                return "";
            }},
        ]
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    }).draw();

    $('#DataTable tbody').on('click', '#edit_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        window.location.href = baseUrl + "/nonpurchase/edit/" + data_row.id;
    });

    $('#DataTable tbody').on('click', '#remove_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        var c = confirm('Delete ' + data_row.name + ' ?');
        if (c) {
            $.ajaxSetup({
                headers: {
                    'Authorization':'Basic xxxxxxxxxxxxx',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseUrl + "/nonpurchase/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTable').DataTable().ajax.reload();
                }, error($data) {
                    alert('This Process Is Not Allowed');
                }
            });

        }
    });
});
</script>
@endpush
