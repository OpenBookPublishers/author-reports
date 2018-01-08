@extends('layouts.dashboard-btn')
@section('title', 'Books')
@section('head')
  <link rel="stylesheet"
   href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
@endsection
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="min-height: 30em;">
                    <div class="panel-heading">
                        All Books
                    </div>

                    <div class="panel-body table-responsive">
                        
                        <table class="table table-hover">
                            <tr>
                                <td>Title</td>
                                <td>DOI</td>
                                <td>Authors</td>
                                <td></td>
                            </tr>
                            @foreach ($books as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->doi }}</td>
                                <td>
                                    <ul>
                                    @foreach ($book->authors as $author)
                                        <li>{{ $author->author_name }}</li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle"
                                                type="button"
                                                id="dropdownMenu1"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="true">
                                            Actions <span class="caret"></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu"
                                            aria-labelledby="dropdownMenu1">
                                            
                                            <li>
                                                <a href="{{ route('report',
                                            ['book_id' => $book->book_id]) }}">
                                                <i class="fa fa-file-text-o"
                                                   aria-hidden="true"></i>
                                                Metrics Report
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a data-toggle="modal"
                                                   data-target="#pdf-{{ $book->book_id }}"
                                                   class="pointer">
                                                <i class="fa fa-file-pdf-o"
                                                   aria-hidden="true"></i>
                                                Metrics Report (PDF)
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a href="{{ route('graphs',
                                            ['book_id' => $book->book_id]) }}">
                                                <i class="fa fa-bar-chart-o"
                                                   aria-hidden="true"></i>
                                                Readership Graphs
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a href="{{ route('map',
                                            ['book_id' => $book->book_id]) }}">
                                                <i class="fa fa-globe"
                                                   aria-hidden="true"></i>
                                                Readership Map
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @include('../pdf-selector')
                            @endforeach
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
