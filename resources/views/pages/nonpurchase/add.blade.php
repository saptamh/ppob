@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Create Non Purchase</h1>
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
        'url' => route('nonpurchase.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('type_object', 'Budget For') }}
                {{ Form::select('type_object', array_combine($select_box['object'], $select_box['object']), null, ['class'=>'form-control', 'placeholder'=>'Select Budget For', 'required'=>'true']) }}
            </div>
            <div id="project_content"></div>
            <div class="form-group">
                {{ Form::label('date', 'Date') }}
                {{ Form::text('date', '', ['class'=>'form-control', 'placeholder'=>'Enter Date', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('type', 'Type') }}
                {{ Form::select('type', $select_box['type'], null, ['class'=>'form-control', 'placeholder'=>'Select Type', 'required'=>'true']) }}
            </div>
            <div id="other_content"></div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('payment', 'Paid Note (pembayaran)') }}
                {{ Form::text('payment', '', ['class'=>'form-control', 'placeholder'=>'Enter Paid Note', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('nominal', 'Nominal') }}
                {{ Form::text('nominal', '', ['class'=>'form-control', 'placeholder'=>'Enter Nominal', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', '', ['class'=>'form-control', 'placeholder'=>'Enter Description', 'rows'=>2, 'required'=>'true']) }}
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
            <a href="{{ route('nonpurchase.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('style')
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
@endpush
@push('script')
@include('pages.nonpurchase/project-template')
@include('pages.nonpurchase/other-template')
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

function showOther() {
    var source   = document.getElementById("other-template").innerHTML;
    var template = Handlebars.compile(source);
    var data = {};
    var html = template(data);

    $('#other_content').append(html);
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

    $("#type_object").change(function() {
        var val = $("#type_object").val();
        console.log(val);

        if (val == "OFFICE") {
            $('#project_content').html('');
        } else {
            showProject();
        }
    });

    $("#type").change(function() {
        var val = $("#type").val();

        if (val == 5) {
            showOther();
        } else {
            $('#other_content').html('');
        }
    });
});
</script>
@endpush
