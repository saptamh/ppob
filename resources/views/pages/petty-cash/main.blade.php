@extends('layouts.default')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Cash Flow</h1>
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('petty-cash.add') }}" class="btn btn-primary btn-circle">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cash Flow List</h6>
            @if(session()->has('message'))
                <div class="alert alert-success alert-sm">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover display" id="DataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Budget For</th>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Berita</th>
                        <th>Nominal</th>
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
    <style>
        tr.dtrg-level-2.dtrg-start td:first-child {
            padding-left: 0px;
            color: #000;
            font-style: italic;
        }

        tr.dtrg-level-2.dtrg-end td:first-child {
            padding-left: 0px;
            color: #000;
            font-style: italic;
        }

        tr.dtrg-level-1 td:first-child {
            padding-left: 0px;
            font-weight: bolder;
            color: #000;
        }

        tr.dtrg-level-1.dtrg-end td:first-child {
            padding-left: 0px;
            font-weight: bolder;
            color: #000;
        }
        tr.dtrg-level-0 td:first-child {
            padding-left: 0px;
            font-weight: bolder;
            color: blue;
        }
    </style>
@endpush
@push('script')
<script src="{{ URL::asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/datatables/dataTables.rowGroup.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    var t = $('#DataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("petty-cash.data-table") }}',
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
            targets: [ 2, 3, 4 ],
            visible: false
        },
        {
            targets: [ 7 ],
            visible: true,
            searchable: false,
            sortable: false,
        }],
        columns: [
            {data: "id"},
            {data: "date"},
            {data: "budget_for"},
            {data: "project.name", name: "Project.name", render: function(data, type, row) {
                if (data) {
                    return data;
                }

                return "-";
            }},
            {data: "type"},
            {data: "noted_news"},
            {data: "nominal", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "source_type", render: function(data, type, row) {
                if (!data) {
                    return "<button class='btn btn-danger btn-circle' id='remove_btn'><i class='fas fa-trash'></i></button></center>";
                }
                return "";
            }}
        ],
        rowGroup: {
            endRender: function ( rows, group ) {
                var avg = rows
                .data()
                .pluck('nominal')
                .reduce( function (a, b) {
                    return a + b.replace(/[^\d]/g, '')*1;
                }, 0);
                console.log('GR', group);
                let groupName = group;

                if (group === "No group") {
                    groupName = "Office";
                }
                if (group == "DEBIT" || group == "KREDIT") {
                    return 'Total in '+groupName+': '+
                    $.fn.dataTable.render.number(',', '.', 0, 'Rp. ').display( avg );
                }
            },
            dataSrc: ['budget_for', 'project.name', 'type'],
        },
        orderFixed: [[2, 'asc'], [3, 'asc'], [4, 'asc']],
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    }).draw();

    $('#DataTable tbody').on('click', '#edit_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        window.location.href = baseUrl + "/petty-cash/edit/" + data_row.id;
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
                url: baseUrl + "/petty-cash/destroy/" + data_row.id,
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
