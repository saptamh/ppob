<table id="dg" title="Propinsi" class="easyui-datagrid" style="100%;"
            url="{{ route('province.data-table') }}"
            method="get"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="name" width="50">Nama Propinsi</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" id="newBtn" iconCls="icon-add" plain="true" onclick="newRecordProvince()">Tambah</a>
        <a href="javascript:void(0)" id="editBtn" iconCls="icon-edit" plain="true" onclick="editRecordProvince()">Edit</a>
        <a href="javascript:void(0)" id="removeBtn" iconCls="icon-remove" plain="true" onclick="destroyRecordProvince()">Hapus</a>
    </div>

    <div id="dlgProvince" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',labelPosition:'top',buttons:'#dlgProvince-buttons'">
        <form id="fmProvince" method="post" novalidate style="margin:0;padding:20px 50px">
            <div style="margin-bottom:10px">
                <input class="easyui-textbox" id="id-province" name="id" type="hidden" data-options="type:'hidden'">
                <input class="easyui-textbox" id="name-province" style="width:400px" name="name" data-options="label:'Nama:',required:true">
            </div>
        </form>
    </div>
    <div id="dlgProvince-buttons">
        <a href="javascript:void(0)" id="dlgSaveBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveProvince()" style="width:90px">Simpan</a>
        <a href="javascript:void(0)" id="dlgCloseBtn" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgProvince').dialog('close')" style="width:90px">Batal</a>
    </div>
    <script type="text/javascript">
        $("#dg").datagrid();
        $("#dlgProvince").dialog();
        $("#newBtn,#editBtn,#removeBtn,#dlgSaveBtn,#dlgCloseBtn").linkbutton();
        $("#fmProvince").form();
        $("#name-province").textbox();

        var url = "{{ route('province.store') }}";
        function newRecordProvince(){
            $('#dlgProvince').dialog('open').dialog('center').dialog('setTitle','Tambah Propinsi');
            $('#fmProvince').form('clear');
        }
        function editRecordProvince(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlgProvince').dialog('open').dialog('center').dialog('setTitle','Edit Propinsi');
                $('#fmProvince').form('load',row);
            }
        }
        function saveProvince(){
            $('#fmProvince').form('submit',{
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
                        $('#dlgProvince').dialog('close');        // close the dialog
                        $('#dg').datagrid('reload');    // reload the user data
                    }
                }
            });
        }
        function destroyRecordProvince(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Anda ingin menghapus propinsi ini?',function(r){
                    if (r){
                        $.ajax({
                            type: 'DELETE',
                            url: '{{ route("province.destroy") }}',
                            dataType: 'json',
                            data: {
                                'id': row.id,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(result) {
                                $('#dg').datagrid('reload');
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
