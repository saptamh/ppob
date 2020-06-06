<link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        {{ Form::open([
            'id'=>'family-form',
            'url' => route('employee-family.store'),
            'method' => 'post',
            'class' => 'form',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        ]) }}
        {{ Form::hidden('id', '', ['id'=>'family_id']) }}
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', '', ['id'=>'name_id', 'class'=>'form-control', 'placeholder'=>'Enter Name', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('birth_date', 'Birth Date') }}
                    {{ Form::text('birth_date', '', ['id'=>'birth_date_id', 'class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('relation_type', 'Relation Type') }}
                    {{ Form::select('relation_type',  array_combine($relation_type, $relation_type), '', ['id'=>'relation_type_id', 'class'=>'form-control', 'placeholder'=>'Select Relation', 'required'=>'true']) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('phone', 'Phone') }}
                    {{ Form::text('phone', '', ['id'=>'phone_id', 'class'=>'form-control', 'placeholder'=>'Enter Phone']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('sex', 'Sex') }}
                    <div class="form-check">
                        {{ Form::radio('sex', 'M', true, ['id'=>'male_id', 'class'=>'form-check-input']) }}
                        {{ Form::label('male', 'Male', ['class'=>'form-check-label']) }}
                    </div>
                    <div class="form-check">
                        {{ Form::radio('sex', 'F', false, ['id'=>'female_id', 'class'=>'form-check-input']) }}
                        {{ Form::label('n', 'Female', ['class'=>'form-check-label']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('address', 'Address') }}
                    {{ Form::textarea('address', '', ['id'=>'address_id', 'class'=>'form-control', 'placeholder'=>'Enter Address', 'rows'=>3, 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::submit('Add!', ['id'=>'btn-family-form', 'class'=>'btn btn-success btn-sm', 'data-loading-text'=>'Loading...']) }}
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
            <table class="table table-bordered table-hover" id="DataTableFamily" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Birth Date</th>
                    <th>Relationship</th>
                    <th>Sex</th>
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
    $('#birth_date_id').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });

    var tFamily = $('#DataTableFamily').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employee-family.data-table") }}',
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
            targets: [ 5 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center><button class='btn btn-warning btn-circle' id='edit_btn'><i class='fas fa-edit'></i></button> " +
                "<button class='btn btn-danger btn-circle' id='remove_btn'><i class='fas fa-trash'></i></button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "name"},
            {data: "birth_date"},
            {data: "relation_type"},
            { data: "sex", render: function(data, type, row) {
                return (data=='M' ? 'Male' : 'Female')
            }},
        ]
    });

    tFamily.on( 'order.dt search.dt', function () {
        tFamily.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    $('#DataTableFamily tbody').on('click', '#edit_btn', function () {
        var data_row = tFamily.row($(this).closest('tr')).data();
        $('#btn-family-form').val('Update');
        $("#family_id").val(data_row.id);
        $("#name_id").val(data_row.name);
        $("#birth_date_id").val(data_row.birth_date);
        $("#relation_type_id").val(data_row.relation_type);
        $("#address_id").val(data_row.address);
        $("#phone_id").val(data_row.phone);
        if (data_row.sex == "F") {
            $("#female_id").prop('checked', true);
            $("#male_id").prop('checked', false);
        } else {
            $("#male_id").prop('checked', true);
            $("#female_id").prop('checked', false);
        }
    });

    $('#DataTableFamily tbody').on('click', '#remove_btn', function () {
        var data_row = tFamily.row($(this).closest('tr')).data();
        var c = confirm('Delete ' + data_row.name + ' ?');
        if (c) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseUrl + "/employee-family/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTableFamily').DataTable().ajax.reload();
                }, error($data) {
                    alert('This Process Is Not Allowed');
                }
            });
        }
    });

    $('#family-form').on('reset', function() {
        $("#family-form input[type='hidden']").val('');
        $('#family-form')[0].reset();
        $('#btn-family-form').val('Add');
    });

    $('#family-form').submit(function(e){
        e.preventDefault();
        $('#btn-family-form').attr('disabled',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('employee-family.store') }}",
            method: 'post',
            dataType: 'json',
            data: $('#family-form').serialize() + "&employee_id={{ $employee_id }}",
            success: function(data){
                $("#family-form input[type='hidden']").val('');
                $('#family-form')[0].reset();
                $('#DataTableFamily').DataTable().ajax.reload();
                $('#btn-family-form').text('Add').removeAttr('disabled');
            }
        });
    });
});
</script>

