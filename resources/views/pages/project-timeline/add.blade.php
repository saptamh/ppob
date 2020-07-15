@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Add Project Timeline</h1>
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
        'url' => route('project-timeline.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('projects_id', 'Project') }}
                {{ Form::select('projects_id', $select_box['projects'], '', ['class'=>'form-control', 'placeholder'=>'Select Project', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('manager_id', 'Project Manager') }}
                {{ Form::select('manager_id', $select_box['manager'], '', ['class'=>'form-control', 'placeholder'=>'Select Manager']) }}
            </div>
            <div class="form-group">
                {{ Form::label('type', 'Type') }}
                {{ Form::select('type', array_combine($select_box['type'], $select_box['type']), '', ['class'=>'form-control', 'placeholder'=>'Select Type']) }}
            </div>
            <div class="form-group">
                {{ Form::label('date', 'Date') }}
                {{ Form::text('date', '', ['class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true', 'readonly'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('item', 'Item') }}
                {{ Form::select('project_item_id', $select_box['project_items'], '', ['class'=>'form-control', 'placeholder'=>'Select Item', 'required'=>'true']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('job', 'Job') }}
                {{ Form::select('project_job_id', $select_box['project_jobs'], '', ['class'=>'form-control', 'placeholder'=>'Select Job', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('zone', 'Zone') }}
                {{ Form::select('project_zone_id', $select_box['project_zones'], '', ['class'=>'form-control', 'placeholder'=>'Select Zone', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('qty', 'Quantity') }}
                {{ Form::number('qty', '', ['class'=>'form-control', 'placeholder'=>'Enter Quantity', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('duration', 'Duration') }}
                {{ Form::number('duration', '', ['class'=>'form-control', 'placeholder'=>'Enter Duration', 'required'=>'true']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm btn-block']) }}
        </div>
        <div class="col-lg-6">
            <a href="{{ route('project-timeline.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
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
    $('#date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });
});
</script>
@endpush
