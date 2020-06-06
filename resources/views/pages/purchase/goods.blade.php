<link href="{{ URL::asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        {{ Form::open([
            'id'=>'value-form',
            'url' => route('purchase-goods.store'),
            'method' => 'post',
            'class' => 'form',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        ]) }}
        {{ Form::hidden('id', '', ['id'=>'purchase_id']) }}
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('part_number', 'Part Number') }}
                    {{ Form::text('part_number', '', ['id'=>'part_number_id', 'class'=>'form-control', 'placeholder'=>'Enter Part Number', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', '', ['id'=>'name_id', 'class'=>'form-control', 'placeholder'=>'Enter Name', 'required'=>'true']) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('qty', 'Quantity') }}
                    {{ Form::number('qty', '', ['id'=>'qty_id', 'class'=>'form-control', 'placeholder'=>'Enter Quantity', 'required'=>'true']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('price', 'Price') }}
                    {{ Form::number('price', '', ['id'=>'price_id', 'class'=>'form-control', 'placeholder'=>'Enter Price', 'required'=>'true']) }}
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
            <table class="table table-bordered table-hover" id="DataTableGoods" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Part Number</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total Price</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align:right"></th>
                        <th></th>
                        <th style="text-align:right"></th>
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

    var tLevel = $('#DataTableGoods').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("purchase-goods.data-table") }}',
            dataType: 'json',
            data:{ _token: "{{csrf_token()}}", purchase_id: "{{ $purchase_id }}"}
        },
        columnDefs: [
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
            {data: "part_number"},
            {data: "name"},
            {data: "qty", render: $.fn.dataTable.render.number( '.', '.', 0, '' )},
            {data: "price", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
            {data: "total", render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp.' )},
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
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            totalBobot = api
            .column( 5 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            // Update footer
            $( api.column( 3 ).footer() ).html(
                'Total: ' + total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );

            $( api.column( 5 ).footer() ).html(
                'Total: Rp.' + totalBobot.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            );
        },
    });

    // tLevel.on( 'order.dt search.dt', function () {
    //     tLevel.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         cell.innerHTML = i+1;
    //     });
    // }).draw();

    $('#DataTableGoods tbody').on('click', '#edit_btn', function () {
        var data_row = tLevel.row($(this).closest('tr')).data();
        $('#btn-value-form').val('Update');
        $("#purchase_id").val(data_row.id);
        $("#part_number_id").val(data_row.part_number);
        $("#qty_id").val(data_row.qty);
        $("#name_id").val(data_row.name);
        $("#price_id").val(data_row.price);
    });

    $('#DataTableGoods tbody').on('click', '#remove_btn', function () {
        var data_row = tLevel.row($(this).closest('tr')).data();
        var c = confirm('Delete data ?');
        if (c) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseUrl + "/purchase-goods/destroy/" + data_row.id,
                method: 'delete',
                success: function(data){
                    $('#DataTableGoods').DataTable().ajax.reload();
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
            url: "{{ route('purchase-goods.store') }}",
            method: 'post',
            dataType: 'json',
            data: $('#value-form').serialize() + "&purchase_id={{ $purchase_id }}",
            success: function(res){
                $("#value-form input[type='hidden']").val('');
                $('#value-form')[0].reset();
                $('#DataTableGoods').DataTable().ajax.reload();
                $('#btn-value-form').text('Add').removeAttr('disabled');
                if ($("#term_of_payment").val() == 2) {
                    var percentase = (parseInt($("#down_payment").val()) / parseInt(res.data.total_price)) * 100;
                    var nominalLeft = parseInt(res.data.total_price) - parseInt($("#down_payment").val());
                    $("#percentase-left").val(percentase + '%');
                    $("#nominal-left").val(nominalLeft.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                }
            }
        });
    });
});
</script>

