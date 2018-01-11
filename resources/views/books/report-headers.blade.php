@extends('layouts.dashboard-btn')
@section('title', 'Metrics Report')
@section('secondary-btn')
    <a data-toggle="modal"
       data-target="#pdf-{{ $book->book_id }}"
       class="btn btn-default pointer pull-right">
            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            Download PDF
    </a>
    @include('pdf-selector')
@endsection
@section('content')

    @include('books.report-container')

@endsection
