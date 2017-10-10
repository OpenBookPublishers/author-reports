@extends('layouts.master')
@section('title', 'Users')
@section('head')
  <link rel="stylesheet"
   href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
@endsection
@section('content')

@include('layouts.dashboard-btn')

@include('auth.register-form')

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
                                    <td>Name</td>
                                    <td>Surname</td>
                                    <td>Email</td>
                                    <td></td>
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
