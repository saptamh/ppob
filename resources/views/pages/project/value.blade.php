<link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        {{ Form::open([
            'id'=>'value-form',
            'url' => route('value-project.store'),
            'method' => 'post',
            'class' => 'form',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        ]) }}
        {{ Form::hidden('id', '', ['id'=>'value_id']) }}
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {{ Form::label('value', 'Value (Nilai)') }}
                    {{ Form::text('value', '', ['id'=>'value_id_text', 'class'=>'form-control', 'placeholder'=>'Enter Value', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', '', ['class'=>'form-control', 'placeholder'=>'Enter Description', 'rows'=>3, 'required'=>'true']) }}
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                {{ Form::submit('Add!', ['id'=>'btn-value-form', 'class'=>'btn btn-success btn-sm btn-block', 'data-loading-text'=>'Loading...']) }}
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
                    <th>ID</th>
                    <th>Value</th>
                    <th>Description</th>
                    <th>Created Date</th>
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
            url: '{{ route("value-project.data-table") }}',
            dataType: 'json',
            data:{ _token: "{{csrf_token()}}", project_id: "{{ $project_id }}"}
        },
        columnDefs: [
        {
            targets: [ 4 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center><button class='btn btn-warning btn-circle' id='edit_btn' disabled='true'><i class='fas fa-edit'></i></button> " +
                "<button class='btn btn-danger btn-circle' id='remove_btn'><i class='fas fa-trash'></i></button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "value", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "description"},
            {data: "created_at"},
        ]
    });

    // tLevel.on( 'order.dt search.dt', function () {
    //     tLevel.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         cell.innerHTML = i+1;
    //     });
    // }).draw();

    $('#DataTableLog tbody').on('click', '#edit_btn', function () {
        var data_row = tLevel.row($(this).closest('tr')).data();
        $('#btn-value-form').val('Update');
        $("#value_id").val(data_row.id);
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
                url: baseUrl + "/value-project/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTableLog').DataTable().ajax.reload();
                }, error($data) {
                    alert('This Process Is Not Allowed');
                }
            });
        }
    });

    $('#value-form').on('reset', function() {
        $("#value-form input[type='hidden']").val('');
        $('#value-form')[0].reset();
        $('#btn-value-form').val('Add');
    });

    $('#value-form').submit(function(e){
        e.preventDefault();
        $('#btn-value-form').attr('disabled',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('value-project.store') }}",
            method: 'post',
            dataType: 'json',
            data: $('#value-form').serialize() + "&project_id={{ $project_id }}",
            success: function(data){
                $("#value-form input[type='hidden']").val('');
                $('#value-form')[0].reset();
                $('#DataTableLog').DataTable().ajax.reload();
                $('#btn-value-form').text('Add').removeAttr('disabled');
            }
        });
    });
});
</script>

