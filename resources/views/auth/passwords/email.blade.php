@extends('layouts.app')

@section('title', 'Password reset - OBP Author Reporting')
@section('head')
  <link href="{{ mix('css/login.css') }}" rel="stylesheet">
@endsection

@section('app')

<form class="login" method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}
    <p class="title">Password Reset</p>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="sr-only">E-Mail Address</label>
        <input id="email" type="email" name="email" placeholder="email" 
               value="{{ old('email') }}" required autofocus>
        <i class="fa fa-user"></i>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div>
        <button type="submit" class="btn btn-primary">
            <i class="spinner"></i>
            <span class="state">Send Password Reset Link</span>
        </button>
    </div>
</form>
<footer>
    <a target="blank" href="https://openbookpublishers.com/">
        openbookpublishers.com
    </a>
</footer>
@endsection
