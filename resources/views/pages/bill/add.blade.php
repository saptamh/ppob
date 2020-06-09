@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Create Bon Material</h1>
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
        'url' => route('bill.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('project_id', 'Project') }}
                {{ Form::select('project_id', $project, null, ['class'=>'form-control', 'placeholder'=>'Select Project', 'required'=>'true']) }}
            </div>
            <div id="salary_content"></div>
            <div class="form-group">
                {{ Form::label('bill_type', 'Bon Type') }}
                {{ Form::select('bill_type', array_combile($bill_type), ['class'=>'form-control', 'placeholder'=>'Select Bon Type', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('periode', 'Periode') }}
                {{ Form::text('periode', '', ['class'=>'form-control', 'placeholder'=>'Enter Periode', 'required'=>'true']) }}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('work_day', 'Work Day') }}
                <div class="form-row">
                    <div class="col">
                        {{ Form::text('work_day', '0', ['class'=>'form-control calculate-salary', 'placeholder'=>'Enter Work Day', 'required'=>'true']) }}
                    </div>
                    <div class="col">
                        {{ Form::text('work_day_result', '0', ['id'=>'work_day_result', 'class'=>'form-control', 'readonly'=>'true']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('over_time_day', 'Over Time (Weekend/Public Holiday)') }}
                <div class="form-row">
                    <div class="col">
                        {{ Form::text('over_time_day', '0', ['class'=>'form-control calculate-salary', 'placeholder'=>'Enter Over Time (day)', 'required'=>'true']) }}
                    </div>
                    <div class="col">
                        {{ Form::text('over_time_day_result', '0', ['id'=>'over_time_day_result', 'class'=>'form-control', 'readonly'=>'true']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('over_time_hour', 'Over Time (Hour)') }}
                <div class="form-row">
                    <div class="col">
                        {{ Form::text('over_time_hour', '0', ['class'=>'form-control calculate-salary', 'placeholder'=>'Enter Over Time (hour)', 'required'=>'true']) }}
                    </div>
                    <div class="col">
                        {{ Form::text('over_time_hour_result', '0', ['id'=>'over_time_hour_result', 'class'=>'form-control', 'readonly'=>'true']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('meal_allowance', 'Meal') }}
                <div class="form-row">
                    <div class="col">
                        {{ Form::text('meal_allowance', '0', ['class'=>'form-control calculate-salary', 'placeholder'=>'Enter Meal Allowance', 'required'=>'true']) }}
                    </div>
                    <div class="col">
                        {{ Form::text('meal_allowance_result', '0', ['id'=>'meal_allowance_result', 'class'=>'form-control', 'readonly'=>'true']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('bonus', 'Bonus') }}
                {{ Form::text('bonus', '0', ['class'=>'form-control calculate-salary', 'placeholder'=>'Enter Bonus', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('cashbon', 'Cashbon') }}
                {{ Form::text('cashbon', '0', ['class'=>'form-control calculate-salary', 'placeholder'=>'Enter Cashbon', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('total_salary', 'Total Salary') }}
                {{ Form::text('total_salary', '0', ['class'=>'form-control', 'placeholder'=>'Automatically Calculate', 'readonly'=>'true', 'required'=>'true']) }}
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
            <a href="{{ route('bill.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('style')
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
@endpush
@push('script')
@include('pages.bill/salary-template')
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
<script src="{{ URL::asset('themes/vendor/handlebars/handlebars.min-v4.7.6.js') }}"></script>
<script>
function showSalary(data) {
    $('#salary_content').html('');
    var t = Handlebars.compile($('#salary-template').html());
    var $html = $(t(data));
    $html.find('input.calculate-salary').on('keyup', function() {
        calculate();
    });
    $('#salary_content').append($html);
}

function calculate() {
    if ($("#employee_id").val() != "") {
        console.log('YYYY');
        var base_salary = $("#base-salary-hide").val();
        var meal_allowance = $("#meal-allowance-hide").val();
        var weekend_allowance = $("#weekend-allowance-hide").val();
        var work_hour = $("#work-hour-hide").val();

        let work_day_result = base_salary;
        if ($("#employee-status").val() == "phl") {
            work_day_result = parseInt(base_salary) * parseInt($("#work_day").val());
        }

        var work_weekend_result = (parseInt(base_salary) * weekend_allowance) * parseInt($("#over_time_day").val());
        var work_hour_result = (parseInt(base_salary) / work_hour) * parseInt($("#over_time_hour").val());
        var meal_allowance_result = parseInt(meal_allowance) * parseInt($("#meal_allowance").val());

        $("#work_day_result").val(work_day_result);
        $("#over_time_day_result").val(work_weekend_result);
        $("#over_time_hour_result").val(work_hour_result);
        $("#meal_allowance_result").val(meal_allowance_result);

        var total_salary = (work_day_result + work_weekend_result + work_hour_result + meal_allowance_result + parseInt($("#bonus").val())) - parseInt($("#cashbon").val());

        $("#total_salary").val(total_salary);
    }
}
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
        if (val == "") {
            $('#salary_content').html('');
        } else {
            if (val != "") {
                $.ajax({
                    method: 'GET',
                    url: baseUrl + "/bill/salary/" + val,
                    dataType: 'json',
                    data:{ _token: "{{csrf_token()}}"},
                    success: function (res) {
                        showSalary(res.data);
                        calculate();
                    }
                });
            }
        }
    });

    $("input.calculate-salary").on('keyup', function() {
        calculate();
    });
});
</script>
@endpush