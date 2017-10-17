@extends('layouts.app')

@section('title', 'OBP Author Reporting')
@section('head')
  <link href="{{ mix('css/login.css') }}" rel="stylesheet">
  <link rel="stylesheet"
   href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
@endsection

@section('app')

<form class="login" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <p class="title">Log in</p>

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

    <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="sr-only">Password</label>
        <input id="password" type="password" placeholder="password"
               name="password" required>
        <i class="fa fa-key"></i>

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div>
        <button type="submit" class="btn btn-primary">
            <i class="spinner"></i>
            <span class="state">Login</span>
        </button>

        <a class="btn btn-link" href="{{ route('password.request') }}">
            Forgot Your Password?
        </a>
    </div>
</form>
<footer>
    <a target="blank" href="https://openbookpublishers.com/">
        openbookpublishers.com
    </a>
</footer>
@endsection
