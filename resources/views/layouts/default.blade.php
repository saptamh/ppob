<!-- Content Wrapper -->
@include('includes.header')
<body>
<div id="content-wrapper" class="d-flex flex-column">
    <div class="easyui-layout" style="width:100%;height:500px;">
        <div data-options="region:'west',split:true" title="Main Menu" style="width:208px;">
        <div id="sm" class="easyui-sidemenu" data-options="data:data, onSelect:openPage"></div>
    </div>
    <div data-options="region:'center'">
            <div id='container'></div>
    </div>
</div>
<script type="text/javascript">
        var data = [{
            text: 'Master',
            iconCls: 'icon-more',
            // state: 'open',
            children: [{
                text: 'Pelanggan',
                link: "{{ route('customer.main') }}"
            },{
                text: 'Pengguna',
                link: "{{ route('user.main') }}"
            },{
                text: 'Area',
                children: [{
                    text: 'Propinsi',
                    link: "{{ route('province.main') }}"
                },{
                    text: 'Kota',
                    link: "{{ route('city.main') }}"
                },{
                    text: 'Kecamatan',
                    link: "{{ route('district.main') }}"
                },{
                    text: 'Desa',
                    link: "{{ route('village.main') }}"
                }]
            }]
        },{
            text: 'Mapping',
            iconCls: 'icon-more',
            children: [{
                text: 'Kustomer',
                link: "{{ route('mapping.main') }}"
            }]
        },{
            text: 'Transaksi',
            iconCls: 'icon-more',
            children: [{
                text: 'Jatuh Tempo',
                link: "{{ route('transaction.late-pay-main') }}"
            },
            {
                text: 'Terbayar',
                link: "{{ route('transaction.late-pay-main') }}"
            }]
        },{
            text: 'Sistem',
            iconCls: 'icon-more',
            children: [{
                text: 'Keluar',
                link: "{{ route('transaction.late-pay-main') }}"
            }]
        }];

        function openPage(item){
            console.log(item.link);
            // $('#container').html("");
            $('#container').load(item.link);
        }

        $("#logo2").on('click', function() {
            $('#container').load("{{ route('user.main') }}");
        });
    </script>
</body>
</html>

