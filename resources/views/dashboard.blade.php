@extends('layouts.master')

@section('title', 'Dashboard')
@section('head')
  <link rel="stylesheet"
   href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
@endsection

@section('content')

@if (Auth::user()->isAuthor())
    @include('information')
@endif

@if (Auth::user()->author)
    @foreach (Auth::user()->author->books as $book)
        @include('book-dashboard')
    @endforeach
@endif
@endsection