@extends('layouts.default')

@section('content')
<?php
    $timeline_date = \Carbon\Carbon::createFromFormat('Y-m-d', $timeline->date);
    $end_date = $timeline_date->addDays($timeline->duration)->toDateString();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Add Project Daily</h1>
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Project Timeline
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <p><b>Project:</b> {{$timeline->project->name}}</p>
                            <p><b>Job:</b> {{$timeline->projectJob->name}}</p>
                            <p><b>Item:</b> {{$timeline->projectItem->name}}</p>
                            <p><b>Zone:</b> {{$timeline->projectZone->name}}</p>
                        </div>
                        <div class="col-lg-6">
                            <p><b>Start Date:</b> {{$timeline->date}}</p>
                            <p><b>Duration:</b> {{$timeline->duration}}</p>
                            <p><b>End Date:</b> {{$end_date}}</p>
                            <p><b>Quantity:</b> {{$timeline->qty}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    {{ Form::open([
        'id'=>'form-pages',
        'url' => route('project-daily.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('date', 'Date') }}
                {{ Form::hidden('project_timeline_id', $timeline->id)}}
                {{ Form::text('date', '', ['class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true', 'readonly'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('employee_id', 'PIC') }}
                {{ Form::select('employee_id', $select_box['employee'], '', ['class'=>'form-control', 'placeholder'=>'Select PIC']) }}
            </div>
            <div class="form-group">
                {{ Form::label('job', 'Job') }}
                {{ Form::text('job', $timeline->projectJob->name . ' ' . $timeline->projectItem->name . ' ' . $timeline->projectZone->name, ['class'=>'form-control', 'placeholder'=>'Enter Job', 'required'=>'true', 'readonly'=>'true']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('target', 'Target') }}
                {{ Form::number('target', '', ['class'=>'form-control', 'placeholder'=>'Enter Target', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('worked_hour', 'Worked Hour') }}
                {{ Form::number('worked_hour', '', ['class'=>'form-control', 'placeholder'=>'Enter Worked Hour', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('realisation', 'Realisation') }}
                {{ Form::number('realisation', '', ['class'=>'form-control', 'placeholder'=>'Enter Realisation', 'required'=>'true']) }}
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
            <a href="{{ route('project-daily.main', ['timeline_id'=>$timeline->id]) }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
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
