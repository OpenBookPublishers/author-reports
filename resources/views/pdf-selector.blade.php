<div class="modal fade" id="pdf-{{ $book->id }}" role="dialog">
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
        <a href="{{ URL::route('download-report',
                               ['book_id' => $book->book_id]) }}"
           class="btn btn-default half-width">
            <i class="fa fa-file-pdf-o"
               aria-hidden="true"></i>
            Full report
        </a>
        <br>

    @foreach ($book->getYearsActive() as $year => $months)

        <a href="{{ URL::route('download-report',
                            ['book_id' => $book->book_id, 'year' => $year]) }}"
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
