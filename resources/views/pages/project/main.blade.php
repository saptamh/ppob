@extends('layouts.default')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Projects</h1>
    @can('project-create')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('project.add') }}" class="btn btn-primary btn-circle">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>
    @endcan
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Project List</h6>
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
                        <th>Value</th>
                        <th>Progress(%)</th>
                        <th>Progress Values</th>
                        <th>BAST 1</th>
                        <th>BAST 2</th>
                        <th width="100px;">Action</th>
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
            url: '{{ route("project.data-table") }}',
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
            targets: [ 2,3,4,5,6 ],
            visible: true,
            searchable: false,
            sortable: false,
        },
        {
            targets: [ 7 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center>@can('project-edit')<button class='btn btn-warning btn-sm' id='edit_btn'>Edit</button>@endcan " +
                "@can('project-delete')<button class='btn btn-danger btn-sm' id='remove_btn'>Delete</button>@endcan</center>"
        }],
        columns: [
            {data: "id"},
            {data: "name"},
            {data: "project_value.value", name: "ProjectValue.value", render: function(data, type, row) {
                if(row.project_value) {
                    return row.project_value.value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
                }

                return 0;
            }},
            {data: "project_progress.total_progress", name: "ProjectProgress.total_progress", render: function(data, type, row) {
                if (row.project_progress) {
                    var progress =  Math.ceil(row.project_progress.total_progress);
                    return progress > 100 ? 100 : progress;
                }
                return 0;
            }},
            {data: "project_progress.total_result", name: "ProjectProgress.total_result", render: function(data, type, row) {
                if (row.project_progress) {
                    return row.project_progress.total_result.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
                }
                return 0;
            }},
            {data: "project_historical.duration", render: function(data, type, row) {
                if (row.project_historical) {
                    var someDate = new Date(row.start_date);
                    someDate.setDate(someDate.getDate() + parseInt(row.project_historical.duration)); //number  of days to add, e.x. 15 days
                    var dateFormated = someDate.toISOString().substr(0,10);
                    return dateFormated;
                }
                return 0;
            }},
            {data: "project_historical.retention", render: function(data, type, row) {
                if (row.project_historical) {
                    var someDate = new Date(row.start_date);
                    var duration = parseInt(row.project_historical.duration) + parseInt(row.project_historical.retention);
                    someDate.setDate(someDate.getDate() + parseInt(duration)); //number  of days to add, e.x. 15 days
                    var dateFormated = someDate.toISOString().substr(0,10);
                    return dateFormated;
                }
                return 0;
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
        window.location.href = baseUrl + "/project/edit/" + data_row.id;
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
                url: baseUrl + "/project/destroy/" + data_row.id,
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
