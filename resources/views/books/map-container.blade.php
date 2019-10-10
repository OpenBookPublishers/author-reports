<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Readership map - {{ $book->title }}
                </div>

                <script>
                    const apiEndp = 'https://metrics.api.openbookpublishers.com';
                    const workUri = "{{ $book->getWorkUriStr() }}";
                </script>
                <div class="panel-body" style="width: 100%;height:95vh">
                    @include('books.map')
                    <p class="text-muted" style="margin-top: -1.5em; margin-left: 5em;">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> Geographical data is only available for a small subset of the total readership data.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
