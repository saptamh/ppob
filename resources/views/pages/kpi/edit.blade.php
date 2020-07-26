@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Create KPI</h1>
                @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    {{ Form::open([
        'id'=>'form-pages',
        'url' => route('kpi.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('employee_id', 'Employee') }}
                {{ Form::hidden('id', $edit['id'])}}
                {{ Form::select('employee_id', $select_box['employees'], $edit['employee_id'], ['class'=>'form-control ', 'placeholder'=>'Select Employee', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('date', 'Date') }}
                {{ Form::text('date', $edit['date'], ['class'=>'form-control ', 'placeholder'=>'Enter Date', 'required'=>'true', 'readonly'=>'false']) }}
            </div>
            <div class="table-responsive">
                <table class="table dt-responsive nowrap table-bordered table-hover table-sm" id="DataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Job</th>
                        <th>Target</th>
                        <th>Work Hour</th>
                        <th>Realisation</th>
                        <th>Achivement</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('job_percentage', 'Work') }}
                {{ Form::number('job_percentage', $edit['job_percentage'], ['class'=>'form-control ', 'placeholder'=>'Enter Work', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('quality_percentage', 'Quality') }}
                {{ Form::number('quality_percentage', $edit['quality_percentage'], ['class'=>'form-control ', 'placeholder'=>'Enter Quality', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('attitude_percentage', 'Attitude') }}
                {{ Form::number('attitude_percentage', $edit['attitude_percentage'], ['class'=>'form-control ', 'placeholder'=>'Enter Attitude', 'required'=>'true']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm btn-block']) }}
        </div>
        <div class="col-lg-6">
            <a href="{{ route('kpi.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('style')
    <link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('themes/vendor/daterangepicker-master/daterangepicker.css') }}" rel="stylesheet">
@endpush
@push('script')
<script src="{{ URL::asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/daterangepicker-master/moment.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/daterangepicker-master/daterangepicker.js') }}"></script>
<script>
$(document).ready(function() {
    $('input[name="date"]').daterangepicker({
        timePicker: false,
        locale:{
        format: 'DD/MM/YYYY'
        }
    });

    $('input[name="date"]').on('apply.daterangepicker', function(ev, picker) {
        $('#DataTable').DataTable().ajax.reload();
        getJobPercentage();
    });

    $("#employee_id").change(function() {
        $('#DataTable').DataTable().ajax.reload();
        getJobPercentage();
    });
    getJobPercentage();
    var t = $('#DataTable').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        searching: false,
        ajax: {
            url: '{{ route("project-daily.data-table-kpi") }}',
            dataType: 'json',
            type: 'post',
            data: function (d) {
                d._token =  "{{csrf_token()}}";
                d.date = $('input[name="date"]').val();
                d.employee_id = $("#employee_id").val();
                d.dt_for = "table";
         }
        },
        columns: [
            {data: "date"},
            {data: "job"},
            {data: "target"},
            {data: "worked_hour"},
            {data: "realisation"},
            {render: function(data, type, row) {
                var percentage = (row.realisation/row.target) * 100;
                return percentage ? percentage + '%' : 0 + '%';
            }},
        ],
    });
});

function getJobPercentage() {
    $.ajax({
        url: '{{ route("project-daily.data-table-kpi") }}',
        dataType: 'json',
        type: 'post',
        data: {
            _token:"{{csrf_token()}}",
            date:$('input[name="date"]').val(),
            employee_id:$("#employee_id").val(),
            dt_for:"input",
        },
        success: function(data) {
            let job_percentage = 0;
            console.log(data.total_hari);
            if (data.total_hari > 0) {
                var qty_perdays = data.total_target / data.total_hari;
                console.log(qty_perdays);

                job_percentage = (data.total_realisation * qty_perdays) / data.total_hari;
                job_percentage = Math.ceil(job_percentage);
            }
            console.log(job_percentage);
            $("#job_percentage").val(job_percentage);
        }
    });
}
</script>
@endpush
