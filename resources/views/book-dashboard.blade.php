    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $book->title }}</div>

                    <div class="panel-body">

                        <a href="{{ route('download-report',
                                    ['book_id' => $book->book_id]) }}"
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
