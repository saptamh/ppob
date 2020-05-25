@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Edit Project</h1>
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
    {{ Form::hidden('id', $edit['id']) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('no_contract', 'No Contract') }}
                {{ Form::text('no_contract', $edit['no_contract'], ['class'=>'form-control', 'placeholder'=>'Enter No Contract', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('name', 'Project Name') }}
                {{ Form::text('name', $edit['name'], ['class'=>'form-control', 'placeholder'=>'Enter Project Name']) }}
            </div>
            <div class="form-group">
                {{ Form::label('address', 'Address') }}
                {{ Form::textarea('address', $edit['address'], ['class'=>'form-control', 'placeholder'=>'Enter Address', 'rows'=>3, 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('pic_customer', 'Pic Customer') }}
                {{ Form::text('pic_customer', $edit['pic_customer'], ['class'=>'form-control', 'placeholder'=>'Enter PIC', 'required'=>'true']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('customer', 'Customer') }}
                {{ Form::text('customer', $edit['customer'], ['class'=>'form-control', 'placeholder'=>'Enter Customer', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('work_type', 'Work Type') }}
                {{ Form::text('work_type', $edit['work_type'], ['class'=>'form-control', 'placeholder'=>'Enter Work Type', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('start_date', 'Start Date') }}
                {{ Form::text('start_date', $edit['start_date'], ['class'=>'form-control', 'placeholder'=>'Select Date', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('end_date', 'End Date') }}
                {{ Form::text('end_date', $edit['end_date'], ['class'=>'form-control', 'placeholder'=>'Select Date']) }}
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
    <br>
    <div class="row">
        <div class="col-lg-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#log_project">Log Project</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#document">Document</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="log_project" class="container tab-pane active"><br>
                    <h3>Log Project</h3>
                    <div class="log-project-content"></div>
                </div>
                <div id="document" class="container tab-pane"><br>
                    <h3>Document</h3>
                    <div class="document-content"></div>
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
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
<script>
function showLogProject() {
    $(".log-project-content").empty();
    $(".log-project-content").load("{{ url('/log-project/template/' . $edit['id']) }}");
}
function showDocumentProject() {
    $(".document-content").empty();
    $(".document-content").load("{{ url('/document-project/template/' . $edit['id']) }}");
}
$(document).ready(function() {
    showLogProject();
    $('#start_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });
    $('#end_date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd'
    });

    $('#myTabs a').click(function (link) {
        var activeTab = link.currentTarget.innerText;
        if (activeTab === "Log_project") {
            showLogProject();
        }
        if (activeTab === "Document") {
            showDocumentProject();
        }
    });
});
</script>
@endpush
