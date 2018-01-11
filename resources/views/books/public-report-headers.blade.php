@extends('layouts.master')
@section('title', 'Metrics Report')

@section('content')

    @include('books.report-container')
    @include('books.map')
    @include('books.graphs-container')

@endsection
