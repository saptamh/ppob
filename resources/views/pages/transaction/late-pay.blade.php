<table id="dgLatePay" title="Kecamatan" style="100%;"
            url="{{ route('district.data-table') }}"
            method="get"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
</table>

<script type="text/javascript">
    var baseUrl = "{{ URL::to('/') }}";
    $("#dgLatePay").datagrid({
        title: "Pembayaran Jatuh Tempo",
        url: "{{ route('transaction.late-pay-data-table') }}",
        method: "get",
        rownumbers: true,
        fitColumns: true,
        singleSelect: true,
        width: '100%',
        columns: [[
            {field:'customer.nik',title:'NIK',width:100,formatter:function(value,row){return row.customer.nik}},
            {field:'customer.name',title:'Nama Kustomer',width:100,formatter:function(value,row){return row.customer.name}},
            {field:'customer_vehicle.plate_number',title:'Nomor Plat',width:100,formatter:function(value,row){return row.customer_vehicle.plate_number}},
            {field:'customer_vehicle.vehicle_merk',title:'Jeni/Tipe Kendaraan',width:100,formatter:function(value,row){return row.customer_vehicle.vehicle_merk}},
            {field:'end_date',title:'Tgl Jatuh Tempo',width:100},
            {field:'late_date',title: 'Telat (hari)', widht:100,
                formatter: function(value, row) {
                    return date_diff_indays(row.end_date, Date.now());
                }
            }
        ]]
    });

    var date_diff_indays = function(date1, date2) {
        dt1 = new Date(date1);
        dt2 = new Date(date2);
        // return 'a';
        return Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate()) ) /(1000 * 60 * 60 * 24));
    }
</script>
