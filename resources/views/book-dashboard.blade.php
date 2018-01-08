    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $book->title }}</div>

                    <div class="panel-body">

                        @if ($book->isPublished())

                        <a href="{{ route('report',
                                    ['book_id' => $book->book_id]) }}"
                           class="btn-large square relative">
                            <div class="centered full-width">
                                <i class="fa fa-file-text-o fa-large"
                                   aria-hidden="true"></i>
                                <br>
                                <span class="full-width">
                                    Metrics Report
                                </span>
                            </div>
                        </a>

                        <a data-toggle="modal" data-target="#pdf-select"
                           class="btn-large square relative pointer">
                            <div class="centered full-width">
                                <i class="fa fa-file-pdf-o fa-large"
                                   aria-hidden="true"></i>
                                <br>
                                <span class="full-width">
                                    Metrics Report (PDF)
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('graphs',
                                    ['book_id' => $book->book_id]) }}"
                           class="btn-large square relative">
                            <div class="centered full-width">
                                <i class="fa fa-bar-chart fa-large"
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

                        <div class="modal fade" id="pdf-select" role="dialog">
                          <div class="modal-dialog">

                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal">
                                    &times;
                                </button>
                                  <h4 class="modal-title">
                                      Metrics report (PDF) - 
                                      {{ $book->title }}
                                  </h4>
                              </div>
                              <div class="modal-body text-center">
                                <a href="{{ URL::route('download-report', ['book_id' => $book->book_id]) }}"
                                   class="btn btn-default half-width">
                                    <i class="fa fa-file-pdf-o"
                                       aria-hidden="true"></i>
                                    Full report
                                </a>
                                <br>

                            @foreach ($book->getYearsActive() as $year => $months)

                                <a href="{{ URL::route('download-report', ['book_id' => $book->book_id, 'year' => $year]) }}"
                                   class="btn btn-default line-break half half-width">
                                    <i class="fa fa-file-pdf-o"
                                       aria-hidden="true"></i>
                                    {{ $year }}
                                </a>
                                <br>
                            @endforeach
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">
                                    Close
                                </button>
                              </div>
                            </div>

                          </div>
                        </div>

                        @else
                        <p>
                            Upon publication of your book, you will be able to access readership, downloads, and sales reports via this interface.
                        </p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
