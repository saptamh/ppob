<link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        {{ Form::open([
            'id'=>'salary-form',
            'url' => route('employee-salary.store'),
            'method' => 'post',
            'class' => 'form',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        ]) }}
        {{ Form::hidden('id', '', ['id'=>'salary_id']) }}
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('start_date', 'Start Date') }}
                    {{ Form::text('start_date', '', ['id'=>'start_date_id', 'class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('end_date', 'End Date') }}
                    {{ Form::text('end_date', '', ['id'=>'end_date_id', 'class'=>'form-control', 'placeholder'=>'Enter Date']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('base_salary', 'Base Salary') }}
                    {{ Form::text('base_salary', '', ['id'=>'base_salary_id', 'class'=>'form-control', 'placeholder'=>'Enter Base Salary', 'required'=>'true']) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('weekend_allowance', 'Weekend Allowance (perdays based on salary)') }}
                    {{ Form::text('weekend_allowance', '', ['id'=>'weekend_allowance_id', 'class'=>'form-control', 'placeholder'=>'Ex: 1.5']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('meal_allowance', 'Meal Allowance (in a day)') }}
                    {{ Form::text('meal_allowance', '', ['id'=>'meal_allowance_id', 'class'=>'form-control', 'placeholder'=>'Ex: 15000']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('working_hour', 'Working Hour Perday') }}
                    {{ Form::text('working_hour', '', ['id'=>'working_hour_id', 'class'=>'form-control', 'placeholder'=>'Ex: 8 (means in a day working for 8 hour)']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                {{ Form::submit('Add!', ['id'=>'btn-salary-form', 'class'=>'btn btn-success btn-sm btn-block', 'data-loading-text'=>'Loading...']) }}
            </div>
            <div class="col-lg-6">
                {{ Form::button('Reset!', ['class'=>'btn btn-warning btn-sm btn-block', 'type'=>'reset']) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="DataTableSalary" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Base Salary</th>
                    <th>Weekend Allowance</th>
                    <th>Work Hour</th>
                    <th>Meal Allowance</th>
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

    var tSalary = $('#DataTableSalary').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employee-salary.data-table") }}',
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
            targets: [ 7 ],
            visible: true,
            searchable: false,
            sortable: false,
            defaultContent: "<center><button class='btn btn-warning btn-circle' id='edit_btn'><i class='fas fa-edit'></i></button> " +
                "<button class='btn btn-danger btn-circle' id='remove_btn'><i class='fas fa-trash'></i></button></center>"
        }],
        columns: [
            {data: "id"},
            {data: "base_salary", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "weekend_allowance"},
            {data: "working_hour"},
            {data: "meal_allowance", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "start_date"},
            {data: "end_date"},
        ]
    });

    tSalary.on( 'order.dt search.dt', function () {
        tSalary.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    $('#DataTableSalary tbody').on('click', '#edit_btn', function () {
        var data_row = tSalary.row($(this).closest('tr')).data();
        $('#btn-salary-form').val('Update');
        $("#salary_id").val(data_row.id);
        $("#start_date_id").val(data_row.start_date);
        $("#end_date_id").val(data_row.end_date);
        $("#base_salary_id").val(data_row.base_salary);
        $("#weekend_allowance_id").val(data_row.weekend_allowance);
        $("#working_hour_id").val(data_row.working_hour);
        $("#meal_allowance_id").val(data_row.meal_allowance);
    });

    $('#DataTableSalary tbody').on('click', '#remove_btn', function () {
        var data_row = tSalary.row($(this).closest('tr')).data();
        var c = confirm('Delete data ?');
        if (c) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseUrl + "/employee-salary/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTableSalary').DataTable().ajax.reload();
                }, error($data) {
                    alert('This Process Is Not Allowed');
                }
            });
        }
    });

    $('#salary-form').on('reset', function() {
        $("#salary-form input[type='hidden']").val('');
        $('#salary-form')[0].reset();
        $('#btn-salary-form').val('Add');
    });

    $('#salary-form').submit(function(e){
        e.preventDefault();
        $('#btn-salary-form').attr('disabled',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('employee-salary.store') }}",
            method: 'post',
            dataType: 'json',
            data: $('#salary-form').serialize() + "&employee_id={{ $employee_id }}",
            success: function(data){
                $("#salary-form input[type='hidden']").val('');
                $('#salary-form')[0].reset();
                $('#DataTableSalary').DataTable().ajax.reload();
                $('#btn-salary-form').text('Add').removeAttr('disabled');
            }
        });
    });
});
</script>

