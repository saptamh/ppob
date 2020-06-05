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
            <div class="col-lg-12">
                <div class="form-group">
                    {{ Form::label('duration', 'Duration') }}
                    {{ Form::text('duration', '', ['id'=>'duration_id', 'class'=>'form-control', 'placeholder'=>'Enter Duration']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('retention', 'Retention') }}
                    {{ Form::text('retention', '', ['id'=>'retention_id', 'class'=>'form-control', 'placeholder'=>'Enter Retention', 'required'=>'true']) }}
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
            <table class="table table-bordered table-hover" id="DataTableHistory" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Start Date</th>
                    <th>Duration</th>
                    <th>Retention</th>
                    <th>Update At</th>
                    <th>BAST 1</th>
                    <th>BAST 2</th>
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
    var start_date = new Date('{{ $project->start_date }}');
    var tLevel = $('#DataTableHistory').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("log-project.data-table") }}',
            dataType: 'json',
            data:{ _token: "{{csrf_token()}}", project_id: "{{ $project->id }}"}
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
                "<button class='btn btn-danger btn-circle' id='remove_btn' disabled='true'><i class='fas fa-trash'></i></button></center>"
        }],
        columns: [
            {data: "id"},
            {render: function ( data, type, row, meta ) {
                return "{{ $project->start_date }}";
            }},
            {data: "duration"},
            {data: "retention"},
            {data: "updated_at"},
            {render: function ( data, type, row, meta ) {
                console.log('ROW', start_date);
                var someDate = new Date(start_date);
                console.log( row.duration);
                someDate.setDate(someDate.getDate() + parseInt(row.duration)); //number  of days to add, e.x. 15 days
                var dateFormated = someDate.toISOString().substr(0,10);
                return dateFormated;
            }},
            {render: function ( data, type, row, meta ) {
                console.log('ROW', row);
                var someDate = new Date(start_date);
                var duration = parseInt(row.duration) + parseInt(row.retention);
                someDate.setDate(someDate.getDate() + parseInt(duration)); //number  of days to add, e.x. 15 days
                var dateFormated = someDate.toISOString().substr(0,10);
                return dateFormated;
            }},
        ]
    });

    tLevel.on( 'order.dt search.dt', function () {
        tLevel.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    $('#DataTableHistory tbody').on('click', '#edit_btn', function () {
        var data_row = tLevel.row($(this).closest('tr')).data();
        $('#btn-log-form').val('Update').removeAttr('disabled');
        $("#log_id").val(data_row.id);
        $("#start_date_id").val(data_row.start_date);
        $("#duration_id").val(data_row.duration);
        $("#retention_id").val(data_row.retention);
    });

    $('#DataTableHistory tbody').on('click', '#remove_btn', function () {
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
                    $('#DataTableHistory').DataTable().ajax.reload();
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
        $('#btn-log-form').attr('disabled');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('log-project.store') }}",
            method: 'post',
            dataType: 'json',
            data: $('#log-form').serialize() + "&project_id={{ $project->id }}",
            success: function(data){
                $("#log-form input[type='hidden']").val('');
                $('#log-form')[0].reset();
                $('#DataTableHistory').DataTable().ajax.reload();
                $('#btn-log-form').text('Add').removeAttr('disabled');
            }
        });
    });
});
</script>

