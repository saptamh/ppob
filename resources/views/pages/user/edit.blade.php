@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-2 text-gray-800">Edit User</h1>
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
        'url' => route('user.store'),
        'method' => 'post',
        'class' => 'form',
        'role' => 'form',
        'enctype' => 'multipart/form-data'
    ]) }}
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::hidden('id', $user['id']) }}
                {{ Form::text('name', $user['name'], ['class'=>'form-control', 'placeholder'=>'Enter Name', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::email('email', $user['email'], ['class'=>'form-control', 'placeholder'=>'Enter Email', 'required'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('password', 'Password') }}
                {{ Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off', 'disabled' => 'true', 'minlength' => 6, 'required' => 'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('password_confirmation', 'Password Confirmation') }}
                {{ Form::password('password_confirmation', ['class' => 'form-control', 'disabled' => 'true', 'minlength' => 6, 'required' => 'true']) }}
                {{ Form::checkbox('check_password', 'Y', false, ['id' => 'check_password', 'onChange' => 'checkPassword()']) }}
                Check if you want change password
            </div>
            <div class="form-group">
                {{ Form::label('role', 'Role') }}
                {{ Form::select('role', $roles, $userRole, ['class'=>'form-control', 'placeholder'=>'Select Role', 'required'=>'true']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            {{ Form::submit('Save!', ['class'=>'btn btn-success btn-sm btn-block']) }}
        </div>
        <div class="col-lg-6">
            <a href="{{ route('user.main') }}" class="btn btn-warning btn-sm btn-block"> Cancel! </a>
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
<script type="text/javascript">
    function checkPassword() {
        var checkbox = document.getElementById('check_password');
        if (checkbox.checked !== true) {
            $("#password").attr('disabled', true);
            $("#password_confirmation").attr('disabled', true);
        } else {
            $("#password").removeAttr('disabled', true);
            $("#password_confirmation").removeAttr('disabled', true);
        }
    }

    $(document).ready(function() {
        checkPassword();
    });
</script>
@endpush
