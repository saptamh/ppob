@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Create Project</h1>
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
        'url' => route('project.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-6">
        <div class="form-group">
                {{ Form::label('no_contract', 'No Contract') }}
                {{ Form::text('no_contract', '', ['class'=>'form-control', 'placeholder'=>'Enter No Contract', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('name', 'Project Name') }}
                {{ Form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Enter Project Name']) }}
            </div>
            <div class="form-group">
                {{ Form::label('address', 'Address') }}
                {{ Form::textarea('address', '', ['class'=>'form-control', 'placeholder'=>'Enter Address', 'rows'=>3, 'required'=>'true']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('customer', 'Customer') }}
                {{ Form::text('customer', '', ['class'=>'form-control', 'placeholder'=>'Enter Customer', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('work_type', 'Work Type') }}
                {{ Form::text('work_type', '', ['class'=>'form-control', 'placeholder'=>'Enter Work Type', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('pic_customer', 'Pic Customer') }}
                {{ Form::text('pic_customer', '', ['class'=>'form-control', 'placeholder'=>'Enter PIC', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('start_date', 'Start Date') }}
                {{ Form::text('start_date', '', ['id'=>'start_date_id', 'class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm btn-block']) }}
        </div>
        <div class="col-lg-6">
            <a href="{{ route('project.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
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
    $('#start_date_id').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });
});
</script>
@endpush
