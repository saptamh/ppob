@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Create Employees</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    {{ Form::open([
        'id'=>'form-pages',
        'url' => route('employee.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="form-group">
        {{ Form::label('nik', 'NIK') }}
        {{ Form::text('nik', '', ['class'=>'form-control', 'placeholder'=>'Enter NIK', 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('no_npwp', 'No.NPWP') }}
        {{ Form::text('no_npwp', '', ['class'=>'form-control', 'placeholder'=>'Enter No.NPWP']) }}
    </div>
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Enter Name', 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('birth_date', 'Birth Date') }}
        {{ Form::text('birth_date', '', ['class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('address', 'Address') }}
        {{ Form::textarea('address', '', ['class'=>'form-control', 'placeholder'=>'Enter Address', 'rows'=>3, 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('religion', 'Religion') }}
        {{ Form::select('religion', array_combine($select_box['religion'], $select_box['religion']),  null, ['class'=>'form-control', 'placeholder'=>'Select Religion', 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('education', 'Education') }}
        {{ Form::select('education', array_combine($select_box['education'], $select_box['education']),  null, ['class'=>'form-control', 'placeholder'=>'Select Education', 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('location', 'Location') }}
        {{ Form::select('location', array_combine($select_box['location'], $select_box['location']),  null, ['class'=>'form-control', 'placeholder'=>'Select Location', 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('status', 'Status') }}
        {{ Form::select('status', array_combine($select_box['status'], $select_box['status']),  null, ['class'=>'form-control', 'placeholder'=>'Select Status', 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('is_merried', 'Merried') }}
        <div class="form-check">
            {{ Form::radio('is_merried', 'Y', true, ['class'=>'form-check-input']) }}
            {{ Form::label('y', 'Yes', ['class'=>'form-check-label']) }}
        </div>
        <div class="form-check">
            {{ Form::radio('is_merried', 'N', false, ['class'=>'form-check-input']) }}
            {{ Form::label('n', 'No', ['class'=>'form-check-label']) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('sex', 'Sex') }}
        <div class="form-check">
            {{ Form::radio('sex', 'M', true, ['class'=>'form-check-input']) }}
            {{ Form::label('male', 'Male', ['class'=>'form-check-label']) }}
        </div>
        <div class="form-check">
            {{ Form::radio('sex', 'F', false, ['class'=>'form-check-input']) }}
            {{ Form::label('n', 'Female', ['class'=>'form-check-label']) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('start_date', 'Start Date') }}
        {{ Form::text('start_date', '', ['class'=>'form-control', 'placeholder'=>'Select Date', 'required'=>'true']) }}
    </div>
    <div class="form-group">
        {{ Form::label('end_date', 'End Date') }}
        {{ Form::text('end_date', '', ['class'=>'form-control', 'placeholder'=>'Select Date']) }}
    </div>
    <div class="form-group">
        {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm']) }}
        <a href="{{ route('employee.main') }}" class="btn btn-warning btn-sm"> Cancel! </a>
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
    $('#start_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });
    $('#end_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });
    $('#birth_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });
});
</script>
@endpush
