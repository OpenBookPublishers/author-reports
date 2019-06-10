<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="min-height: 30em;">
                <div class="panel-heading">
                    Readership graphs - {{ $book->title }}
                </div>

                <div class="panel-body">
                    <?php
                    if ((int)$graph_data['total']['countries']['total'] !== 0) {
                    ?>
                    @include('books.graphs')
                    <div style="clear: both;"></div>
                    <p class="text-muted">
                        <span style="font-family: \"Times New Roman\"">*</span> 'Other' represents the remaining countries from which we have received visits. The number of visits from each country is too small to list individually.
                    </p>
                    <?php
                    } else {
                        echo "Geographical data not available.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
