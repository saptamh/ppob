<table id="dgDistrict" title="Kecamatan" style="100%;"
            url="{{ route('district.data-table') }}"
            method="get"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
</table>
<div id="toolbar">
    <a href="javascript:void(0)" id="newBtn" iconCls="icon-add" plain="true" onclick="newRecord()">Tambah</a>
    <a href="javascript:void(0)" id="editBtn" iconCls="icon-edit" plain="true" onclick="editRecord()">Edit</a>
    <a href="javascript:void(0)" id="removeBtn" iconCls="icon-remove" plain="true" onclick="destroyRecord()">Hapus</a>
</div>

<div id="dlgDistrict"style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlgDistrict-buttons'">
    <form id="fmDistrict" method="post" novalidate style="margin:0;padding:20px 50px">
        <div style="margin-bottom:10px">
            <input id="idDistrict" name="id" style="width:200px" type="hidden" data-options="type:'hidden'">
            <input id="districtProvId" name="province_id" label="Propinsi:" labelPosition="top" style="width:400px;" required="true">
            <input id="districtCityId" name="city_id" label="Kota:" labelPosition="top" style="width:400px;" required="true">
            <input id="nameDistrict" name="name" style="width:400px" data-options="label:'Nama Kecamatan:',labelPosition:'top',required:true">
        </div>
    </form>
</div>
<div id="dlgDistrict-buttons">
    <a href="javascript:void(0)" id="dlgSaveBtn" iconCls="icon-ok" onclick="save()" style="width:90px">Simpan</a>
    <a href="javascript:void(0)" id="dlgCloseBtn" iconCls="icon-cancel" onclick="javascript:$('#dlgDistrict').dialog('close')" style="width:90px">Batal</a>
</div>
<script type="text/javascript">
    var baseUrl = "{{ URL::to('/') }}";
    $("#dgDistrict").datagrid({
        columns: [[
            {field:'name',title:'Nama Kecamatan',width:100},
            {field:'city.name',title:'Nama Kota',width:100,formatter:function(value,row){return row.city.name}},
            {field:'city.province.name',title:'Nama Propinsi',width:100,formatter:function(value,row){return row.city.province.name}}
        ]]
    });
    $("#dlgDistrict").dialog();
    $("#newBtn,#editBtn,#removeBtn,#dlgSaveBtn,#dlgCloseBtn").linkbutton();
    $("#fmDistrict").form();
    $("#nameDistrict").textbox();

    $("#districtProvId").combobox({
        method: "get",
        valueField: 'id',
        textField: 'name',
        onClick: function(rec){
            $('#districtCityId').combobox('clear');
        },
        onSelect: function(rec) {
            var url = baseUrl + "/city/combobox/?province=" + rec.id;
            $('#districtCityId').combobox('reload', url);
        }
    });

    $("#districtCityId").combobox({
        method: 'get',
        valueField: 'id',
        textField: 'name',
    });

    var url = "{{ route('district.store') }}";
    function newRecord(){
        $('#dlgDistrict').dialog('open').dialog('center').dialog('setTitle','Tambah Kecamatan');
        $('#districtProvId').combobox('reload', "{{ route('province.combobox') }}");
        $('#fmDistrict').form('clear');

    }
    function editRecord(){
        $('#districtProvId').combobox('reload', "{{ route('province.combobox') }}");
        var row = $('#dgDistrict').datagrid('getSelected');
        if (row){
            $('#dlgDistrict').dialog('open').dialog('center').dialog('setTitle','Edit Kecamatan');
            $('#fmDistrict').form('load',row);
            $("#districtProvId").combobox('setValue', row.city.province.id);
        }
    }
    function save(){
        $('#fmDistrict').form('submit',{
            url: url,
            iframe: false,
            dataType: 'json',
            onSubmit: function(param){
                param._token = "{{csrf_token()}}";
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result.errorMsg){
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else {
                    $('#dlgDistrict').dialog('close');        // close the dialog
                    $('#dgDistrict').datagrid('reload');    // reload the user data
                }
            }
        });
    }
    function destroyRecord(){
        var row = $('#dgDistrict').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Anda yakin hapus kecamatan ini?',function(r){
                if (r){
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route("district.destroy") }}',
                        dataType: 'json',
                        data: {
                            'id': row.id,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(result) {
                            $('#dgDistrict').datagrid('reload');
                        },
                        error: function(error) {
                            $.messager.show({    // show error message
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
                    });
                }
            });
        }
    }
</script>
