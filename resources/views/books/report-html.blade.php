<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <title>{{ $book->title }}</title>

    <link href="{{ ltrim(mix('css/app.css'), '/') }}" rel="stylesheet">
    <style>
        html, body {
            background-color: white;
            color: #000;
        }
    </style>
</head>
<body>
    <table id="header-table">
      <tr>
        <td><b>Title</b></td>
        <td>{{ $book->title }}</td>
      </tr>
      <tr>
        <td><b>DOI</b></td>
        <td>{{ $book->doi }}</td>
      </tr>
      @if ($year !== null)
      <tr>
        <td><b>Year</b></td>
        <td>{{ $year }}</td>
      </tr>
      @endif      
    </table>

    @include('books.report')

</body>
</html>
