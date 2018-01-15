<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <title>Royalties report</title>

    <link href="{{ ltrim(mix('css/app.css'), '/') }}" rel="stylesheet">
    <style>
        html, body {
            background-color: white;
            color: #000;
        }
    </style>
</head>
<body>
    <?php
    foreach ($books as $book) {
        $data = [];
        $data['royalties'] = $book->data;
    ?>
    <table id="header-table">
      <tr>
        <td><b>Title</b></td>
        <td>{{ $book->title }}</td>
      </tr>
      <tr>
        <td><b>DOI</b></td>
        <td>{{ $book->doi }}</td>
      </tr>
    </table>

    @include('books.report')

    <div style="height:1px; page-break-after:always;"></div>
    
    <?php
    }
    ?>
</body>
</html>
