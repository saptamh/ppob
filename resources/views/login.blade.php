@include('includes.header')
<div id="dlg" data-options="buttons:'#dlg-buttons'">
    <form id="fm" method="post" style="padding:10px 40px;">
        <div style="margin-top:20px"><input class="easyui-textbox" name="username" label="Email:" type="email" required="true" style="width:100%"></div>
        <div style="margin-top:20px"><input class="easyui-textbox" name="password" label="Password:" type="password" required="true" style="width:100%"></div>
    </form>
</div>
<div id="dlg-buttons">
    <a href="javascript:void(0)" id="dlgSaveBtn" iconCls="icon-ok" onclick="save()" style="width:90px">Simpan</a>
    <a href="javascript:void(0)" id="dlgCloseBtn" iconCls="icon-cancel" onclick="javascript:$('#win').window('close')" style="width:90px">Batal</a>
</div>
<script>
    var baseUrl = "{{ URL::to('/') }}";
    $("#dlgSaveBtn,#dlgCloseBtn").linkbutton();
    $("#dlg").dialog({
        title: 'Login',
        width: '400px',
        closed: false,
        cache: false,
    });
    $("#fm").form();
    $("#easyui-textbox").textbox();
    function save(){
        $("#fm").form('submit',{
            url: "{{ route('login-check') }}",
            iframe: false,
            dataType: 'json',
            onSubmit: function(param){
                param._token = "{{csrf_token()}}";
                return $(this).form('validate');
            },
            success: function(result){
                window.location.href = baseUrl;
            }
        });
    }
</script>
