@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Edit Employees</h1>
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
            {{ Form::hidden('id', $edit['id']) }}
            <div class="form-group">
                {{ Form::label('nik', 'NIK') }}
                {{ Form::text('nik', $edit['nik'], ['class'=>'form-control', 'placeholder'=>'Enter NIK', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('no_npwp', 'No.NPWP') }}
                {{ Form::text('no_npwp', $edit['no_npwp'], ['class'=>'form-control', 'placeholder'=>'Enter No.NPWP']) }}
            </div>
            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', $edit['name'], ['class'=>'form-control', 'placeholder'=>'Enter Name', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('address', 'Address') }}
                {{ Form::textarea('address', $edit['address'], ['class'=>'form-control', 'placeholder'=>'Enter Address', 'rows'=>3, 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('religion', 'Religion') }}
                {{ Form::select('religion', array_combine($religion, $religion),  $edit['religion'], ['class'=>'form-control', 'placeholder'=>'Select Religion', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('education', 'Education') }}
                {{ Form::select('education', array_combine($education, $education),  $edit['education'], ['class'=>'form-control', 'placeholder'=>'Select Education', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('location', 'Location') }}
                {{ Form::select('location', array_combine($location, $location),  $edit['location'], ['class'=>'form-control', 'placeholder'=>'Select Location', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('is_merried', 'Merried') }}
                <div class="form-check">
                    {{ Form::radio('is_merried', 'Y', $edit['is_merried'] == 'Y' ? true : false, ['class'=>'form-check-input']) }}
                    {{ Form::label('y', 'Yes', ['class'=>'form-check-label']) }}
                </div>
                <div class="form-check">
                    {{ Form::radio('is_merried', 'N', $edit['is_merried'] == 'N' ? true : false, ['class'=>'form-check-input']) }}
                    {{ Form::label('n', 'No', ['class'=>'form-check-label']) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('sex', 'Sex') }}
                <div class="form-check">
                    {{ Form::radio('sex', 'M', $edit['sex'] == 'M' ? true : false, ['class'=>'form-check-input']) }}
                    {{ Form::label('male', 'Male', ['class'=>'form-check-label']) }}
                </div>
                <div class="form-check">
                    {{ Form::radio('sex', 'F', $edit['sex'] == 'F' ? true : false, ['class'=>'form-check-input']) }}
                    {{ Form::label('n', 'Female', ['class'=>'form-check-label']) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('start_date', 'Start Date') }}
                {{ Form::text('start_date', $edit['start_date'], ['class'=>'form-control', 'placeholder'=>'Select Date', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('end_date', 'End Date') }}
                {{ Form::text('end_date', $edit['end_date'], ['class'=>'form-control', 'placeholder'=>'Select Date']) }}
            </div>
            <div class="form-group">
                {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm']) }}
                <a href="{{ route('employee.main') }}" class="btn btn-warning btn-sm"> Cancel! </a>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#family">Family</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#salary">Salary</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#level">Level</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#document">Documents</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="family" class="container tab-pane active"><br>
                    <h3>Family</h3>
                    <div class="family-content"></div>
                </div>
                <div id="salary" class="container tab-pane"><br>
                    <h3>Salary</h3>
                    <div class="salary-content"></div>
                </div>
                <div id="level" class="container tab-pane"><br>
                    <h3>Level</h3>
                    <div class="level-content"></div>
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
function showFamily() {
    $(".family-content").empty();
    $(".family-content").load("{{ url('/employee-family/template/' . $edit['id']) }}");
}
function showSalary() {
    $(".salary-content").empty();
    $(".salary-content").load("{{ url('/employee-salary/template/' . $edit['id']) }}");
}
function showLevel() {
    $(".level-content").empty();
    $(".level-content").load("{{ url('/employee-level/template/' . $edit['id']) }}");
}
function showDocument() {
    $(".document-content").empty();
    $(".document-content").load("{{ url('/document-employee/template/' . $edit['id']) }}");
}
$(document).ready(function() {
    showFamily();
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
        console.log(activeTab);
        if (activeTab === "Family") {
            showFamily();
        }
        if (activeTab === "Salary"){
            showSalary();
        }
        if (activeTab === "Level"){
            showLevel();
        }
        if (activeTab === "Documents"){
            showDocument();
        }
    });
});
</script>
@endpush
