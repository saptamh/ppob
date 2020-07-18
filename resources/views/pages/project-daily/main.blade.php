@extends('layouts.default')

@section('content')
<?php
    $timeline_date = \Carbon\Carbon::createFromFormat('Y-m-d', $timeline->date);
    $end_date = $timeline_date->addDays($timeline->duration)->toDateString();
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Project Daily</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Project Timeline
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <p><b>Project:</b> {{$timeline->project->name}}</p>
                            <p><b>Job:</b> {{$timeline->projectJob->name}}</p>
                            <p><b>Item:</b> {{$timeline->projectItem->name}}</p>
                            <p><b>Zone:</b> {{$timeline->projectZone->name}}</p>
                        </div>
                        <div class="col-lg-6">
                            <p><b>Start Date:</b> {{$timeline->date}}</p>
                            <p><b>Duration:</b> {{$timeline->duration}}</p>
                            <p><b>End Date:</b> {{$end_date}}</p>
                            <p><b>Quantity:</b> {{$timeline->qty}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    @can('role-create')
    <div class="row">
        <div class="col-lg-6">
            <a href="{{ route('project-daily.add', ['timeline_id'=>$timeline_id]) }}" class="btn btn-primary btn-circle">
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <div class="col-lg-6 text-right">
            <a href="{{ route('project-timeline.main', ['timeline_id'=>$timeline_id]) }}" class="btn btn-warning">
                Back To Project Timeline
            </a>
        </div>
    </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Project Daily List</h6>
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
                                <th>#</th>
                                <th>Date</th>
                                <th>PIC</th>
                                <th>Job</th>
                                <th>Target</th>
                                <th>Work Hour</th>
                                <th>Realisation</th>
                                <th>Achivement</th>
                                <th>Description</th>
                                <th style="width: 150px;">Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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
            url: '{{ route("project-daily.data-table", ["timeline_id"=>$timeline_id]) }}',
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
            targets: [ 9 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center>@can('projectDaily-edit')<button class='btn btn-warning btn-sm' id='edit_btn'>Edit</button>@endcan " +
                "@can('projectDaily-delete')<button class='btn btn-danger btn-sm' id='remove_btn'>Delete</button>@endcan</center>"
        }],
        columns: [
            {data: "id"},
            {data: "date"},
            {data: "employee.name", name: "Employee.name"},
            {data: "job"},
            {data: "target"},
            {data: "worked_hour"},
            {data: "realisation"},
            {render: function(data, type, row) {
                var percentage = (row.realisation/row.target) * 100;
                return percentage ? percentage + '%' : 0 + '%';
            }},
            {data: "description"},
        ]
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    }).draw();

    $('#DataTable tbody').on('click', '#edit_btn', function () {
        var data_row = t.row($(this).closest('tr')).data();
        window.location.href = baseUrl + "/project-daily/edit/" + data_row.project_timeline.id + "/" + data_row.id;
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
                url: baseUrl + "/project-daily/destroy/" + data_row.id,
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
