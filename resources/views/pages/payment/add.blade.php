@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Create Petty Cash</h1>
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
        'url' => route('petty-cash.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('budget_for', 'Budget For') }}
                {{ Form::select('budget_for', array_combine($select_box['budget'], $select_box['budget']), null, ['class'=>'form-control', 'placeholder'=>'Select Budget For', 'required'=>'true']) }}
            </div>
            <div id="project_content"></div>
            <div class="form-group">
                {{ Form::label('date', 'Date') }}
                {{ Form::text('date', '', ['class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('noted_news', 'Noted News') }}
                {{ Form::text('noted_news', '', ['class'=>'form-control', 'placeholder'=>'Enter Noted News', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('name_bank_from', 'From Bank') }}
                {{ Form::text('name_bank_from', '', ['class'=>'form-control', 'placeholder'=>'Enter Name Bank', 'required'=>'true']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('name_bank_to', 'To Bank') }}
                {{ Form::text('name_bank_to', '', ['class'=>'form-control', 'placeholder'=>'Enter Name Bank', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('type', 'Type') }}
                {{ Form::select('type', array_combine($select_box['type'], $select_box['type']), null, ['class'=>'form-control', 'placeholder'=>'Select Type', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('nominal', 'Nominal') }}
                {{ Form::text('nominal', '', ['class'=>'form-control', 'placeholder'=>'Enter Nominal', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('upload', 'Upload') }}
                {{ Form::file('upload',  ['class'=>'form-control', 'placeholder'=>'Upload File']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm btn-block']) }}
        </div>
        <div class="col-lg-6">
            <a href="{{ route('petty-cash.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('style')
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
@endpush
@push('script')
@include('pages.petty-cash/project-template')
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/handlebars/handlebars.min-v4.7.6.js') }}"></script>
<script>
async function showProject(data) {
    $('#project_content').html('');

    Handlebars.registerHelper('isSelected', function (label) {
        return '';
    });

    var t = Handlebars.compile($('#project-template').html());

    var obj = {
        selectedProject: "",
        projects: await getProject(),
    };
    var $html = $(t(obj));

    $('#project_content').append($html);
}

async function getProject() {
    var result = await $.ajax({
        method: 'GET',
        url: baseUrl + "/salary-payment/project",
        dataType: 'json',
        data:{ _token: "{{csrf_token()}}"},
        success: function (res) {
            return res.data.data;
        }
    });
    console.log("Res", result);
    return result;
}

$(document).ready(function() {
    var baseUrl = "{{ URL::to('/') }}";
    $('#date').datepicker({
        uiLibrary: 'bootstrap',
        format: 'yyyy-mm-dd',
        keyboardNavigation: true,
        showOtherMonths: true
    });

    $("#budget_for").change(function() {
        var val = $("#budget_for").val();
        console.log(val);

        if (val == "OFFICE") {
            $('#project_content').html('');
        } else {
            showProject();
        }
    });
});
</script>
@endpush
