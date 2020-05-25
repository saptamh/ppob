<link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        {{ Form::open([
            'id'=>'log-form',
            'url' => route('log-project.store'),
            'method' => 'post',
            'class' => 'form',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        ]) }}
        {{ Form::hidden('id', '', ['id'=>'log_id']) }}
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('point', 'Point (Nilai)') }}
                    {{ Form::text('point', '', ['id'=>'point_id', 'class'=>'form-control', 'placeholder'=>'Enter Point', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('duration', 'Duration') }}
                    {{ Form::text('duration', '', ['id'=>'duration_id', 'class'=>'form-control', 'placeholder'=>'Enter Duration']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('weight', 'Weight (Bobot)') }}
                    {{ Form::text('weight', '', ['id'=>'weight_id', 'class'=>'form-control', 'placeholder'=>'Enter Weight', 'required'=>'true']) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('bast', 'BAST') }}
                    {{ Form::text('bast', '', ['id'=>'bast_id', 'class'=>'form-control', 'placeholder'=>'Enter BAST', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('retention', 'Retention') }}
                    {{ Form::text('retention', '', ['id'=>'retention_id', 'class'=>'form-control', 'placeholder'=>'Enter Retention', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('date', 'Date') }}
                    {{ Form::text('date', '', ['id'=>'date_id', 'class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                {{ Form::submit('Add!', ['id'=>'btn-log-form', 'class'=>'btn btn-success btn-sm btn-block', 'data-loading-text'=>'Loading...']) }}
            </div>
            <div class="col-lg-6">
                {{ Form::button('Reset!', ['class'=>'btn btn-warning btn-sm btn-block', 'type'=>'reset']) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="DataTableLog" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Point</th>
                    <th>Duration</th>
                    <th>Weight</th>
                    <th>BAST</th>
                    <th>Retention</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#date_id').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });

    var tLevel = $('#DataTableLog').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("log-project.data-table") }}',
            dataType: 'json',
            data:{ _token: "{{csrf_token()}}", project_id: "{{ $project_id }}"}
        },
        columnDefs: [{
            targets: [ 0 ],
            visible: true,
            searchable: false,
            sortable: false,
        },
        {
            targets: [ 7 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center><button class='btn btn-warning btn-circle' id='edit_btn'><i class='fas fa-edit'></i></button> " +
                "<button class='btn btn-danger btn-circle' id='remove_btn'><i class='fas fa-trash'></i></button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "point"},
            {data: "duration"},
            {data: "weight"},
            {data: "bast"},
            {data: "retention"},
            {data: "date"},
        ]
    });

    tLevel.on( 'order.dt search.dt', function () {
        tLevel.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    $('#DataTableLog tbody').on('click', '#edit_btn', function () {
        var data_row = tLevel.row($(this).closest('tr')).data();
        $('#btn-log-form').val('Update');
        $("#log_id").val(data_row.id);
        $("#point_id").val(data_row.point);
        $("#duration_id").val(data_row.duration);
        $("#weight_id").val(data_row.weight);
        $("#bast_id").val(data_row.bast);
        $("#date_id").val(data_row.date);
        $("#retention_id").val(data_row.retention);
    });

    $('#DataTableLog tbody').on('click', '#remove_btn', function () {
        var data_row = tLevel.row($(this).closest('tr')).data();
        var c = confirm('Delete data ?');
        if (c) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/log-project/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTableLog').DataTable().ajax.reload();
                }, error($data) {
                    alert('This Process Is Not Allowed');
                }
            });
        }
    });

    $('#log-form').on('reset', function() {
        $("#log-form input[type='hidden']").val('');
        $('#log-form')[0].reset();
        $('#btn-log-form').val('Add');
    });

    $('#log-form').submit(function(e){
        e.preventDefault();
        $('#btn-log-form').attr('disabled',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('log-project.store') }}",
            method: 'post',
            dataType: 'json',
            data: $('#log-form').serialize() + "&project_id={{ $project_id }}",
            success: function(data){
                $("#log-form input[type='hidden']").val('');
                $('#log-form')[0].reset();
                $('#DataTableLog').DataTable().ajax.reload();
                $('#btn-log-form').text('Add').removeAttr('disabled');
            }
        });
    });
});
</script>

