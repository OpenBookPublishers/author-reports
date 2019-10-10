@extends('layouts.dashboard-btn')
@section('title', 'Books')
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
                                <th>Title</th>
                                <th>DOI</th>
                                <th>Public Sales</th>
                                <th>Publication Date</th>
                                <th>Authors</th>
                                <th></th>
                            </tr>
                            @foreach ($books as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->doi }}</td>
                                <td>
                                    {{ $book->areSalesPublic()
                                    ? "Yes" : "No" }}
                                </td>
                                <td>
                                    {{ $book->isPublished()? Carbon\Carbon::parse($book->publication_date)->format('M Y') : "" }}
                                </td>
                                <td>
                                    <ul>
                                    @foreach ($book->authors as $author)
                                        @if (isset($author->user))
                                        <li>
                                            <a href="{{ route('edit-user',
                                            ['user_id' => 
                                              $author->user->user_id]) }}">
                                            {{ $author->author_name }}
                                            </a>
                                        </li>
                                        @else
                                        <li>{{ $author->author_name }}</li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle "
                                                type="button"
                                                id="dropdownMenu1"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="true">
                                            Actions <span class="caret"></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu dropdown-menu-right"
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
