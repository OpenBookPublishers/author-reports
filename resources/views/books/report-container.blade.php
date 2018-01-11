<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ $year !== null ? $year . " " : "" }}
                    Metrics report - {{ $book->title }}
                </div>

                <div class="panel-body">
                    @include('books.report')
                    <p class="text-muted">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> You may click on a year to view its monthly breakdown.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
