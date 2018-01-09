@extends('layouts.dashboard-btn')
@section('title', 'Users')
@section('head')
  <link rel="stylesheet"
   href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
@endsection
@section('secondary-btn')
    <a id="new-user-btn" class="btn btn-default pointer pull-right">
        <i class="fa fa-plus" aria-hidden="true"></i>
        New User
    </a>
    <script>
        $(document).ready(function() {
            $('#new-user-btn').click(function() {
               $('#new-user-panel').removeClass('hidden'); 
            });
        });
    </script>
@endsection
@section('content')

    <div id="new-user-panel" class="hidden">
        @include('auth.register-form')
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="min-height: 30em;">
                    <div class="panel-heading">
                        All Users
                    </div>

                    <div class="panel-body table-responsive">

                        <table class="table table-hover">
                            <tr>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Email</th>
                                <th></th>
                            </tr>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td><a href="{{ route('edit-user',
                                            ['user_id' => $user->user_id]) }}"
                                       class="btn btn-default">
                                        <i class="fa fa-pencil"
                                           aria-hidden="true"></i>
                                           Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
