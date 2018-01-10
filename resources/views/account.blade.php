@extends('layouts.dashboard-btn')
@section('title', 'Account')
@section('secondary-btn')
    <a id="password-btn" class="btn btn-default pointer pull-right">
        <i class="fa fa-key" aria-hidden="true"></i>
        Change password
    </a>
    <script>
        $(document).ready(function() {
            $('#password-btn').click(function() {
               $('#password-panel').removeClass('hidden'); 
            });
        });
    </script>
@endsection
@section('content')

    <div id="password-panel" class="container {{ $errors->has('old-password') || $errors->has('password') || $errors->has('password_confirmation') ? '' : 'hidden' }}">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="min-height: 20em;">
                    <div class="panel-heading">
                        Password update
                    </div>

                    <div class="panel-body">
                        @include('users.password-form')
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="min-height: 30em;">
                    <div class="panel-heading">
                        Account details
                    </div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST"
                              action="{{ route('update-account') }}">
                            {{ csrf_field() }}

                            @include('users.personal-details')

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit"
                                            class="btn btn-primary">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
