@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

@if (Auth::user()->isAuthor())
    @include('information')
@endif

@if (Auth::user()->isAdmin())
    @include('admin-dashboard')
@endif

@if (Auth::user()->author)
    @foreach (Auth::user()->author->books as $book)
        @include('book-dashboard')
    @endforeach
@endif
@endsection
