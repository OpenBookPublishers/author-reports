<style>
    .report-wrapper {
        font-family: "Times New Roman", arial, Verdana;
    }
</style>

<div class="report-wrapper">
    @foreach ($data as $name => $table)
        @if (!empty($table['data']))
            @include('books.table')
        @endif
    @endforeach
</div>
