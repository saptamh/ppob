@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Edit Purchase</h1>
                @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    {{ Form::open([
        'id'=>'form-pages',
        'url' => route('purchase.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    {{ Form::hidden('id', $edit['id']) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('supplier_name', 'Supplier Name') }}
                {{ Form::text('supplier_name', $edit['supplier_name'], ['class'=>'form-control', 'placeholder'=>'Enter Supplier Name', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('supplier_address', 'Supplier Address') }}
                {{ Form::textarea('supplier_address', $edit['supplier_address'], ['class'=>'form-control', 'placeholder'=>'Enter Suplier Address', 'rows'=>2, 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('supplier_phone', 'Supplier Phone') }}
                {{ Form::text('supplier_phone', $edit['supplier_phone'], ['class'=>'form-control', 'placeholder'=>'Enter Supplier Phone', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('term_of_payment', 'Term Of Payment') }}
                {{ Form::select('term_of_payment', $term, $edit['term_of_payment'], ['class'=>'form-control', 'placeholder'=>'Select Term', 'required'=>'true']) }}
            </div>
            <div id="term_of_payment_content"></div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('project_id', 'Project') }}
                {{ Form::select('project_id', $projects, $edit['project_id'], ['class'=>'form-control', 'placeholder'=>'Select Project', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('incoming_date', 'Incoming Date') }}
                {{ Form::text('incoming_date', $edit['incoming_date'], ['class'=>'form-control', 'placeholder'=>'Enter Incoming Date']) }}
            </div>
            <div class="form-group">
                {{ Form::label('payment_status', 'Payment Status') }}
                {{ Form::select('payment_status', $payment_status, $edit['payment_status'], ['class'=>'form-control', 'placeholder'=>'Select Payment Status', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('shipping_address', 'Shipping Address') }}
                {{ Form::textarea('shipping_address', $edit['shipping_address'], ['class'=>'form-control', 'rows'=>2, 'placeholder'=>'Enter Shipping Address', 'required'=>'true']) }}
            </div>
            <div id="down_payment_content"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm btn-block']) }}
        </div>
        <div class="col-lg-6">
            <a href="{{ route('purchase.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
        </div>
    </div>
    {{ Form::close() }}
    <br>
    <div class="row">
        <div class="col-lg-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#goods">Goods</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="goods" class="container tab-pane active"><br>
                    <h3>Goods</h3>
                    <div class="goods-content"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
@endpush
@push('script')
@include('pages.purchase/term-of-payment-template')
@include('pages.purchase/down-payment-template')
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/handlebars/handlebars.min-v4.7.6.js') }}"></script>
<script>
function showGoods() {
    $(".goods-content").empty();
    $(".goods-content").load("{{ url('/purchase-goods/template/' . $edit['id']) }}");
}

function showTermOfPayment() {
    var t = Handlebars.compile($('#entry-template').html());
    var data = {
            downPayment: "{{ $edit['down_payment'] }}",
            dueDate: "{{ $edit['due_date'] }}",
        };
    var $html = $(t(data));
    $html.find('#due_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });
    $('#term_of_payment_content').append($html);
}

function showDownPayment() {
    var t = Handlebars.compile($('#down-payment-template').html());
    var data = {
            downPayment: "{{ $percentase }}",
            dueDate: "{{ $nominal }}",
        };
    var $html = $(t(data));
    $html.find('#due_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });
    $('#down_payment_content').append($html);
}

function termOfPayment(val) {
    if (val == 2) {
        showTermOfPayment();
        showDownPayment();
    } else {
        $('#term_of_payment_content').html('');
        $('#down_payment_content').html('');
    }
}
$(document).ready(function() {
    termOfPayment($("#term_of_payment").val());
    showGoods();

    $('#incoming_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });

    $("#term_of_payment").change(function() {
        termOfPayment($(this).val());
    });

    $('#myTabs a').click(function (link) {
        var activeTab = link.currentTarget.innerText;
        if (activeTab === "Goods") {
            showGoods();
        }
    });
});
</script>
@endpush
