@extends('layouts.dashboard-btn')
@section('title', 'Metrics Report')
@section('secondary-btn')
    <a href="{{ route('admin-royalties-pdf',
                ['author_id' => $author->author_id, 'year' => $year]) }}"
       class="btn btn-default pointer pull-right">
            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            Download PDF
    </a>
@endsection
@section('content')

    @foreach ($books as $book)

    <?php
        $data = [];
        $data['royalties'] = $book->data;
    ?>

        @include('books.report-container')

    @endforeach

@endsection
