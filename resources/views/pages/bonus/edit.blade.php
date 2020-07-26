@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Edit Bonus</h1>
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
        'url' => route('bonus.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('rate', 'Rate') }}
                {{ Form::hidden('id', $edit['id'])}}
                {{ Form::range('rate', $edit['rate'], ['class'=>'form-control', 'placeholder'=>'Enter Rate', 'required'=>'true', 'min'=>'1', 'max'=>'100']) }}
                <p id="demo" style="font-weight:bold;color:blue;"></p>
            </div>
            <div class="form-group">
                {{ Form::label('value', 'Value') }}
                {{ Form::number('value', $edit['value'], ['class'=>'form-control', 'placeholder'=>'Enter Value', 'required'=>'true']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm btn-block']) }}
        </div>
        <div class="col-lg-6">
            <a href="{{ route('project-item.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('script')
<script>
var slider = document.getElementById("rate");
var output = document.getElementById("demo");
output.innerHTML = slider.value; // Display the default slider value

// Update the current slider value (each time you drag the slider handle)
slider.oninput = function() {
  output.innerHTML = this.value;
}
</script>
@endpush
