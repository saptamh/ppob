<table id="dgVillage" title="Kecamatan" style="100%;"
            url="{{ route('village.data-table') }}"
            method="get"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
</table>
<div id="toolbar">
    <a href="javascript:void(0)" id="newBtn" iconCls="icon-add" plain="true" onclick="newRecord()">Tambah</a>
    <a href="javascript:void(0)" id="editBtn" iconCls="icon-edit" plain="true" onclick="editRecord()">Edit</a>
    <a href="javascript:void(0)" id="removeBtn" iconCls="icon-remove" plain="true" onclick="destroyRecord()">Hapus</a>
</div>

<div id="dlgVillage"style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlgVillage-buttons'">
    <form id="fmVillage" method="post" novalidate style="margin:0;padding:20px 50px">
        <div style="margin-bottom:10px">
            <input id="idVillage" name="id" style="width:200px" type="hidden" data-options="type:'hidden'">
            <input id="villageProvId" name="province_id" label="Propinsi:" labelPosition="top" style="width:400px;" required="true">
            <input id="villageCityId" name="city_id" label="Kota:" labelPosition="top" style="width:400px;" required="true">
            <input id="villageDistrictId" name="district_id" label="Kecamatan:" labelPosition="top" style="width:400px;" required="true">
            <input id="nameVillage" name="name" style="width:400px" data-options="label:'Nama Desa:',labelPosition:'top',required:true">
            <input id="villageRt" name="rt" style="width:400px" data-options="label:'RT/RW:',labelPosition:'top',required:true">
        </div>
    </form>
</div>
<div id="dlgVillage-buttons">
    <a href="javascript:void(0)" id="dlgSaveBtn" iconCls="icon-ok" onclick="save()" style="width:90px">Simpan</a>
    <a href="javascript:void(0)" id="dlgCloseBtn" iconCls="icon-cancel" onclick="javascript:$('#dlgVillage').dialog('close')" style="width:90px">Batal</a>
</div>
<script type="text/javascript">
    var baseUrl = "{{ URL::to('/') }}";
    $("#dgVillage").datagrid({
        columns: [[
            {field:'name',title:'Nama Desa',width:100},
            {field:'rt',title:'RT/RW',width:100},
            {field:'district.name',title:'Nama Kecamatan',width:100,formatter:function(value,row){return row.district.name}},
            {field:'district.city.name',title:'Nama Kota',width:100,formatter:function(value,row){return row.district.city.name}},
            {field:'district.city.province.name',title:'Nama Propinsi',width:100,formatter:function(value,row){return row.district.city.province.name}}
        ]]
    });
    $("#dlgVillage").dialog();
    $("#newBtn,#editBtn,#removeBtn,#dlgSaveBtn,#dlgCloseBtn").linkbutton();
    $("#fmVillage").form();
    $("#nameVillage,#villageRt").textbox();

    $("#villageProvId").combobox({
        method: "get",
        valueField: 'id',
        textField: 'name',
        onClick: function(rec){
            $('#villageCityId').combobox('clear');
            $('#villageDistrictId').combobox('clear');
        },
        onSelect: function(rec) {
            var url = baseUrl + "/city/combobox/?province=" + rec.id;
            $('#villageCityId').combobox('reload', url);
        }
    });

    $("#villageCityId").combobox({
        method: "get",
        valueField: 'id',
        textField: 'name',
        onClick: function(rec){
            $('#villageDistrictId').combobox('clear');
        },
        onSelect: function(rec) {
            var url = baseUrl + "/district/combobox/?city=" + rec.id;
            $('#villageDistrictId').combobox('reload', url);
        }
    });

    $("#villageDistrictId").combobox({
        method: 'get',
        valueField: 'id',
        textField: 'name',
    });

    var url = "{{ route('village.store') }}";
    function newRecord(){
        $('#dlgVillage').dialog('open').dialog('center').dialog('setTitle','Tambah Desa');
        $('#villageProvId').combobox('reload', "{{ route('province.combobox') }}");
        $('#fmVillage').form('clear');
    }
    function editRecord(){
        $('#villageProvId').combobox('reload', "{{ route('province.combobox') }}");
        var row = $('#dgVillage').datagrid('getSelected');
        if (row){
            $('#dlgVillage').dialog('open').dialog('center').dialog('setTitle','Edit Desa');
            $('#fmVillage').form('load',row);
            console.log(row);
            $("#villageProvId").combobox('setValue', row.district.city.province.id);
            $("#villageCityId").combobox('setValue', row.district.city.id);
        }
    }
    function save(){
        $('#fmVillage').form('submit',{
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
                    $('#dlgVillage').dialog('close');        // close the dialog
                    $('#dgVillage').datagrid('reload');    // reload the user data
                }
            }
        });
    }
    function destroyRecord(){
        var row = $('#dgVillage').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Anda yakin hapus desa ini?',function(r){
                if (r){
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route("village.destroy") }}',
                        dataType: 'json',
                        data: {
                            'id': row.id,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(result) {
                            $('#dgVillage').datagrid('reload');
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
