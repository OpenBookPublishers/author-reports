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
                @include('books.map')
            </div>
        </div>
    </div>
</div>
