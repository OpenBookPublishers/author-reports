@extends('layouts.app')

@section('title', 'OBP Author Reporting')
@section('head')
  <link href="{{ mix('css/login.css') }}" rel="stylesheet">
@endsection

@section('app')

<form class="login" method="POST" action="{{ route('password.request') }}">
    {{ csrf_field() }}
    <p class="title">Create a new password</p>

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="sr-only">E-Mail Address</label>
        <input id="email" type="email" name="email" placeholder="email" 
               value="{{ $email or old('email') }}" required autofocus>
        <i class="fa fa-user"></i>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="sr-only">Password</label>
        <input id="password" type="password" placeholder="New password"
               name="password" required>
        <i class="fa fa-key"></i>

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="{{ $errors->has('password_confirmation')
        ? ' has-error' : '' }}">
        <label for="password" class="sr-only">Confirm Password</label>
        <input id="password-confirm" name="password_confirmation"
               type="password" placeholder="Confirm password" required>
        <i class="fa fa-key"></i>

        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
    </div>

    <div>
        <button type="submit" class="btn btn-primary">
            <i class="spinner"></i>
            <span class="state">Reset Password</span>
        </button>
    </div>
</form>
<footer>
    <a target="blank" href="https://openbookpublishers.com/">
        openbookpublishers.com
    </a>
</footer>
@endsection
