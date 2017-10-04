@extends('layouts.master')
@section('content')

<table>
  <tr>
    <td>Title</td>
    <td>Publication date</td>
    <td>DOI</td>
    <td>Authors</td>
  </tr>
  @foreach ($books as $book)
  <tr>
    <td>{{ $book->title }}</td>
    <td>{{ $book->publication_date }}</td>
    <td>{{ $book->doi }}</td>
    <td>
      <ul>
      @foreach ($book->authors as $author)
        <li>{{ $author->author_name }}</li>
      @endforeach
      </ul>
    </td>
  </tr>
  @endforeach
</table>

@endsection
