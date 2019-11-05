@extends('layouts.master')
@section('title', 'Metrics Report')

@section('content')

    @if ($book->isPublished())
        @include('books.report-container')
        @include('books.map-container')
    @endif

@endsection
