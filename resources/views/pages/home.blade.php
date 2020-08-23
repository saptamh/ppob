@extends('layouts.default')

@section('content')
<head>
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui-1.9.7/themes/default/easyui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui-1.9.7/themes/icon.css') }}">
    <script type="text/javascript" src="{{ asset('jquery-easyui-1.9.7/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('jquery-easyui-1.9.7/jquery.easyui.min.js') }}"></script>
</head>
    <div class="easyui-layout" style="width:100%;height:350px;">

        <div data-options="region:'west',split:true" title="West" style="width:208px;">
            <div id="sm" class="easyui-sidemenu" data-options="data:data"></div>
        </div>
        <div data-options="region:'center',title:'Main Title',iconCls:'icon-ok'">
            <div class="easyui-tabs" data-options="fit:true,border:false,plain:true">
                <a id="logo" href="#" class="easyui-linkbutton" data-options="plain:true">SEGES</a>
                <a id="logo2" href="#" class="easyui-linkbutton" data-options="plain:true">SEGES2</a>

                <div id='container'></div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var data = [{
            text: 'Item1',
            iconCls: 'icon-sum',
            state: 'open',
            children: [{
                text: 'Option1'
            },{
                text: 'Option2'
            },{
                text: 'Option3',
                children: [{
                    text: 'Option31'
                },{
                    text: 'Option32'
                }]
            }]
        },{
            text: 'Item2',
            iconCls: 'icon-more',
            children: [{
                text: 'Option4'
            },{
                text: 'Option5'
            },{
                text: 'Option6'
            }]
        }];

        $("#logo2").on('click', function() {
            $('#container').load("{{ route('user.main') }}");
        });
    </script>
@endsection
