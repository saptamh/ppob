<table id="dgMapping" title="Pengguna (RT/RW)" class="easyui-datagrid" style="height:230px;"
        url="{{ route('user.data-table') }}"
        method="get"
        pagination="true"
        rownumbers="true" fitColumns="true" singleSelect="true">
</table>
<br>
<table id="dgMpCustomer" class="easyui-datagrid" style="height:230px;"
        method="get"
        pagination="true"
        rownumbers="true" fitColumns="true" singleSelect="true">
</table>

<div id="mpWin" class="easyui-window" style="width:800px;height:400px">
        <div id="mpLayout" class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',split:true" style="height:175px">
                <table id="dgMpCustomerVehicle"></table>
            </div>
            <div data-options="region:'center'">
                <table id="dgMpCustomerHistorical"></table>
            </div>
        </div>
    </div>

<script type="text/javascript">
    var baseUrl = "{{ URL::to('/') }}";
    $("#dgMapping").datagrid({
        columns: [[
            {field:'user_detail.nik',title:'NIK',width:30,formatter:function(value,row){return row.user_detail.nik}},
            {field:'name',title:'Nama',width:30, sortable:true},
            {field:'email',title:'Email',width:30,formatter:function(value,row){return row.email}},
            {field:'user_detail.phone',title:'Telepon',width:30,formatter:function(value,row){return row.user_detail.phone}},
            {field:'user_detail.village.name',title:'Desa',width:30,formatter:function(value,row){return row.user_detail.village.name}},
            {field:'user_detail.village.rt',title:'RT/RW',width:30,formatter:function(value,row){return row.user_detail.village.rt}},
            {field:'user_detail.address',title:'Address',width:30,formatter:function(value,row){return row.user_detail.address}},
            {field:'is_admin',title:'Admin',width:30,formatter:function(value,row){return row.is_admin ? 'Ya' : 'Tidak'}}
        ]],
        onDblClickRow: function(index, row) {
            var url = baseUrl + "/mapping/data-table?user=" + row.id;
            $("#dgMpCustomer").datagrid('reload', url);
        }
    });

    $('#mpWin').window({
        title: "my window",
        modal: true,
        closed: true
    });

    $("#mpLayout").layout();

    $("#dgMpCustomerVehicle").datagrid({
        title: 'List Kendaraan',
        method: 'get',
        pagination: true,
        rownumbers: true,
        fitColumns: true,
        singleSelect: true,
        width: '100%',
        height: '168px',
        columns: [[
            {field:'plate_number',title:'No Polisi',width:30},
            {field:'build_year',title:'Tahun',width:30},
            {field:'machine_number',title:'No Mesin',width:30},
            {field:'body_number',title:'No Rangka',width:30},
            {field:'vehicle_type',title:'Jenis',width:30},
            {field:'vehicle_merk',title:'Merek/Tipe',width:30}
        ]],
        onClickRow: function(index, row) {
            $('#dgMpCustomerHistorical').datagrid('reload');
        }
    });

    $("#dgMpCustomerHistorical").datagrid({
        title: 'Catatan Pembayaran',
        method: 'get',
        pagination: true,
        rownumbers: true,
        fitColumns: true,
        singleSelect: true,
        width: '100%',
        height: '168px',
        columns: [[
            {field:'plate_number',title:'Kode Bayar',width:30},
            {field:'build_year',title:'Tanggal',width:30},
            {field:'machine_number',title:'Metode Bayar',width:30},
            {field:'body_number',title:'Terlambat (hari)',width:30}
        ]],
    });

    $("#dgMpCustomer").datagrid({
        title: "Kustomer",
        columns: [[
            {field:'nik',title:'NIK',width:30},
            {field:'name',title:'Nama',width:30},
            {field:'email',title:'Email',width:30},
            {field:'phone',title:'Telepon',width:30},
            {field:'village.name',title:'Desa',width:30,formatter:function(value,row){return row.village.name}},
            {field:'village.rt',title:'RT/RW',width:30,formatter:function(value,row){return row.village.rt}}
        ]],
        onDblClickRow: function(index, row) {
            $('#mpWin').window('open').window('setTitle', row.nik + " - " + row.name);
            var url = baseUrl + "/customer/vehicles?nik=" + row.nik;
            $("#dgMpCustomerVehicle").datagrid('reload', url);
        }
    });
</script>
