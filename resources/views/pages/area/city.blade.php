<table id="dgCity" title="Kota" style="100%;"
            url="{{ route('city.data-table') }}"
            method="get"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" id="newBtn" iconCls="icon-add" plain="true" onclick="newRecord()">Tambah</a>
        <a href="javascript:void(0)" id="editBtn" iconCls="icon-edit" plain="true" onclick="editRecord()">Edit</a>
        <a href="javascript:void(0)" id="removeBtn" iconCls="icon-remove" plain="true" onclick="destroyRecord()">Hapus</a>
    </div>

    <div id="dlgCity"style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlgCity-buttons'">
        <form id="fmCity" method="post" novalidate style="margin:0;padding:20px 50px">
            <div style="margin-bottom:10px">
                <input id="idCity" name="id" style="width:200px" type="hidden" data-options="type:'hidden'">
                <input id="provId" name="province_id" label="Propinsi:" labelPosition="top" style="width:400px;">
                <input id="nameCity" name="name" style="width:400px" data-options="label:'Nama Kota:',labelPosition:'top',required:true">
            </div>
        </form>
    </div>
    <div id="dlgCity-buttons">
        <a href="javascript:void(0)" id="dlgSaveBtn" iconCls="icon-ok" onclick="save()" style="width:90px">Simpan</a>
        <a href="javascript:void(0)" id="dlgCloseBtn" iconCls="icon-cancel" onclick="javascript:$('#dlgCity').dialog('close')" style="width:90px">Batal</a>
    </div>
    <script type="text/javascript">
        $("#dgCity").datagrid({
            columns: [[
                {field:'name',title:'Nama Kota',width:100},
                {field:'province.id',title:'Nama Propinsi',width:100,formatter:function(value,row){return row.province.name}}
            ]]
        });
        $("#dlgCity").dialog();
        $("#newBtn,#editBtn,#removeBtn,#dlgSaveBtn,#dlgCloseBtn").linkbutton();
        $("#fmCity").form();
        $("#nameCity").textbox();
        $("#provId").combobox({
            url: "{{ route('province.combobox') }}",
            method: "get",
            valueField: 'id',
            textField: 'name',
        });

        var url = "{{ route('city.store') }}";
        function newRecord(){
            $('#dlgCity').dialog('open').dialog('center').dialog('setTitle','Tambah Kota');
            $('#fmCity').form('clear');
        }
        function editRecord(){
            var row = $('#dgCity').datagrid('getSelected');
            if (row){
                $('#dlgCity').dialog('open').dialog('center').dialog('setTitle','Edit Kota');
                $('#fmCity').form('load',row);
            }
        }
        function save(){
            $('#fmCity').form('submit',{
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
                        $('#dlgCity').dialog('close');        // close the dialog
                        $('#dgCity').datagrid('reload');    // reload the user data
                    }
                }
            });
        }
        function destroyRecord(){
            var row = $('#dgCity').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Anda yakin hapus kota ini?',function(r){
                    if (r){
                        $.ajax({
                            type: 'DELETE',
                            url: '{{ route("city.destroy") }}',
                            dataType: 'json',
                            data: {
                                'id': row.id,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(result) {
                                $('#dgCity').datagrid('reload');
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
