@extends('layouts.master')

@section('title', 'Dashboard')
@section('head')
  <link rel="stylesheet"
   href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
@endsection

@section('content')

@if (Auth::user()->isAuthor())
    @include('information')
@endif

@if (Auth::user()->author)
    @foreach (Auth::user()->author->books as $book)
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $book->title }}</div>

                    <div class="panel-body">

                        <a href="{{ route('dashboard',
                                    ['doi' => $book->book_id]) }}"
                           class="btn-large square relative">
                            <div class="centered full-width">
                                <i class="fa fa-file-pdf-o fa-large"
                                   aria-hidden="true"></i>
                                <br>
                                <span class="full-width">
                                    Full Report
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('graphs',
                                    ['book_id' => $book->book_id]) }}"
                           class="btn-large square relative">
                            <div class="centered full-width">
                                <i class="fa fa-pie-chart fa-large"
                                   aria-hidden="true"></i>
                                <br>
                                <span class="full-width">
                                    Readership Graphs
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('map',
                                    ['book_id' => $book->book_id]) }}"
                           target="_blank"
                           class="btn-large square relative">
                            <div class="centered full-width">
                                <i class="fa fa-globe fa-large"
                                   aria-hidden="true"></i>
                                <br>
                                <span class="full-width">
                                    Readership Map
                                </span>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
