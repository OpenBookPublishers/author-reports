@extends('layouts.master')
@section('title', 'Readership Map')
@section('content')

@include('layouts.dashboard-btn')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Readership map - {{ $book->title }}
                    </div>

                    <div class="panel-body">
                        <div class="embed-responsive embed-responsive-4by3">
                            <iframe class="embed-responsive-item"
                                    src="{{ $map_url }}">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
