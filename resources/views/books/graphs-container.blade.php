<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="min-height: 30em;">
                <div class="panel-heading">
                    Readership graphs - {{ $book->title }}
                </div>

                <div class="panel-body">
                    @include('books.graphs')
                    <div style="clear: both;"></div>
                    <p class="text-muted">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> Only readership for which we have geographical information is displayed. "Other" represents other countries.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
