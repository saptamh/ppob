@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Create Role</h1>
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
        'url' => route('role.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::hidden('id', $role->id )}}
        {{ Form::text('name', $role->name, ['class'=>'form-control', 'placeholder'=>'Enter Name', 'required'=>'true']) }}
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    @php
        $numOfCols = 4;
        $rowCount = 0;
        $bootstrapColWidth = 12 / $numOfCols;
    @endphp
        <div class="form-group">
            <strong>Permission:</strong>
            <br/>
            <div class="row">
                @foreach($permission as $value)
                    <div class="col-md-<?php echo $bootstrapColWidth; ?>">
                    <label>
                        {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                        {{ $value->name }}
                    </label>
                        <br/>
                    </div>
                    <?php
                        $rowCount++;
                        if($rowCount % $numOfCols == 0) echo '</div><div class="row">';
                    ?>
                @endforeach
            </div>
        </div>
    </div>
    <div class="form-group">
        {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm']) }}
        <a href="{{ route('role.main') }}" class="btn btn-warning btn-sm"> Cancel! </a>
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('style')
<link href="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/css.gijgo.min.css') }}" rel="stylesheet">
@endpush
@push('script')
<script src="{{ URL::asset('themes/vendor/gijgo-combined-1.9.13/js.gijgo.min.js') }}"></script>
@endpush
