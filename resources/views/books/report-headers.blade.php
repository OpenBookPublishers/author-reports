@extends('layouts.master')
@section('title', 'Readership Graphs')
@section('content')

@include('layouts.dashboard-btn')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Metrics report - {{ $book->title }}
                    </div>

                    <div class="panel-body">
                        @include('books.report')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
