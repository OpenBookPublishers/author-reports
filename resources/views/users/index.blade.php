@extends('layouts.master')
@section('title', 'Books')
@section('head')
  <link rel="stylesheet"
   href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
@endsection
@section('content')

@include('layouts.dashboard-btn')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="min-height: 30em;">
                    <div class="panel-heading">
                        All Users
                    </div>

                    <div class="panel-body">
                        
                        <table class="table table-responsive">
                            <tr>
                                <td>Name</td>
                                <td>Surname</td>
                                <td>Email</td>
                                <td>ORCID</td>
                                <td>Twitter</td>
                                <td></td>
                            </tr>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->orcid }}</td>
                                <td>{{ $user->twitter }}</td>
                            </tr>
                            @endforeach
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
