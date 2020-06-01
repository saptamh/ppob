<link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        {{ Form::open([
            'id'=>'progress-form',
            'url' => route('progress-project.store'),
            'method' => 'post',
            'class' => 'form',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        ]) }}
        {{ Form::hidden('id', '', ['id'=>'progress_id']) }}
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {{ Form::label('progress', 'Progress (%)') }}
                    {{ Form::text('progress', '', ['id'=>'progress_id_text', 'class'=>'form-control', 'placeholder'=>'Enter Progress', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', '', ['id'=>'description_id', 'class'=>'form-control', 'placeholder'=>'Enter Description', 'rows'=>3, 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('project_value_id', 'Value (Nilai)') }}
                    {{ Form::select('project_value_id', $values, null, ['id'=>'project_value_id', 'class'=>'form-control', 'placeholder'=>'Select Value', 'required'=>'true']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                {{ Form::submit('Add!', ['id'=>'btn-progress-form', 'class'=>'btn btn-success btn-sm btn-block', 'data-loading-text'=>'Loading...']) }}
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
            <table class="table table-bordered table-hover" id="DataTableProgress" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Progress</th>
                    <th>Value</th>
                    <th>Result</th>
                    <th>Description</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align:right">Total:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
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

    var tProgress = $('#DataTableProgress').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("progress-project.data-table") }}',
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
            targets: [ 6 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center><button class='btn btn-warning btn-circle' id='edit_btn'><i class='fas fa-edit'></i></button> " +
                "<button class='btn btn-danger btn-circle' id='remove_btn' disabled='true'><i class='fas fa-trash'></i></button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "progress", "render": function ( data, type, row ) {
                return data +'%';
            }},
            {data: "project_value.value", name: "ProjectValue.value", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "result", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "description"},
            {data: "created_at"},
        ],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            console.log('API', api);
            // Total over all pages
            total = api
            .column( 1 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            totalBobot = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            // Total over this page
            pageTotal = api
                .column( 1, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 1 ).footer() ).html(
                'Total Progress: ' + total + '%'
            );

            $( api.column( 3 ).footer() ).html(
                'Total Progress: Rp.' + totalBobot.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );
        },
    });

    tProgress.on( 'order.dt search.dt', function () {
        tProgress.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    $('#DataTableProgress tbody').on('click', '#edit_btn', function () {
        var data_row = tProgress.row($(this).closest('tr')).data();
        $('#btn-progress-form').val('Update');
        $("#progress_id").val(data_row.id);
        $("#progress_id_text").val(data_row.progress);
        $("#project_value_id").val(data_row.project_value_id);
        $("#description_id").val(data_row.description);
    });

    $('#DataTableProgress tbody').on('click', '#remove_btn', function () {
        var data_row = tProgress.row($(this).closest('tr')).data();
        var c = confirm('Delete data ?');
        if (c) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/progress-project/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTableProgress').DataTable().ajax.reload();
                }, error($data) {
                    alert('This Process Is Not Allowed');
                }
            });
        }
    });

    $('#progress-form').on('reset', function() {
        $("#progress-form input[type='hidden']").val('');
        $('#progress-form')[0].reset();
        $('#btn-progress-form').val('Add');
    });

    $('#progress-form').submit(function(e){
        e.preventDefault();
        $('#btn-progress-form').attr('disabled',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('progress-project.store') }}",
            method: 'post',
            dataType: 'json',
            data: $('#progress-form').serialize() + "&project_id={{ $project_id }}",
            success: function(data){
                $("#progress-form input[type='hidden']").val('');
                $('#progress-form')[0].reset();
                $('#DataTableProgress').DataTable().ajax.reload();
                $('#btn-progress-form').text('Add').removeAttr('disabled');
            }
        });
    });
});
</script>

