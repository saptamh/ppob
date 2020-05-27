@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Create Salary Payment</h1>
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
        'url' => route('salary-payment.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('employee_id', 'Employee') }}
                {{ Form::select('employee_id', $employee, null, ['class'=>'form-control', 'placeholder'=>'Select Employee', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('salary', 'Nominal') }}
                {{ Form::text('salary', '', ['class'=>'form-control', 'placeholder'=>'Enter Nominal']) }}
            </div>
            <div class="form-group">
                {{ Form::label('payment_date', 'Payment Date') }}
                {{ Form::text('payment_date', '', ['class'=>'form-control', 'placeholder'=>'Enter Payment Date', 'required'=>'true']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('periode', 'Periode') }}
                {{ Form::text('periode', '', ['class'=>'form-control', 'placeholder'=>'Enter Periode', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('receipe', 'Receipe') }}
                {{ Form::file('receipe',  ['class'=>'form-control', 'placeholder'=>'Enter Receipe']) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', '', ['class'=>'form-control', 'placeholder'=>'Enter Description', 'rows'=>3, 'required'=>'true']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm btn-block']) }}
        </div>
        <div class="col-lg-6">
            <a href="{{ route('salary-payment.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('style')
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
@endpush
@push('script')
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
<script>
$(document).ready(function() {
    var baseUrl = "{{ URL::to('/') }}";
    $('#payment_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd',
        keyboardNavigation: true,
        showOtherMonths: true
    });
    $('#periode').datepicker({
        uiLibrary: 'bootstrap',
        format: 'mmmm, yyyy',
        keyboardNavigation: true,
        showOtherMonths: true
    });

    $("#employee_id").change(function() {
        var val = $("#employee_id").val();
        $("#salary").val('');
        if (val != "") {
            $.ajax({
                method: 'GET',
                url: baseUrl + "/salary-payment/salary/" + val,
                dataType: 'json',
                data:{ _token: "{{csrf_token()}}"},
                success: function (res) {
                    if (res.data.value) {
                        $("#salary").val(res.data.value);
                    }
                }
            });
        }
    });
});
</script>
@endpush
