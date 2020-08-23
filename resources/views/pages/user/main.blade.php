<table id="dgUser" title="Pengguna" class="easyui-datagrid" style="100%;"
        url="{{ route('user.data-table') }}"
        method="get"
        toolbar="#toolbar" pagination="true"
        rownumbers="true" fitColumns="true" singleSelect="true">
</table>
<div id="toolbar">
    <a href="javascript:void(0)" id="newBtn" iconCls="icon-add" plain="true" onclick="newRecord()">Tambah</a>
    <a href="javascript:void(0)" id="editBtn" iconCls="icon-edit" plain="true" onclick="editRecord()">Edit</a>
    <a href="javascript:void(0)" id="removeBtn" iconCls="icon-remove" plain="true" onclick="destroyRecord()">Hapus</a>
</div>

<div id="dlgUser" class="easyui-dialog" style="width:570px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlgUser-buttons'">
    <form id="fmUser" method="post" novalidate style="margin:0;padding:20px;">
        <div style="margin-bottom:10px">
            <div style="display:flex;justify-content:space-between">
                <div>
                    <input id="userId" name="id" style="width:200px" type="hidden" data-options="type:'hidden'">
                    <input id="userNik" name="nik" class="easyui-textbox" required="true" label="NIK:" labelPosition="top" style="width:240px;">
                    <input id="userName" name="name" class="easyui-textbox" required="true" label="Nama:" labelPosition="top" style="width:240px;">
                    <input id="userEmail" name="email" class="easyui-textbox" type="email" required="true" label="Email:" labelPosition="top" style="width:240px;">
                    <input id="userPassword" name="password" class="easyui-textbox" type="password" label="Password (kosongkan jika tidak ganti):" labelPosition="top" style="width:240px;">
                </div>
                <div>
                    <input id="userPhone" name="phone" class="easyui-textbox" required="true" label="Telepon:" labelPosition="top" style="width:240px;">
                    <input id="userAddress" name="address" class="easyui-textbox" required="true" label="address:" labelPosition="top" style="width:240px;">
                    <select id="userVillage" name="village_id" label="Desa:" labelPosition="top" requred="true">
                    <input id="userIsadmin" class="easyui-checkbox" name="is_admin" value="1" label="Admin:" labelPosition="top">
                </div>
            </div>
        </div>
    </form>
</div>
<div id="dlgUser-buttons">
    <a href="javascript:void(0)" id="dlgSaveBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Simpan</a>
    <a href="javascript:void(0)" id="dlgCloseBtn" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgUser').dialog('close')" style="width:90px">Batal</a>
</div>
<script type="text/javascript">
    $("#dgUser").datagrid({
        columns: [[
                {field:'user_detail.nik',title:'NIK',width:30,formatter:function(value,row){return row.user_detail.nik}},
                {field:'name',title:'Nama',width:30},
                {field:'email',title:'Email',width:30,formatter:function(value,row){return row.email}},
                {field:'user_detail.phone',title:'Telepon',width:30,formatter:function(value,row){return row.user_detail.phone}},
                {field:'user_detail.village.name',title:'Desa',width:30,formatter:function(value,row){return row.user_detail.village.name}},
                {field:'user_detail.village.rt',title:'RT/RW',width:30,formatter:function(value,row){return row.user_detail.village.rt}},
                {field:'user_detail.address',title:'Address',width:30,formatter:function(value,row){return row.user_detail.address}},
                {field:'is_admin',title:'Admin',width:30,formatter:function(value,row){return row.is_admin ? 'Ya' : 'Tidak'}}
            ]]
    });
    $("#dlgUser").dialog();
    $("#newBtn,#editBtn,#removeBtn,#dlgSaveBtn,#dlgCloseBtn").linkbutton();
    $("#userNik,#userName,#userEmail,#userPhone,#userAddress,#userPassword").textbox();
    $("#userIsadmin").checkbox();
    $("#fmUser").form();
    $("#userVillage").combogrid({
            panelWidth:450,
            delay: 500,
            mode: 'remote',
            url: "{{ route('village.combobox') }}",
            method: "get",
            idField: 'id',
            textField: 'name',
            fitColumns:true,
            columns: [[
                {field:'name',title:'Nama Desa',width:120,sortable:true},
                {field:'rt',title:'RT/RW',width:400,sortable:true}
            ]],
            queryParams: {
                param1: $("#userName").textbox('getValue'),
            },
        });

    var url = "{{ route('user.store') }}";
    function newRecord(){
        // $('#userVillage').combobox('reload', "{{ route('village.combobox') }}");
        $('#dlgUser').dialog('open').dialog('center').dialog('setTitle','Tambah Pengguna');
        $('#fmUser').form('clear');
    }
    function editRecord(){
        // $('#userVillage').combobox('reload', "{{ route('village.combobox') }}");
        var row = $('#dgUser').datagrid('getSelected');
        if (row){
            console.log(row);
            $('#dlgUser').dialog('open').dialog('center').dialog('setTitle','Edit Pengguna');
            $('#fmUser').form('load',row);
            $("#userVillage").combogrid('setValue', row.user_detail.village_id);
            $("#userNik").textbox('setValue', row.user_detail.nik);
            $("#userPhone").textbox('setValue', row.user_detail.phone);
            $("#userAddress").textbox('setValue', row.user_detail.address);
            if (row.is_admin) {
                $("#userIsadmin").checkbox('check');
            } else {
                $("#userIsadmin").checkbox('uncheck');
            }

        }
    }
    function saveUser(){
        $('#fmUser').form('submit',{
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
                    $('#dlgUser').dialog('close');        // close the dialog
                    $('#dgUser').datagrid('reload');    // reload the user data
                }
            }
        });
    }
    function destroyRecord(){
        var row = $('#dgUser').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Yakin hapus user ini?',function(r){
                if (r){
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route("user.destroy") }}',
                        dataType: 'json',
                        data: {
                            'id': row.id,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(result) {
                            $('#dgUser').datagrid('reload');
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
