@extends('layouts.default')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Project Timeline</h1>
    @can('projectTimeline-create')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('project-timeline.add') }}" class="btn btn-primary btn-circle">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>
    @endcan
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Project Timeline List</h6>
            @if(session()->has('message'))
                <div class="alert alert-success alert-sm">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table dt-responsive nowrap table-bordered table-hover table-sm" id="DataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle;text-align:center;">#</th>
                        <th rowspan="2" style="vertical-align: middle;text-align:center;">Date</th>
                        <th rowspan="2" style="vertical-align: middle;text-align:center;">Project</th>
                        <th rowspan="2" style="vertical-align: middle;text-align:center;">Item</th>
                        <th rowspan="2" style="vertical-align: middle;text-align:center;">Job</th>
                        <th rowspan="2" style="vertical-align: middle;text-align:center;">Zone</th>
                        <th colspan="3" style="text-align:center;">Target</th>
                        <th rowspan="2" style="vertical-align: middle;text-align:center;">%</th>
                        <th colspan="3" style="text-align:center;">Realisation</th>
                        <th rowspan="2"  style="vertical-align: middle;text-align:center;width:150px;">Action</th>
                    </tr>
                    <tr>
                        <th>Qty</th>
                        <th>Duration</th>
                        <th>End Date</th>
                        <th>Qty</th>
                        <th>Duration</th>
                        <th>End Date</th>
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
            url: '{{ route("project-timeline.data-table") }}',
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
            targets: [ 13 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center>@can('projectTimeline-edit')<button class='btn btn-warning btn-sm' id='edit_btn'>Edit</button>@endcan " +
                "@can('projectTimeline-delete')<button class='btn btn-danger btn-sm' id='remove_btn'>Delete</button>@endcan " +
                "@can('projectDaily-list')<button class='btn btn-primary btn-sm' id='daily_rpt_btn'>Daily Work</button>@endcan</center>"
        }],
        columns: [
            {data: "id"},
            {data: "date"},
            {data: "project.name", name: "Project.name"},
            {data: "project_item.name", name: "ProjectItem.name"},
            {data: "project_job.name", name: "ProjectJob.name"},
            {data: "project_zone.name", name: "ProjectZone.name"},
            {data: "qty"},
            {data: "duration", render: function( data, type, row, meta) {
                return data + " hari";
            }},
            {render: function ( data, type, row, meta ) {
                var someDate = new Date(row.date);
                someDate.setDate(someDate.getDate() + parseInt(row.duration)); //number  of days to add, e.x. 15 days
                var dateFormated = someDate.toISOString().substr(0,10);
                return dateFormated;
            }},
            {render: function( data, type, row, meta ) {
                if (row.project_daily[0] && row.qty > 0) {
                    var total_realisation = row.project_daily[0].total_realisation;
                    var calculate = (total_realisation / row.qty) * 100;
                    return calculate ? calculate + '%' : 0 +'%';
                } else {
                    return '0%';
                }
            }},
            {render: function( data, type, row, meta ) {
                if (row.project_daily[0]) {
                    return row.project_daily[0].total_realisation;
                } else {
                    return 0;
                }
            }},
            {render: function( data, type, row, meta ) {
                if (row.project_daily[0]) {
                    var qty = row.project_daily[0].total_realisation;
                    var total_hari = row.project_daily[0].total_hari;
                    var qty_per_day = (row.qty / row.duration) * total_hari;
                    var duration = (qty_per_day - qty) / row.working_hour;

                    var res = duration.toFixed(1);
                    var hour = (res % 1).toFixed(4) * 10;

                    let hari = 0;
                    if (parseInt(res) > 0) {
                        hari = row.duration - parseInt(res);
                    }

                    if (parseInt(res) < 0) {
                        hari = parseInt(row.duration) + parseInt(res);
                    }

                    let jam = hour;
                    if (hour < 0) {
                        console.log(parseInt(row.working_hour));
                        console.log(hour);


                        jam = parseInt(row.working_hour) + hour;
                        hari = hari - 1;
                    }

                        return hari + ' hari ' + jam + ' jam';
                    }
                    return row.duration;
           }},
           {render: function( data, type, row, meta ) {
            if (row.project_daily[0]) {
                    var qty = row.project_daily[0].total_realisation;
                    var total_hari = row.project_daily[0].total_hari;
                    var qty_per_day = (row.qty / row.duration) * total_hari;
                    var duration = (qty_per_day - qty) / row.working_hour;

                    var res = duration.toFixed(1);
                    var hour = (res % 1).toFixed(4) * 10;

                    var someDate = new Date(row.date);
                    someDate.setDate(someDate.getDate() + parseInt(row.duration) + parseInt(res)); //number  of days to add, e.x. 15 days
                    var dateFormated = someDate.toISOString().substr(0,10);

                    return dateFormated;
                } else {
                    return 0;
                }

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
        window.location.href = baseUrl + "/project-timeline/edit/" + data_row.id;
    });

    $('#DataTable tbody').on('click', '#daily_rpt_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        window.location.href = baseUrl + "/project-daily/" + data_row.id;
    });

    $('#DataTable tbody').on('click', '#remove_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        var c = confirm('Delete ' + data_row.name + ' ?');
        if (c) {+
            $.ajaxSetup({
                headers: {
                    'Authorization':'Basic xxxxxxxxxxxxx',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseUrl + "/project-timeline/destroy/" + data_row.id,
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
