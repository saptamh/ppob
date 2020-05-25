<link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        {{ Form::open([
            'id'=>'level-form',
            'url' => route('employee-level.store'),
            'method' => 'post',
            'class' => 'form',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        ]) }}
        {{ Form::hidden('id', '', ['id'=>'level_id']) }}
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {{ Form::label('start_date', 'Start Date') }}
                    {{ Form::text('start_date', '', ['id'=>'start_date_id', 'class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('end_date', 'End Date') }}
                    {{ Form::text('end_date', '', ['id'=>'end_date_id', 'class'=>'form-control', 'placeholder'=>'Enter Date']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('level', 'Level') }}
                    {{ Form::text('level', '', ['id'=>'level_id', 'class'=>'form-control', 'placeholder'=>'Enter Level', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::submit('Add!', ['id'=>'btn-level-form', 'class'=>'btn btn-success btn-sm', 'data-loading-text'=>'Loading...']) }}
                    {{ Form::button('Reset!', ['class'=>'btn btn-warning btn-sm', 'type'=>'reset']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="DataTableLevel" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Level</th>
                    <th>Start Date</th>
                    <th>End Date</th>
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
    $('#start_date_id').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });

    $('#end_date_id').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });

    var tLevel = $('#DataTableLevel').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employee-level.data-table") }}',
            dataType: 'json',
            data:{ _token: "{{csrf_token()}}", employee_id: "{{ $employee_id }}"}
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
            defaultContent: "<center><button class='btn btn-warning btn-circle' id='edit_btn'><i class='fas fa-edit'></i></button> " +
                "<button class='btn btn-danger btn-circle' id='remove_btn'><i class='fas fa-trash'></i></button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "level"},
            {data: "start_date"},
            {data: "end_date"},
        ]
    });

    tLevel.on( 'order.dt search.dt', function () {
        tLevel.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    $('#DataTableLevel tbody').on('click', '#edit_btn', function () {
        var data_row = tLevel.row($(this).closest('tr')).data();
        $('#btn-level-form').val('Update');
        $("#level_id").val(data_row.id);
        $("#start_date_id").val(data_row.start_date);
        $("#end_date_id").val(data_row.end_date);
    });

    $('#DataTableLevel tbody').on('click', '#remove_btn', function () {
        var data_row = tLevel.row($(this).closest('tr')).data();
        var c = confirm('Delete data ?');
        if (c) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/employee-level/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTableLevel').DataTable().ajax.reload();
                }, error($data) {
                    alert('This Process Is Not Allowed');
                }
            });
        }
    });

    $('#level-form').on('reset', function() {
        $("#level-form input[type='hidden']").val('');
        $('#level-form')[0].reset();
        $('#btn-level-form').val('Add');
    });

    $('#level-form').submit(function(e){
        e.preventDefault();
        $('#btn-level-form').attr('disabled',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('employee-level.store') }}",
            method: 'post',
            dataType: 'json',
            data: $('#level-form').serialize() + "&employee_id={{ $employee_id }}",
            success: function(data){
                $("#level-form input[type='hidden']").val('');
                $('#level-form')[0].reset();
                $('#DataTableLevel').DataTable().ajax.reload();
                $('#btn-level-form').text('Add').removeAttr('disabled');
            }
        });
    });
});
</script>

