@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Create Purchase</h1>
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
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('supplier_name', 'Supplier Name') }}
                {{ Form::text('supplier_name', '', ['class'=>'form-control', 'placeholder'=>'Enter Supplier Name', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('supplier_address', 'Supplier Address') }}
                {{ Form::textarea('supplier_address', '', ['class'=>'form-control', 'placeholder'=>'Enter Suplier Address', 'rows'=>2, 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('supplier_phone', 'Supplier Phone') }}
                {{ Form::text('supplier_phone', '', ['class'=>'form-control', 'placeholder'=>'Enter Supplier Phone', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('term_of_payment', 'Term Of Payment') }}
                {{ Form::select('term_of_payment', $term, null, ['class'=>'form-control', 'placeholder'=>'Select Term', 'required'=>'true']) }}
            </div>
            <div id="term_of_payment_content"></div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('project_id', 'Project') }}
                {{ Form::select('project_id', $projects, null, ['class'=>'form-control', 'placeholder'=>'Select Project', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('incoming_date', 'Incoming Date') }}
                {{ Form::text('incoming_date', '', ['class'=>'form-control', 'placeholder'=>'Enter Incoming Date', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('payment_status', 'Payment Status') }}
                {{ Form::select('payment_status', $payment_status, null, ['class'=>'form-control', 'placeholder'=>'Select Payment Status', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('shipping_address', 'Shipping Address') }}
                {{ Form::textarea('shipping_address', '', ['class'=>'form-control', 'rows'=>2, 'placeholder'=>'Enter Shipping Address', 'required'=>'true']) }}
            </div>
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
</div>
@endsection
@push('style')
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
@endpush
@push('script')
@include('pages.purchase/down-payment-template')
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/handlebars/handlebars.min-v4.7.6.js') }}"></script>
<script>
function termOfPayment(val) {
    if (val == 2) {
        var t = Handlebars.compile($('#entry-template').html());
        var data = {};
        var $html = $(t(data));
        $html.find('#due_date').datepicker({
            uiLibrary: 'bootstrap',
            format: 'yyyy-mm-dd'
        });
        $('#term_of_payment_content').append($html);
    } else {
        $('#term_of_payment_content').html('');
    }
}
$(document).ready(function() {
    $('#incoming_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });

    $("#term_of_payment").change(function() {
        termOfPayment($(this).val());
    });
});
</script>
@endpush
