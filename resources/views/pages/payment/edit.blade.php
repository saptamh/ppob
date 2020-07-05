@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Edit Payment</h1>
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
        'url' => route('payment.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    {{ Form::hidden('id', $edit['id'])}}
    {{ Form::hidden('project_id', $edit['project_id'])}}
    {{ Form::hidden('payment_name', $edit['payment_name'])}}
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('payment_total', 'Payment Total') }}
                {{ Form::text('payment_total', $edit['payment_total'], ['class'=>'form-control', 'readonly'=>'true', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('payment_total', 'PR Document') }}
                <div>
                    <a href="{{ $pr_document['upload'] }}" target="_blank">
                        <img src="{{ $pr_document['upload'] }}" style="widht: 100px;height:100px;"/>
                    </a>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('pr_document', 'Source') }}
                {{ Form::select('source_id', $source, $edit['project_id'], ['class'=>'form-control', 'placeholder'=>'Select Source', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('payment_method', 'Payment Method') }}
                {{ Form::select('payment_method', ['TRANSFER'=>'TRANSFER', 'CASH'=>'CASH'], $edit['payment_method'], ['class'=>'form-control', 'placeholder'=>'Select Payment Method', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('paid_date', 'Paid Date') }}
                {{ Form::text('paid_date', $edit['paid_date'], ['class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('upload', 'Upload') }}
                {{ Form::file('upload',  ['class'=>'form-control', 'placeholder'=>'Upload File']) }}
                {{ Form::hidden('upload_hidden', $edit['upload'], ['class'=>'form-control', 'required'=>'true']) }}
                <br>
                <center>
                    <img class="img-responsive img-circle" style="width: 150px;height:150px" src="{{ $edit['upload'] }}">
                </center>
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', $edit['description'], ['class'=>'form-control', 'rows'=>3, 'placeholder'=>'Enter Description', 'required'=>'true']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">

        </div>
        <div class="col-lg-4">

        </div>
        <div class="col-lg-4" style="text-align:right;">
            {{ Form::submit('Paid', ['name'=>'paid', 'class'=>'btn btn-outline-secondary btn-sm']) }}
            {{ Form::submit('Reject', ['name'=>'reject', 'class'=>'btn btn-outline-secondary btn-sm']) }}
            <a href="{{ route('payment.main') }}" class="btn btn-outline-secondary btn-sm"> Cancel</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id="salary_content"></div>
            <div id="nonpurchase_content"></div>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('style')
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
@endpush
@push('script')
@include('pages.payment/salary-template')
@include('pages.payment/nonpurchase-template')
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/handlebars/handlebars.min-v4.7.6.js') }}"></script>
<script>
async function showSalary(data) {
    $('#salary_content').html('');
    $('#nonpurchase_content').html('');

    var t = Handlebars.compile($('#salary-template').html());

    var obj = {
        salary: await getSalary(),
    };
    console.log("OBJ", obj);
    var $html = $(t(obj));

    $('#salary_content').append($html);
}

async function showNonpurchase(data) {
    $('#salary_content').html('');
    $('#nonpurchase_content').html('');

    var t = Handlebars.compile($('#nonpurchase-template').html());

    var obj = {};

    var $html = $(t(obj));

    $('#nonpurchase_content').append($html);
}

async function getSalary() {
    var result = await $.ajax({
        method: 'GET',
        url: baseUrl + "/payment/get-salary-payment",
        dataType: 'json',
        data:{ _token: "{{csrf_token()}}", payment_id: "{{$edit['payment_id']}}"},
        success: function (res) {
            return res.data.data;
        }
    });
    console.log("Res", result);
    return result;
}

$(document).ready(function() {
    var baseUrl = "{{ URL::to('/') }}";

    $('#paid_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd',
        keyboardNavigation: true,
        showOtherMonths: true
    });

    var payment_type = "{{$edit['payment_type']}}";
    if (payment_type == "SALARY") {
        showSalary();
    }

    if (payment_type == "NONPURCHASE") {
        showNonpurchase();
    }
});
</script>
@endpush
