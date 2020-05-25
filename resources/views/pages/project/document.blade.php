<link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        {{ Form::open([
            'id'=>'document-form',
            'url' => route('document-project.store'),
            'method' => 'post',
            'class' => 'form',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        ]) }}
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {{ Form::hidden('project_id', $project_id) }}
                    {{ Form::label('name', 'Document Name') }}
                    {{ Form::text('name', '', ['id'=>'name_id', 'class'=>'form-control', 'placeholder'=>'Enter Name', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                {{ Form::label('path', 'Upload Document') }}
                {{ Form::file('path', ['id'=>'path_id', 'class'=>'form-control', 'placeholder'=>'Upload Document', 'required'=>'true']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                {{ Form::submit('Add!', ['id'=>'btn-document-form', 'class'=>'btn btn-success btn-sm btn-block', 'data-loading-text'=>'Loading...']) }}
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
            <table class="table table-bordered table-hover" id="DataTableDocument" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>File</th>
                    <th>Type</th>
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

    var tDocument = $('#DataTableDocument').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("document-project.data-table") }}',
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
            targets: [ 4 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center><button class='btn btn-danger btn-circle' id='remove_btn'><i class='fas fa-trash'></i></button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "name"},
            {data: "path", render: function(data, type, row) {
                return "<a href='"+data+"' target='_blank'>View Document</a>";
            }},
            {data: "type"},
        ]
    });

    tDocument.on( 'order.dt search.dt', function () {
        tDocument.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    $('#DataTableDocument tbody').on('click', '#remove_btn', function () {
        var data_row = tDocument.row($(this).closest('tr')).data();
        var c = confirm('Delete data ?');
        if (c) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/document-project/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTableDocument').DataTable().ajax.reload();
                }, error($data) {
                    alert('This Process Is Not Allowed');
                }
            });
        }
    });

    $('#document-form').on('reset', function() {
        $('#document-form')[0].reset();
        $('#btn-document-form').val('Add');
    });

    $('#document-form').submit(function(e){
        e.preventDefault();
        $('#btn-document-form').attr('disabled',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('document-project.store') }}",
            method: 'post',
            dataType: 'json',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(data){
                $('#document-form')[0].reset();
                $('#DataTableDocument').DataTable().ajax.reload();
                $('#btn-document-form').text('Add').removeAttr('disabled');
            }
        });
    });
});
</script>

