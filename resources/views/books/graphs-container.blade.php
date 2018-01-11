<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="min-height: 30em;">
                <div class="panel-heading">
                    Readership graphs - {{ $book->title }}
                </div>

                <div class="panel-body">
                    <p class="text-muted line-break-double">
                        N.B. Only readership for which we have geographical information is displayed. "Other" represents other countries.
                    </p>
                    @include('books.graphs')
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
