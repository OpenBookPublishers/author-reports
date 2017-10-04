@extends('layouts.master')
@section('content')

<table>
  <tr>
    <td>Author</td>
    <td>Books</td>
  </tr>
  @foreach ($authors as $author)
  <tr>
    <td>{{ $author->author_name }}</td>
    <td>
    @foreach ($author->books as $book)
      {{ $book->doi }}
    @endforeach
    </td>
  </tr>
  @endforeach
</table>

@endsection
