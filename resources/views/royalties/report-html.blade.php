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
        .report-wrapper {
            font-family: "Times New Roman", arial, Verdana;
        }
    </style>
</head>
<body>
    @foreach ($books as $key => $book) 
        <?php
            $data = [];
            $data['royalties'] = $book->data;
        ?>
        <div class="report-wrapper">
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

            @foreach ($data as $name => $table)
                @if (!empty($table['data']))

                    @include('books.table')

                @endif
            @endforeach
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif

    @endforeach
</body>
</html>
