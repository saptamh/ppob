<table id="dgCustomer" title="Pelanggan" class="easyui-datagrid" style="100%;"
        url="{{ route('customer.data-table') }}"
        method="get"
        toolbar="#toolbar" pagination="true"
        rownumbers="true" fitColumns="true" singleSelect="true">
</table>
<div id="toolbar">
    <a href="javascript:void(0)" id="newBtn" iconCls="icon-add" plain="true" onclick="newRecord()">Tambah</a>
    <a href="javascript:void(0)" id="editBtn" iconCls="icon-edit" plain="true" onclick="editRecord()">Edit</a>
    <a href="javascript:void(0)" id="removeBtn" iconCls="icon-remove" plain="true" onclick="destroyRecord()">Hapus</a>
</div>

<div id="dlgCustomer" class="easyui-dialog" style="width:570px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlgCustomer-buttons'">
    <form id="fmCustomer" method="post" novalidate style="margin:0;padding:20px;">
        <div style="margin-bottom:10px">
            <div style="display:flex;justify-content:space-between">
                <div>
                    <input id="customerId" name="id" style="width:200px" type="hidden" data-options="type:'hidden'">
                    <input id="customerNik" name="nik" class="easyui-textbox" required="true" label="NIK:" labelPosition="top" style="width:240px;">
                    <input id="customerName" name="name" class="easyui-textbox" required="true" label="Nama:" labelPosition="top" style="width:240px;">
                    <input id="customerEmail" name="email" class="easyui-textbox" type="email" required="true" label="Email:" labelPosition="top" style="width:240px;">
                </div>
                <div>
                    <input id="customerPhone" name="phone" class="easyui-textbox" required="true" label="Telepon:" labelPosition="top" style="width:240px;">
                    <input id="customerAddress" name="address" class="easyui-textbox" required="true" label="address:" labelPosition="top" style="width:240px;">
                    <select id="customerVillageCbGrid" name="village_id" label="Desa:" labelPosition="top" requred="true"></select>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="dlgCustomer-buttons">
    <a href="javascript:void(0)" id="dlgSaveBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveCustomer()" style="width:90px">Simpan</a>
    <a href="javascript:void(0)" id="dlgCloseBtn" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgCustomer').dialog('close')" style="width:90px">Batal</a>
</div>
<script type="text/javascript">
    $("#dgCustomer").datagrid({
        columns: [[
                {field:'nik',title:'NIK',width:30},
                {field:'name',title:'Nama',width:30},
                {field:'email',title:'Email',width:30},
                {field:'phone',title:'Telepon',width:30},
                {field:'village.name',title:'Desa',width:30,formatter:function(value,row){return row.village.name}},
                {field:'village.rt',title:'RT/RW',width:30,formatter:function(value,row){return row.village.rt}}
            ]]
    });
    $("#dlgCustomer").dialog();
    $("#newBtn,#editBtn,#removeBtn,#dlgSaveBtn,#dlgCloseBtn").linkbutton();
    $("#customerNik,#customerName,#customerEmail,#customerPhone,#customerAddress").textbox();
    $("#fmCustomer").form();
    $("#customerVillageCbGrid").combogrid({
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
            ]]
        });

    var url = "{{ route('customer.store') }}";
    function newRecord(){
        $('#dlgCustomer').dialog('open').dialog('center').dialog('setTitle','Tambah Kustomer');
        $('#fmCustomer').form('clear');
    }
    function editRecord(){
        var row = $('#dgCustomer').datagrid('getSelected');
        if (row){
            console.log(row);
            $('#dlgCustomer').dialog('open').dialog('center').dialog('setTitle','Edit Kustomer');
            $('#fmCustomer').form('load',row);
            $("#customerVillageCbGrid").combogrid('setValue', row.village_id);
        }
    }
    function saveCustomer(){
        $('#fmCustomer').form('submit',{
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
                    $('#dlgCustomer').dialog('close');        // close the dialog
                    $('#dgCustomer').datagrid('reload');    // reload the user data
                }
            }
        });
    }
    function destroyRecord(){
        var row = $('#dgCustomer').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Yakin hapus kustomer ini?',function(r){
                if (r){
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route("customer.destroy") }}',
                        dataType: 'json',
                        data: {
                            'id': row.id,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(result) {
                            $('#dgCustomer').datagrid('reload');
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
